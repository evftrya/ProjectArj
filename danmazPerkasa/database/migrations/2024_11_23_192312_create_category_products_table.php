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
        Schema::create('category_products', function (Blueprint $table) {
            $table->id('id_category_product')->autoIncrement();
            $table->bigInteger('id_product')->unsigned();
            $table->string('category_name');
            $table->timestamps();

            $table->foreign('id_product')->references('id_product')->on('Products')->onDelete('cascade');
            $table->foreign('category_name')->references('category_name')->on('ref_category_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_products');
    }
};
