<?php

namespace App\Model;

use App\Pembelian;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $guarded = [];


    public function pembelian() {
        return $this->hasMany(Pembelian::class);
    }
}
