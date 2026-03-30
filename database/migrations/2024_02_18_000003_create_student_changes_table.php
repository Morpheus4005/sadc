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
        Schema::create('student_changes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreignId('snapshot_id')->constrained('weekly_snapshots')->onDelete('cascade');
            $table->string('change_type'); // 'new', 'updated', 'deleted', 'status_change'
            $table->string('field_changed')->nullable(); // e.g., 'tindak_lanjut'
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->timestamps();
            
            $table->index(['student_id', 'snapshot_id']);
            $table->index('change_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_changes');
    }
};
