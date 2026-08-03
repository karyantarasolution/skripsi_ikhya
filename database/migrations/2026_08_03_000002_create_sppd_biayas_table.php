<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sppd_biayas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sppd_id')->constrained('surat_perjalanan_dinas')->onDelete('cascade');
            $table->string('uraian');
            $table->integer('volume')->default(1);
            $table->string('satuan')->nullable();
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sppd_biayas');
    }
};
