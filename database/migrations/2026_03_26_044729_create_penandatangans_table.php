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
    Schema::create('penandatangans', function (Blueprint $table) {
        $table->id();
        $table->string('nama_pejabat');
        $table->string('nip')->unique();
        $table->string('jabatan');
        $table->string('qr_code_path')->nullable(); 
        $table->boolean('is_aktif')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penandatangans');
    }
};
