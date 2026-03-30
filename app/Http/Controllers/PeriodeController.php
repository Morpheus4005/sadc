<?php

namespace App\Http\Controllers;

use App\Helpers\PeriodeHelper;
use App\Models\Student;
use App\Models\Periode;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    /**
     * Show periode management page
     */
    public function index()
    {
        $currentPeriode = PeriodeHelper::getCurrentPeriode();
        
        // Get all periodes from database
        $periodes = Periode::orderBy('tahun_ajaran', 'desc')
            ->orderBy('semester', 'desc')
            ->get();
        
        // Get student count per periode
        $periodeCounts = [];
        foreach ($periodes as $periode) {
            $periodeCounts[$periode->nama_periode] = Student::where('periode_akademik', $periode->nama_periode)->count();
        }
        
        // Pass $periodes as $allPeriodes for view compatibility
        return view('periode.index', [
            'currentPeriode' => $currentPeriode,
            'periodes' => $periodes,
            'allPeriodes' => $periodes,  // ← TAMBAH INI
            'periodeCounts' => $periodeCounts,
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('periode.create');
    }

    /**
     * Store new periode
     */
    public function store(Request $request)
    {
        $request->validate([
            'semester' => 'required|in:Ganjil,Genap',
            'tahun_ajaran' => 'required|string',
        ]);

        $namaPeriode = $request->semester . ' ' . $request->tahun_ajaran;

        // Check if exists
        if (Periode::where('nama_periode', $namaPeriode)->exists()) {
            return redirect()->back()
                ->with('error', "Periode {$namaPeriode} sudah ada!");
        }

        Periode::create([
            'nama_periode' => $namaPeriode,
            'semester' => $request->semester,
            'tahun_ajaran' => $request->tahun_ajaran,
            'keterangan' => $request->keterangan,
            'is_active' => false,
        ]);

        return redirect()->route('periode.index')
            ->with('success', "✅ Periode {$namaPeriode} berhasil ditambahkan!");
    }

    /**
     * Switch active periode
     */
    public function switch(Request $request)
    {
        $request->validate([
            'periode' => 'required|string'
        ]);
        
        PeriodeHelper::setCurrentPeriode($request->periode);
        
        return redirect()->route('dashboard')
            ->with('success', "✅ Beralih ke periode: {$request->periode}");
    }

    /**
     * Delete periode
     */
    public function destroy(Periode $periode)
    {
        $studentCount = Student::where('periode_akademik', $periode->nama_periode)->count();
        
        if ($studentCount > 0) {
            return redirect()->back()
                ->with('error', "Tidak bisa hapus periode {$periode->nama_periode} karena masih ada {$studentCount} mahasiswa!");
        }

        $periode->delete();

        return redirect()->route('periode.index')
            ->with('success', "Periode {$periode->nama_periode} berhasil dihapus.");
    }
}