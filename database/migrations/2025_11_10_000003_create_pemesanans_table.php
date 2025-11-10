<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('jenis_service_id');
            $table->text('alamat');
            $table->date('service_date');
            $table->integer('duration'); // in hours
            $table->enum('preferred_gender', ['any', 'male', 'female'])->default('any');
            $table->enum('status', ['pending', 'diproses', 'diterima', 'ditolak', 'selesai'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id_user')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('jenis_service_id')
                  ->references('id')
                  ->on('jenis_services')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemesanans');
    }
};
