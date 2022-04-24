<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePembelianDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembelian_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('pembelian_id')->unsigned();
            $table->bigInteger('barang_id')->unsigned();
            $table->integer('harga_beli')->default(0);
            $table->integer('jumlah')->default(0);
            $table->timestamps();
            $table->foreign('pembelian_id')->references('id')->on('pembelians')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembelian_details');
    }
}
