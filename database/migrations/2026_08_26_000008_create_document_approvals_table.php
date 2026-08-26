<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_approvals', function (Blueprint $table) {
            $table->id();
            $table->string('dokumen_type');
            $table->unsignedBigInteger('dokumen_id');
            $table->enum('tahapan', ['draft', 'review_kabag', 'ttd_karo', 'selesai']);
            $table->enum('aksi', ['submit', 'approve', 'reject', 'return', 'sign']);
            $table->foreignId('user_id')->constrained('users');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['dokumen_type', 'dokumen_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_approvals');
    }
};
