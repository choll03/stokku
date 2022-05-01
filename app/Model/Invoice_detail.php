<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Invoice_detail extends Model
{
    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo('App\Model\Barang');
    }

    public function invoice()
    {
        return $this->belongsTo('App\Model\Invoice');
    }
}
