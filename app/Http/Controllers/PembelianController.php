<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormPembelianBarangReuest;
use App\Model\Barang;
use App\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pembelian.index');
    }

    public function getData()
    {
        $user = auth()->user();

        return Datatables::of($user->pembelian()->with(['detail' => function($q) {
            $q->leftJoin('barangs', 'barangs.id', '=', 'pembelian_details.barang_id');
        }]))
            ->addColumn('actions', function ($data) {
                return '
                <div style="display:flex;justify-content: center;">
                <a href="'. route('pembelian.show', $data->id) .'" class="btn btn-sm btn-primary">Detail</a>&nbsp;
                <form action="'. route('pembelian.destroy', $data->id) .'" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                    <button class="btn btn-sm btn-danger" onclick="return confirm('. var_export("Anda yakin ingin menghapus barang ini?", true) .')">Hapus</button>
                </form>
                </div>
            ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $barangs = auth()->user()->barang()->where('active', 1)->get();

        $dataBarang = [];

        foreach ($barangs as $barang) {
            $dataBarang[$barang->id] = '(' . $barang->kode_barang . ') '. $barang->nama;
        }

        return view('pembelian.create', ['data' => $dataBarang]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $warung = $user->warung;

        $no_transaksi = date('Ymd') . str_pad($warung->id, 4, '0', STR_PAD_LEFT) . '001';

        $last_invoice = $user->pembelian()->orderByDesc('id')->first();

        if($last_invoice){
            $last_nomor = $last_invoice->no_transaksi;
            if(substr($last_nomor, 0, 6) == date("Ym")){
                $no_transaksi = $last_nomor + 1;
            }
        }

        DB::beginTransaction();
        try {

            $pembelian = $user->pembelian()->create([
                'no_transaksi'          => $no_transaksi,
                'no_invoice_penjual'    => $request->no_invoice_penjual,
                'nama_penjual'          => $request->nama_penjual
            ]);

            foreach ($request->harga_beli as $idBarang => $value) {
                $pembelian->detail()->create([
                    'barang_id'     => $idBarang,
                    'harga_beli'    => $value,
                    'jumlah'        => $request->jumlah[$idBarang]
                ]);

            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json($th->getMessage(), 403);
        }

        session()->flash('status', 'Pembelian Barang berhasil di buat');

        return redirect(route('pembelian.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pembelian = Pembelian::find($id);
        $detail = $pembelian->detail()->with('barang')->get();

        return view('pembelian.edit', ['pembelian' => $pembelian , 'detail' => $detail]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();

        $barangs = $user->barang()->where('active', 1)->get();

        $dataBarang = [];

        foreach ($barangs as $barang) {
            $dataBarang[$barang->id] = '(' . $barang->kode_barang . ') '. $barang->nama;
        }

        $pembelian = Pembelian::find($id);

        return view('pembelian.edit', [
            'data' => $pembelian,
            'barangs' => $dataBarang
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pembelian = Pembelian::find($id);
        $pembelian->update([
            'no_invoice_penjual'    => $request->no_invoice_penjual,
            'nama_penjual'     => $request->nama_penjual
        ]);

        session()->flash('status', 'Pembelian Barang berhasil di ubah');
        return redirect(route('pembelian.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Pembelian::find($id)->delete();

        session()->flash('status', 'Barang berhasil di hapus');
        return redirect(route('pembelian.index'));
    }
}
