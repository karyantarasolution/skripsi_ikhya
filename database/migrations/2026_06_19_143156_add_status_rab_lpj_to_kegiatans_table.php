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
            $table->enum('status', ['draf', 'diajukan', 'disetujui', 'ditolak', 'pelaksanaan', 'selesai', 'lpj'])->default('draf')->after('deskripsi');
            $table->string('rab_file')->nullable()->after('status');
            $table->string('lpj_file')->nullable()->after('rab_file');
            $table->text('catatan_penolakan')->nullable()->after('lpj_file');
            $table->foreignId('approved_by')->nullable()->constrained('users')->after('catatan_penolakan');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });
    }

    public function down(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropColumn(['status', 'rab_file', 'lpj_file', 'catatan_penolakan', 'approved_by', 'approved_at']);
        });
    }
};
