<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_perjalanan_dinas', function (Blueprint $table) {
            $table->enum('ttd_status', ['belum', 'diajukan_ttd', 'ditandatangani'])->default('belum')->after('status');
            $table->foreignId('ttd_by')->nullable()->constrained('users')->after('ttd_status');
            $table->timestamp('ttd_at')->nullable()->after('ttd_by');
            $table->string('qr_code_path')->nullable()->after('ttd_at');
            $table->string('hash_sha256', 64)->nullable()->after('qr_code_path');
        });
    }

    public function down(): void
    {
        Schema::table('surat_perjalanan_dinas', function (Blueprint $table) {
            $table->dropColumn(['ttd_status', 'ttd_by', 'ttd_at', 'qr_code_path', 'hash_sha256']);
        });
    }
};
