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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('admit_term');
            $table->string('campus');
            $table->string('faculty');
            $table->string('program');
            $table->string('binusian_id')->unique();
            $table->string('nim')->unique();
            $table->string('binusian');
            $table->string('name');
            $table->string('tindak_lanjut');
            $table->string('evaluasi');
            $table->string('sks_kumulatif')->nullable();
            $table->string('sks_sisa')->nullable();
            $table->string('study_target_10')->nullable();
            $table->string('study_target_14')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better query performance
            $table->index('program');
            $table->index('tindak_lanjut');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
