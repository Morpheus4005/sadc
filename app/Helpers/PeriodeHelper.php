<?php

namespace App\Helpers;

use App\Models\Periode;

class PeriodeHelper
{
    /**
     * Get current active periode from session
     */
    public static function getCurrentPeriode()
    {
        return session('current_periode', 'Ganjil 2025/2026');
    }

    /**
     * Set current active periode
     */
    public static function setCurrentPeriode($periode)
    {
        session(['current_periode' => $periode]);
    }

    /**
     * Get all available periodes (from database or default)
     */
    public static function getAllPeriodes()
    {
        // Try to get from database if Periode model exists
        if (class_exists('App\Models\Periode')) {
            $periodes = Periode::orderBy('tahun_ajaran', 'desc')
                ->orderBy('semester', 'desc')
                ->pluck('nama_periode')
                ->toArray();
            
            if (count($periodes) > 0) {
                return $periodes;
            }
        }
        
        // Fallback to default periodes
        return [
            'Ganjil 2025/2026',
            'Genap 2025/2026',
            'Ganjil 2026/2027',
            'Genap 2026/2027',
        ];
    }
}