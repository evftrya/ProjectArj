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
        Schema::create('return_pesanans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_detil_transaksi');
            $table->String('Barang')->default(null)->nullable();
            $table->unsignedBigInteger('qty_retur')->nullable();
            $table->longText('alasan_retur')->nullable();
            $table->longText('link_bukti')->nullable();
            $table->boolean('persetujuan_1')->nullable();
            $table->boolean('persetujuan_2')->nullable();
            $table->boolean('retur_status')->nullable();
            $table->longText('alasan_ditolak')->nullable();
            $table->String('Ekspedisi')->nullable();
            $table->String('Resi')->nullable();
            $table->timestamps();

            $table->foreign('id_detil_transaksi')->references('id_Detail_transaction')->on('detail__transactions')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_pesanans');
    }
};
