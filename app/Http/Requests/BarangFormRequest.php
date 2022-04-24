<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BarangFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'kode_barang'   => 'required',
            'nama'           => 'required|min:6',
            'harga_jual'     => 'required|numeric',
            'stok_limit'     => 'required|numeric',
        ];
    }
}
