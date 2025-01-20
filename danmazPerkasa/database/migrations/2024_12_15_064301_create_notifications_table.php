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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('idNotification')->autoIncrement();
            $table->enum('type', ['Product', 'Transaction', 'Progress', 'Address'])->nullable();
            $table->string('link')->nullable();
            $table->string('Icon')->nullable();
            $table->string('Title')->nullable();
            $table->string('Detil')->nullable();
            $table->string('isRead')->nullable();
            $table->unsignedBigInteger('id_user');

            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
