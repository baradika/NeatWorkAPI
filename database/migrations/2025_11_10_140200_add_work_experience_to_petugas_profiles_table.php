<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('petugas_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('petugas_profiles', 'work_experience')) {
                $table->text('work_experience')->nullable()->after('gender');
            }
        });
    }

    public function down()
    {
        Schema::table('petugas_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('petugas_profiles', 'work_experience')) {
                $table->dropColumn('work_experience');
            }
        });
    }
};
