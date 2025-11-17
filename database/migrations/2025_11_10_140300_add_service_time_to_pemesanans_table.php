<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            if (!Schema::hasColumn('pemesanans', 'service_time')) {
                $table->time('service_time')->nullable()->after('service_date');
            }
        });
    }

    public function down()
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            if (Schema::hasColumn('pemesanans', 'service_time')) {
                $table->dropColumn('service_time');
            }
        });
    }
};
