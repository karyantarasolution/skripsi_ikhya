<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lpj_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('penugasan_id')->constrained('penugasan_liputans')->onDelete('cascade');
            $table->string('no_lpj')->nullable();
            $table->text('uraian_hasil')->nullable();
            $table->string('penanggung_jawab_nama')->nullable();
            $table->string('penanggung_jawab_jabatan')->nullable();
            $table->date('tanggal_lpj')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lpj_tugas');
    }
};
