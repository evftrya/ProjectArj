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
        Schema::create('ref_category_parts', function (Blueprint $table) {
            $table->id('id_category_part')->autoIncrement();

            // $table->id('id_category_part')->autoIncrement()->primary();
            $table->string('Area');
            $table->string('Category');
            $table->string('Count')->nullable();
            $table->string('Types');
            $table->timestamps();

            $table->foreign('Area')->references('Area')->on('ref_area_category_parts')->onDelete('cascade');
            $table->foreign('Types')->references('category_name')->on('ref_category_products')->onDelete('cascade');

            // $table->foreign('Area')->references('Area')->on('ref_area_category_parts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_category_parts');
    }
};
