<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lpj_tugas', function (Blueprint $table) {
            $table->dropForeign(['penugasan_id']);
            $table->unsignedBigInteger('penugasan_id')->nullable()->change();
            $table->foreignId('kegiatan_id')->nullable()->after('penugasan_id')->constrained('kegiatans')->nullOnDelete();
            $table->foreign('penugasan_id')->references('id')->on('penugasan_liputans')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('lpj_tugas', function (Blueprint $table) {
            $table->dropForeign(['penugasan_id']);
            $table->dropForeign(['kegiatan_id']);
            $table->dropColumn('kegiatan_id');
            $table->unsignedBigInteger('penugasan_id')->nullable(false)->change();
            $table->foreign('penugasan_id')->references('id')->on('penugasan_liputans')->onDelete('cascade');
        });
    }
};
