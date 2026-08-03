<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_perjalanan_dinas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kegiatan_id')->nullable()->constrained('kegiatans')->nullOnDelete();
            $table->string('no_surat')->nullable();
            $table->string('tujuan');
            $table->string('kota_tujuan');
            $table->date('tanggal_berangkat');
            $table->date('tanggal_kembali');
            $table->string('kendaraan');
            $table->string('pembebanan');
            $table->text('keterangan')->nullable();
            $table->enum('status', ['draf', 'diajukan', 'disetujui', 'ditolak'])->default('draf');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_perjalanan_dinas');
    }
};
