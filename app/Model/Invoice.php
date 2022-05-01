<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $guarded = [];

    public function detail()
    {
        return $this->hasMany(Invoice_detail::class);
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
