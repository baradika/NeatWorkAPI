<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            if (!Schema::hasColumn('pemesanans', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('pemesanans', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            if (Schema::hasColumn('pemesanans', 'longitude')) {
                $table->dropColumn('longitude');
            }
            if (Schema::hasColumn('pemesanans', 'latitude')) {
                $table->dropColumn('latitude');
            }
        });
    }
};
