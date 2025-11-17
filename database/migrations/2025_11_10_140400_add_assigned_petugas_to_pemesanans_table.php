<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            if (!Schema::hasColumn('pemesanans', 'assigned_petugas_id')) {
                $table->unsignedInteger('assigned_petugas_id')->nullable()->after('user_id');
                $table->index('assigned_petugas_id');
                $table->foreign('assigned_petugas_id')
                    ->references('id_user')
                    ->on('users')
                    ->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            if (Schema::hasColumn('pemesanans', 'assigned_petugas_id')) {
                $table->dropForeign(['assigned_petugas_id']);
                $table->dropIndex(['assigned_petugas_id']);
                $table->dropColumn('assigned_petugas_id');
            }
        });
    }
};
