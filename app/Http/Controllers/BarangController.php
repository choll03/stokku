<?php

namespace App\Http\Controllers;

use App\Http\Requests\BarangFormRequest;
use App\Model\Barang;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return redirect(route('barang.create'));
        return view('barang.index');
    }

    public function getData()
    {
        $user = auth()->user();
        return Datatables::of($user->barang)
        ->addColumn('actions', function ($data) {
            return '
                <div style="display:flex;justify-content: center;">
                <a href="'. route('barang.edit', $data->id) .'" class="btn btn-sm btn-primary">Edit</a>&nbsp;
                <form action="'. route('barang.destroy', $data->id) .'" method="POST">
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
        return view('barang.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BarangFormRequest $request)
    {
        $user   = $request->user();
        $warung = $user->warung;

        $isBarangExists = $user->barang()->where('kode_barang', 'LIKE','%'. $request->kode_barang)->exists();

        if ($isBarangExists) {
            return redirect()->back()->with('err', 'Kode barang sudah terpakai')->withInput($request->all());
        }

        $kodeBarang = str_pad($warung->id, 4, '0', STR_PAD_LEFT) . '-'. $request->kode_barang;

        $warung->barang()->create([
            'kode_barang'   => $kodeBarang,
            'nama'          => $request->nama,
            'harga_jual'    => $request->harga_jual,
            'stok_limit'    => $request->stok_limit,
        ]);

        session()->flash('status', 'Barang berhasil di buat');

        return redirect(route('barang.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

        $barang = $user->barang()->find($id);

        $kodeBarangs = [];
        $explodeKodeBarang = explode("-", $barang->kode_barang);
        foreach ($explodeKodeBarang as $key => $value) {
            if ($key != 0) $kodeBarangs[] = $value;
        }

        $barang->kode_barang = implode("-", $kodeBarangs);

        return view('barang.edit', ['data' => $barang]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BarangFormRequest $request, $id)
    {

        $barang = Barang::find($id);

        $kodeWarung = explode("-",$barang->kode_barang)[0];
        $kodeBarang = str_pad($kodeWarung, 4, '0', STR_PAD_LEFT) . '-'. $request->kode_barang;

        if ($barang->kode_barang != $kodeBarang) {
            $isBarangExists = $barang->where('kode_barang', 'LIKE','%'. $request->kode_barang)->exists();

            if ($isBarangExists) {
                return redirect()->back()->with('err', 'Kode barang sudah terpakai')->withInput($request->all());
            }
        }

        $barang->update([
            'kode_barang' => $kodeBarang,
            'nama'          => $request->nama,
            'harga_jual'    => $request->harga_jual,
            'stok_limit'    => $request->stok_limit,
        ]);

        session()->flash('status', 'Barang berhasil di ubah');
        return redirect(route('barang.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();

        $user->barang()->find($id)->delete();

        session()->flash('status', 'Barang berhasil di hapus');
        return redirect(route('barang.index'));
    }


    public function stok() {
        return view('barang.stok');
    }

    public function hargaBarang() {
        return view('barang.harga_dasar');
    }

    public function getStokBarang()
    {
        $user = auth()->user();
        $warung = $user->warung;
        $barangs = Barang::select(DB::raw("
                    barangs.*,
                    IFNULL((SELECT SUM(jumlah) FROM pembelian_details WHERE barang_id = barangs.id), 0) - IFNULL((SELECT SUM(qty) FROM invoice_details WHERE barangs.id = invoice_details.barang_id ),0) as stok"))
            ->where('warung_id', $warung->id)
            ->orderBy('stok')
            ->get();
        return Datatables::of($barangs)->make(true);
    }

    public function getDataHarga() {
        $user = auth()->user();
        $warung = $user->warung;
        $barangs = Barang::select(DB::raw("
                    barangs.*,
                    (SELECT harga_beli FROM pembelian_details WHERE pembelian_details.barang_id = barangs.id ORDER BY created_at DESC LIMIT 1) as harga_beli"))
            ->where('warung_id', $warung->id)
            ->orderBy('kode_barang')
            ->get();
        return Datatables::of($barangs)->make(true);
    }
}
