<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lpj_tugas_buktis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lpj_tugas_id')->constrained('lpj_tugas')->onDelete('cascade');
            $table->string('file');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lpj_tugas_buktis');
    }
};
