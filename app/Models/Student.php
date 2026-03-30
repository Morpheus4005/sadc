<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'periode_akademik',
        'admit_term',
        'campus',
        'faculty',
        'program',
        'binusian_id',
        'nim',
        'binusian',
        'name',
        'tindak_lanjut',
        'evaluasi',
        'sks_kumulatif',
        'sks_sisa',
        'study_target_10',
        'study_target_14',
        'prediksi_smt_selesai',  
        'no_hp',                  
        'notes_srsc',
        'status_warna',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get all changes for this student
     */
    public function changes()
    {
        return $this->hasMany(StudentChange::class);
    }

    /**
     * Scope to filter by program
     */
    public function scopeByProgram($query, $program)
    {
        return $query->where('program', $program);
    }

    /**
     * Scope to filter by tindak lanjut status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('tindak_lanjut', $status);
    }

    /**
     * Get recap summary by program
     */
    public static function getRecapByProgram()
    {
        return self::selectRaw('program, COUNT(*) as count')
            ->groupBy('program')
            ->orderBy('count', 'desc')
            ->get();
    }

    /**
     * Get recap summary by tindak lanjut status
     */
    public static function getRecapByStatus()
    {
        return self::selectRaw('tindak_lanjut, COUNT(*) as count')
            ->groupBy('tindak_lanjut')
            ->orderBy('count', 'desc')
            ->get();
    }

    /**
     * Get recap summary by evaluasi
     */
    public static function getRecapByEvaluasi()
    {
        return self::selectRaw('evaluasi, COUNT(*) as count')
            ->groupBy('evaluasi')
            ->get();
    }

    /**
     * Search students by multiple criteria
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('nim', 'like', "%{$search}%")
              ->orWhere('binusian_id', 'like', "%{$search}%");
        });
    }

    /**
     * Get status color class
     */
    public function getStatusColorClass()
    {
        $colors = [
            'Proses Re-active' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
            'Re-active' => 'bg-green-100 text-green-800 border-green-300',
            'Merespon tapi belum re-active' => 'bg-purple-100 text-purple-800 border-purple-300',
            'Undur Diri' => 'bg-amber-50 text-amber-800 border-amber-200',  // ← KRIM/BEIGE
            'Tidak Terhubung' => 'bg-red-100 text-red-800 border-red-300',
            'Terhubung Tapi Tidak Merespon' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
        ];
        
        return $colors[$this->status_warna] ?? 'bg-gray-100 text-gray-800 border-gray-300';
    }

    /**
 * Scope untuk filter by periode
 */
    public function scopePeriode($query, $periode)
    {
        return $query->where('periode_akademik', $periode);
    }

    /**
     * Get list of available periodes
     */
    public static function getAvailablePeriodes()
    {
        return self::select('periode_akademik')
            ->distinct()
            ->orderBy('periode_akademik', 'desc')
            ->pluck('periode_akademik');
    }
}