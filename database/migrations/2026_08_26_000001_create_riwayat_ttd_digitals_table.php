<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_ttd_digitals', function (Blueprint $table) {
            $table->id();
            $table->string('dokumen_type');
            $table->unsignedBigInteger('dokumen_id');
            $table->foreignId('penandatangan_id')->constrained('penandatangans');
            $table->string('nomor_dokumen')->nullable();
            $table->string('hash_sha256', 64);
            $table->string('qr_code_path')->nullable();
            $table->foreignId('disahkan_by')->constrained('users');
            $table->timestamp('disahkan_at');
            $table->timestamp('pin_verified_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->index(['dokumen_type', 'dokumen_id']);
            $table->unique('hash_sha256');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_ttd_digitals');
    }
};
