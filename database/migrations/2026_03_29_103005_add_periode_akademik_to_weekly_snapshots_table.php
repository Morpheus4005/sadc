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
        Schema::table('weekly_snapshots', function (Blueprint $table) {
            $table->string('periode_akademik', 50)->default('Ganjil 2025/2026')->after('id');
            $table->index('periode_akademik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weekly_snapshots', function (Blueprint $table) {
            $table->dropIndex(['periode_akademik']);
            $table->dropColumn('periode_akademik');
        });
    }
};