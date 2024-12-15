<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('id_product')->autoIncrement();
            $table->string('nama_product');
            $table->string('type');
            $table->integer('stok');
            $table->string('price');
            $table->string('isSpecial')->nullable();
            $table->Integer('weight');
            $table->string('Category')->nullable();
            $table->longText('detail_product');
            $table->longText('Features')->nullable();
            $table->string('mainPhoto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
