<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 8, 2);
            $table->unsignedBigInteger('currency_id');
            $table->decimal('tax_cost', 8, 2);
            $table->decimal('manufacturing_cost', 8, 2);
            $table->timestamps();

            $table->foreign('currency_id')
                  ->references('id')
                  ->on('divisas')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
