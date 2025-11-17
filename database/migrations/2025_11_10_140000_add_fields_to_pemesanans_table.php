<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            if (!Schema::hasColumn('pemesanans', 'people_count')) {
                $table->integer('people_count')->default(1)->after('preferred_gender');
            }
            if (!Schema::hasColumn('pemesanans', 'total_harga')) {
                $table->decimal('total_harga', 10, 2)->nullable()->after('catatan');
            }
            if (!Schema::hasColumn('pemesanans', 'tanggal_pesan')) {
                $table->timestamp('tanggal_pesan')->nullable()->after('total_harga');
            }
        });
    }

    public function down()
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            if (Schema::hasColumn('pemesanans', 'people_count')) {
                $table->dropColumn('people_count');
            }
            if (Schema::hasColumn('pemesanans', 'total_harga')) {
                $table->dropColumn('total_harga');
            }
            if (Schema::hasColumn('pemesanans', 'tanggal_pesan')) {
                $table->dropColumn('tanggal_pesan');
            }
        });
    }
};
