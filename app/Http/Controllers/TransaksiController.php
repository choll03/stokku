<?php

namespace App\Http\Controllers;

use App\Model\Barang;
use App\PartnerInvitation;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Model\Invoice_detail;
use App\Model\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Item;
use App\CoreItem;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

use DNS2D;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        return view('transaksi.index', ['type' => $request->type]);
    }


    public function getData(Request $request)
    {
        $user = auth()->user();
        $warung = $user->warung;

        $harga = 'barangs.harga_jual';

        if ($request->type == 'offline') {
            $harga = 'barangs.harga_jual_offline';
        }

        $barangs = Barang::select(DB::raw("
                    barangs.id as id,
                    barangs.warung_id as warung_id,
                    barangs.kode_barang as kode_barang,
                    barangs.nama as nama,
                    null as nama_toko,".
                    $harga . " as harga_jual,"
                    ."IFNULL((SELECT SUM(jumlah) FROM pembelian_details WHERE barang_id = barangs.id), 0) - IFNULL((SELECT SUM(qty) FROM invoice_details WHERE barangs.id = invoice_details.barang_id ),0) as stok"))
            ->where('warung_id', $warung->id)
            ->where('active', 1);

        $arr = [];
        $invitationId = PartnerInvitation::where(function ($q) use ($warung) {
            $q->where('from', $warung->id);
            $q->orWhere('to', $warung->id);
        })->where('success', 1)->get();

        foreach ($invitationId as $data) {
            if ($data->to == $warung->id) {
                $arr[] = $data->to;
            } else {
                $arr[] = $data->from;
            }
        };

        $barangJoined = Barang::select(DB::raw("
                    barangs.id as id,
                    barangs.warung_id as warung_id,
                    barangs.kode_barang as kode_barang,
                    barangs.nama as nama,
                    (SELECT nama FROM warungs WHERE warungs.id = barangs.warung_id) as nama_toko,
                    harga_jual_offline as harga_jual,"
            ."IFNULL((SELECT SUM(jumlah) FROM pembelian_details WHERE barang_id = barangs.id), 0) - IFNULL((SELECT SUM(qty) FROM invoice_details WHERE barangs.id = invoice_details.barang_id ),0) as stok"))
            ->join('product_colaborations', 'product_colaborations.barang_id', '=', 'barangs.id')
            ->where('barangs.active', 1)
            ->where('product_colaborations.isConfirm', 1)
            ->whereIn('product_colaborations.warung_id', $arr);

        $barangs->union($barangJoined);

        return Datatables::of($barangs)
        ->addColumn('actions', function ($data) use ($request){
            return '
                <button type="button" class="increment btn btn-sm btn-success" data-barang='. var_export(json_encode($data), true) .' >+</button>
                <button type="button" class="decrement btn btn-sm btn-danger" data-barang='. var_export(json_encode($data), true) .'>-</button>
            ';
        })
        ->rawColumns(['actions'])
        ->make(true);
    }

    public function getDataPenjualan() {
        $user = auth()->user();

        return Datatables::of($user->invoice()->with('detail'))
            ->addColumn('actions', function ($data) {
                return '
                <a href="'. route('laporan.show', $data->id) .'" class="btn btn-primary">Detail</a>
            ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }


    public function store(Request $request)
    {
        $user = $request->user();

        $warung = $user->warung;

        $no_transaksi = date('Ymd') . str_pad($warung->id, 4, '0', STR_PAD_LEFT) . '001';

        $last_invoice = $user->invoice()->orderByDesc('id')->first();

        if($last_invoice){
            $last_nomor = $last_invoice->no_transaksi;
            if(substr($last_nomor, 0, 6) == date("Ym")){
                $no_transaksi = $last_nomor + 1;
            }
        }

        DB::beginTransaction();
        try {

            $invoice = $user->invoice()->create([
                'no_transaksi'  => $no_transaksi,
                'nama_pembeli' => $request->nama_pembeli,
                'type' => $request->type_trx
            ]);

            foreach($request->nama as $key => $value)
            {
                $invoice->detail()->create([
                    'barang_id'     => $key,
                    'nama'          => $value,
                    'qty'           => $request->qty[$key],
                    'harga'         => $request->harga[$key] / $request->qty[$key],
                    'warung_id'     => $request->warung_id[$key]
                ]);
            }
            DB::commit();
            return response()->json([
                'message'   => 'Transaksi berhasil dibuat',
                'data'      => $invoice
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json($th->getMessage(), 403);
        }

    }

    public function laporan()
    {
        $user = auth()->user();

        $invoice = $user->invoice()
                    ->selectRaw('invoices.*, (SELECT SUM(harga * qty) FROM invoice_details WHERE invoice_details.invoice_id = invoices.id) as total')
                    ->latest()
                    ->get();

        return view('transaksi.laporan', ['data' => $invoice]);
    }

    public function laporanShow($id)
    {
        $invoice = auth()->user()->invoice()->with('detail')->where('id', $id)->first();
        $barcode = DNS2D::getBarcodeHTML( route('transaksi.print_barcode', $id) , "QRCODE");
        return view('transaksi.detail', [
            'data'      => $invoice,
            'barcode'   => $barcode
        ]);
    }

    public function print($id)
    {
        $user = auth()->user();

        $warung = $user->warung;
        $invoice =  $user->invoice()->with('detail')->where('id', $id)->first();

        try {
            $ip = "127.0.0.1";
            // $ip = "127.0.0.1";
            $connector = new WindowsPrintConnector("smb://". $ip ."/POS-58");
            $printer = new Printer($connector);

            $total = 0;
            /* Date is kept the same for testing */
            // $date = date('l jS \of F Y h:i:s A');
            // date_default_timezone_set('Asia/Jakarta');
            date_default_timezone_set("Asia/Jakarta");
            $date = date("d m Y H:i:s");

            /* Start the printer */
            // $logo = EscposImage::load("resources/escpos-php.png", false);
            // $printer = new Printer($connector);

            /* Print top logo */
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            // $printer -> graphics($logo);

            /* Name of shop */
            $printer->setEmphasis(true);
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer->text(strtoupper($warung->nama . "\n"));
            $printer->selectPrintMode();
            $printer->text($warung->alamat);
            $printer->feed();
            $printer->setEmphasis(false);


            /* Items */
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setEmphasis(true);
            $printer->text(new CoreItem('Nomor', $invoice->no_transaksi));
            $printer->text(new CoreItem('Tanggal', $invoice->created_at->format("d/m/Y")));
            $printer->feed();

            $printer->setEmphasis(false);
            foreach ($invoice->detail as $item) {
                $total += ($item->harga * $item->qty);
                $printer->text(new Item($item->nama, $item->qty ,($item->harga * $item->qty)));
            }
            $printer->feed();
            $printer->setEmphasis(true);
            $printer->text(new CoreItem('Total', $total));
            $printer->setEmphasis(false);

            /* Tax and total */
            $printer->text(new CoreItem('Tunai', $invoice->tunai));
            // $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer->text(new CoreItem('Kembali', ($invoice->tunai - $total)));
            $printer->selectPrintMode();

            /* Footer */
            $printer->feed(2);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Terima kasih sudah berbelanja di ". strtoupper($warung->nama) ."\n");
            $printer->feed(2);
            $printer->text($date . "\n");
            $printer->feed(4);

            /* Cut the receipt and open the cash drawer */
            $printer->cut();
            $printer->pulse();

            $printer->close();

        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function printJs($id)
    {
        $user = auth()->user();
        return view('pdf.invoice', [
            'warung'    => $user->warung,
            'invoice'   => $user->invoice()->with('detail')->where('id', $id)->first(),
            'date'      => Carbon::now()->format("d M Y H:i:s")
        ]);
    }

    public function printBarcode($id)
    {
        $invoice = Invoice::with('detail')->where('id', $id)->first();
        $warung = $invoice->user->warung;
        return view('pdf.invoice_barcode', [
            'warung'    => $warung,
            'invoice'   => $invoice,
            'date'      => Carbon::now()->format("d M Y H:i:s")
        ]);
    }
}
