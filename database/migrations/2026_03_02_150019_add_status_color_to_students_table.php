<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('status_warna')->nullable()->after('notes_srsc')
                ->comment('Status dengan warna: Proses Re-active, Re-active, Merespon tapi belum re-active, Undur Diri, Tidak Terhubung, Terhubung Tapi Tidak Merespon');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('status_warna');
        });
    }
};