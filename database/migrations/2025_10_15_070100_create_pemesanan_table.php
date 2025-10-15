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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->increments('id_pemesanan');
            $table->unsignedInteger('id_pelanggan');
            $table->unsignedInteger('id_petugas');
            $table->unsignedInteger('id_jadwal');
            $table->text('lokasi');
            $table->text('catatan')->nullable();
            $table->enum('status', ['menunggu', 'dikonfirmasi', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->timestamp('tanggal_pesan')->useCurrent();

            $table->index('id_pelanggan');
            $table->index('id_petugas');
            $table->index('id_jadwal');

            $table->foreign('id_pelanggan')
                ->references('id_user')->on('users')
                ->onDelete('cascade');
            $table->foreign('id_petugas')
                ->references('id_user')->on('users')
                ->onDelete('cascade');
            $table->foreign('id_jadwal')
                ->references('id_jadwal')->on('jadwal_petugas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
