<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rating_pesanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pemesanan');
            $table->integer('rating');
            $table->text('ulasan')->nullable();
            $table->timestamps();

            $table->foreign('id_pemesanan')
                  ->references('id')
                  ->on('pemesanans')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rating_pesanan');
    }
};
