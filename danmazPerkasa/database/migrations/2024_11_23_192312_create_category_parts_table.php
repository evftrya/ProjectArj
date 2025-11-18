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
        Schema::create('category_parts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_part')->unsigned();
            $table->unsignedBigInteger('id_category_part')->unsigned();
            $table->timestamps();

            $table->foreign('id_part')->references('id_product')->on('products')->onDelete('cascade');
            $table->foreign('id_category_part')->references('id_category_part')->on('ref_category_parts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_parts');
    }
};
