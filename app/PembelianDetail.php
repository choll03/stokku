<?php

namespace App;

use App\Model\Barang;
use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    protected $guarded=[];

    public function barang() {
        return $this->belongsTo(Barang::class);
    }
}
