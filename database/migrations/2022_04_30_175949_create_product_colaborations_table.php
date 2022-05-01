<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductColaborationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_colaborations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('barang_id');
            $table->bigInteger('warung_id');
            $table->boolean('isConfirm')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_colaborations');
    }
}
