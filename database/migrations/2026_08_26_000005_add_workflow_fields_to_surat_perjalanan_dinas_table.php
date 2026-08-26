<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_perjalanan_dinas', function (Blueprint $table) {
            $table->foreignId('kabag_reviewed_by')->nullable()->constrained('users')->after('catatan');
            $table->timestamp('kabag_reviewed_at')->nullable()->after('kabag_reviewed_by');
            $table->text('kabag_catatan')->nullable()->after('kabag_reviewed_at');
        });
    }

    public function down(): void
    {
        Schema::table('surat_perjalanan_dinas', function (Blueprint $table) {
            $table->dropColumn(['kabag_reviewed_by', 'kabag_reviewed_at', 'kabag_catatan']);
        });
    }
};
