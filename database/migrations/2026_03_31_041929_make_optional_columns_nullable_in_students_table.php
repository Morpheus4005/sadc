<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Make these columns nullable for new format compatibility
            $table->string('evaluasi', 50)->nullable()->change();
            $table->integer('sks_kumulatif')->nullable()->change();
            $table->integer('sks_sisa')->nullable()->change();
            $table->string('study_target_10', 100)->nullable()->change();
            $table->string('study_target_14', 100)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('evaluasi', 50)->nullable(false)->change();
            $table->integer('sks_kumulatif')->nullable(false)->change();
            $table->integer('sks_sisa')->nullable(false)->change();
            $table->string('study_target_10', 100)->nullable(false)->change();
            $table->string('study_target_14', 100)->nullable(false)->change();
        });
    }
};