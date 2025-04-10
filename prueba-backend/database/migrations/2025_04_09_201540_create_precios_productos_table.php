<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreciosProductosTable extends Migration
{
    public function up()
    {
        Schema::create('precios_productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('currency_id');
            $table->decimal('price', 8, 2);
            $table->timestamps();

            $table->foreign('product_id')
                  ->references('id')
                  ->on('productos')
                  ->onDelete('cascade');

            $table->foreign('currency_id')
                  ->references('id')
                  ->on('divisas');
        });
    }

    public function down()
    {
        Schema::dropIfExists('precios_productos');
    }
}
