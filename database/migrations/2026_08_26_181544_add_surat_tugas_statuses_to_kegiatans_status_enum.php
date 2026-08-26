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
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->enum('status', [
                'draf', 'diajukan', 'disetujui', 'ditolak', 'pelaksanaan', 'selesai', 'lpj',
                'diajukan_st', 'review_kabag_st', 'ditolak_st',
            ])->default('draf')->change();
        });
    }

    public function down(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->enum('status', [
                'draf', 'diajukan', 'disetujui', 'ditolak', 'pelaksanaan', 'selesai', 'lpj',
            ])->default('draf')->change();
        });
    }
};
