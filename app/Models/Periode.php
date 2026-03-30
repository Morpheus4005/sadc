<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $fillable = [
        'nama_periode',
        'semester',
        'tahun_ajaran',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get student count for this periode
     */
    public function getStudentCountAttribute()
    {
        return Student::where('periode_akademik', $this->nama_periode)->count();
    }

    /**
     * Scope for active periode
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}