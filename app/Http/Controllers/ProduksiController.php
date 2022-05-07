<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProduksiController extends Controller
{

    public function bulatPress() {
        return view('produksi.bulat-press');
    }

    public function printBulat(Request $request) {

        $arr_data = [];

        foreach ($request->data_row as $d) {
            $arr_data[] = json_decode($d);
        }

        return view('pdf.produksi', [
            'dataRow'    => $arr_data,
            'dataTotal'   => json_decode($request->dataTotal)
        ]);
    }
}
