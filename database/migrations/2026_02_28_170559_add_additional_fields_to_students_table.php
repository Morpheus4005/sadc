<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('prediksi_smt_selesai')->nullable()->after('study_target_14');
            $table->string('no_hp')->nullable()->after('prediksi_smt_selesai');
            $table->text('notes_srsc')->nullable()->after('no_hp');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['prediksi_smt_selesai', 'no_hp', 'notes_srsc']);
        });
    }
};