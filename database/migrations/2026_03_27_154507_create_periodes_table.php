<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periodes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_periode', 50)->unique();
            $table->string('semester', 20); // Ganjil/Genap
            $table->string('tahun_ajaran', 20); // 2025/2026
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
        
        // Insert default periodes
        DB::table('periodes')->insert([
            [
                'nama_periode' => 'Ganjil 2025/2026',
                'semester' => 'Ganjil',
                'tahun_ajaran' => '2025/2026',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_periode' => 'Genap 2025/2026',
                'semester' => 'Genap',
                'tahun_ajaran' => '2025/2026',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('periodes');
    }
};