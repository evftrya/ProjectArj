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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('id_user');
            $table->integer('Total')->nullable();
            $table->string('Shipping')->nullable();
            $table->string('PaymentMethod')->nullable();
            $table->string('Status_Pembayaran')->default('Pending');
            $table->string('Status_Pengiriman')->nullable();
            $table->string('Notes')->nullable();
            $table->timestamps();


            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
