<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lpj_tugas', function (Blueprint $table) {
            $table->string('status')->default('draf')->after('tanggal_lpj');
            $table->foreignId('kabag_reviewed_by')->nullable()->constrained('users')->after('status');
            $table->timestamp('kabag_reviewed_at')->nullable()->after('kabag_reviewed_by');
            $table->text('kabag_catatan')->nullable()->after('kabag_reviewed_at');
        });
    }

    public function down(): void
    {
        Schema::table('lpj_tugas', function (Blueprint $table) {
            $table->dropColumn(['status', 'kabag_reviewed_by', 'kabag_reviewed_at', 'kabag_catatan']);
        });
    }
};
