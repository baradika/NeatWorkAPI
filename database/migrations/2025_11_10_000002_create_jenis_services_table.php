<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jenis_services', function (Blueprint $table) {
            $table->id();
            $table->string('kode_service', 10)->unique();
            $table->string('nama_service', 100);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2);
            $table->integer('estimasi_waktu'); // in minutes
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jenis_services');
    }
};
