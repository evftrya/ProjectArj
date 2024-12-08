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
        Schema::create('detail__transactions', function (Blueprint $table) {
            $table->id('id_Detail_transaction')->autoIncrement();
            $table->unsignedBigInteger('id_product');
            $table->unsignedBigInteger('id_User');
            $table->unsignedBigInteger('qty');
            $table->unsignedBigInteger('Total');
            $table->String('Status')->default('Pending');
            $table->timestamps();
            
            $table->foreignId('Transaksis_id')->nullable()->constrained('Transaksis')->nullOnDelete();
            $table->foreign('id_product')->references('id_product')->on('Products')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail__transactions');
    }
};
