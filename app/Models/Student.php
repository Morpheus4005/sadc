<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

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
        'keterangan',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Get CSS class for status badge
     * UPDATED: Added Konfirmasi DO (orange) and empty status (gray)
     */
    public function getStatusColorClass()
    {
        // Handle null/empty status
        if (empty($this->status_warna)) {
            return 'bg-gray-100 text-gray-700'; // Gray for no status
        }
        
        $statusMap = [
            'Proses Re-active' => 'bg-cyan-100 text-cyan-700',
            'Re-active' => 'bg-green-100 text-green-700',
            'Merespon tapi belum re-active' => 'bg-purple-100 text-purple-700',
            'Undur Diri' => 'bg-amber-100 text-amber-700',
            'Tidak Terhubung' => 'bg-red-100 text-red-700',
            'Terhubung Tapi Tidak Merespon' => 'bg-yellow-100 text-yellow-700',
            'Konfirmasi DO' => 'bg-orange-100 text-orange-700', // NEW: Orange
        ];

        return $statusMap[$this->status_warna] ?? 'bg-gray-100 text-gray-700';
    }

    /**
     * Get display text for status (handle empty status)
     */
    public function getStatusDisplayText()
    {
        return $this->status_warna ?: '(Tidak Ada Status)';
    }
}