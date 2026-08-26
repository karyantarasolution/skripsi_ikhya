<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokumentasis', function (Blueprint $table) {
            $table->timestamp('foto_taken_at')->nullable()->after('tipe_file');
            $table->decimal('gps_latitude', 10, 7)->nullable()->after('foto_taken_at');
            $table->decimal('gps_longitude', 10, 7)->nullable()->after('gps_latitude');
            $table->string('gps_location_name')->nullable()->after('gps_longitude');
        });
    }

    public function down(): void
    {
        Schema::table('dokumentasis', function (Blueprint $table) {
            $table->dropColumn(['foto_taken_at', 'gps_latitude', 'gps_longitude', 'gps_location_name']);
        });
    }
};
