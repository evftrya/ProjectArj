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
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_User')->autoIncrement();
            $table->string('namaUser');
            $table->string('emailUser')->unique();
            $table->string('passwordUser');
            $table->string('role');
            $table->string('Phone')->nullable();
            $table->string('Gender')->nullable();
            $table->string('Address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
