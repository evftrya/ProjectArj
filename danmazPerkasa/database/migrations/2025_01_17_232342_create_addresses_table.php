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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id('idAddress');
            $table->timestamps();
            $table->string('Provinsi')->nullable();
            $table->string('KotaKabupaten')->nullable();
            $table->string('Kecamatan')->nullable();
            $table->string('Kelurahan')->nullable();
            $table->string('RT')->nullable();
            $table->string('RW')->nullable();
            $table->string('KodePos')->nullable();
            $table->longText('Detil')->nullable();
            $table->longText('AlamatDetil')->nullable();
            $table->unsignedBigInteger('id_user');
            
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
