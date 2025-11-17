<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('petugas_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('petugas_profiles', 'gender')) {
                $table->enum('gender', ['male', 'female'])->after('address');
            }
        });
    }

    public function down()
    {
        Schema::table('petugas_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('petugas_profiles', 'gender')) {
                $table->dropColumn('gender');
            }
        });
    }
};
