<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $guarded = [];

    public function detail() {
        return $this->hasMany(PembelianDetail::class);
    }
}
