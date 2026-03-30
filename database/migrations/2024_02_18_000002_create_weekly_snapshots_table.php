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
        Schema::create('weekly_snapshots', function (Blueprint $table) {
            $table->id();
            $table->date('snapshot_date'); // Tanggal Jumat
            $table->string('week_label'); // e.g., "Week 1 - Feb 2025"
            $table->integer('total_students');
            $table->json('data_summary'); // Store rekap per program, status, etc
            $table->string('uploaded_by')->nullable();
            $table->string('file_name')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('snapshot_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_snapshots');
    }
};
