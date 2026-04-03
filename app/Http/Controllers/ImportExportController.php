<?php

namespace App\Http\Controllers;

use App\Services\StudentDataService;
use App\Models\Student;
use App\Helpers\PeriodeHelper;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;

class ImportExportController extends Controller
{
    protected $studentService;

    public function __construct(StudentDataService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * Show import form
     */
    public function index()
    {
        return view('import.index');
    }

    /**
     * Show import form
     */
    public function import()
    {
        $currentPeriode = PeriodeHelper::getCurrentPeriode();
        
        return view('import-export.import', compact('currentPeriode'));
    }

    /**
     * Process import
     */
    public function processImport(Request $request)
    {
        // DEBUG: Log semua input
        \Log::info('=== IMPORT DEBUG ===');
        \Log::info('All Input: ', $request->all());
        \Log::info('Has File: ' . ($request->hasFile('excel_file') ? 'YES' : 'NO'));
        
        if ($request->hasFile('excel_file')) {
            $file = $request->file('excel_file');
            \Log::info('File Name: ' . $file->getClientOriginalName());
            \Log::info('File Size: ' . $file->getSize());
        }
        
        // Validation - GANTI "file" jadi "excel_file"
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls|max:10240',
        ], [
            'excel_file.required' => 'File Excel wajib diupload',
            'excel_file.mimes' => 'File harus berformat .xlsx atau .xls',
            'excel_file.max' => 'Ukuran file maksimal 10MB',
        ]);

        try {
            $file = $request->file('excel_file'); // GANTI ini juga
            $currentPeriode = \App\Helpers\PeriodeHelper::getCurrentPeriode();
            
            \Log::info('Starting import for periode: ' . $currentPeriode);
            
            // Import
            $result = $this->studentService->importFromExcel($file);
            
            \Log::info('Import result: ', $result);
            
            if (!$result['success']) {
                return redirect()->back()
                    ->with('error', 'Import gagal: ' . $result['message']);
            }
            
            $message = "Import berhasil! ";
            $message .= "Ditambahkan: {$result['imported']}, ";
            $message .= "Diupdate: {$result['updated']} ";
            $message .= "(Periode: {$currentPeriode})";
            
            if (isset($result['format'])) {
                $message .= " | Format: {$result['format']}";
            }
            
            return redirect()->route('students.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            \Log::error('Import exception: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export to Excel
     */
    public function export()
    {
        $currentPeriode = PeriodeHelper::getCurrentPeriode();
        
        // Get only students from current periode
        $students = Student::where('periode_akademik', $currentPeriode)
            ->orderBy('program')
            ->orderBy('name')
            ->get();

        if ($students->count() === 0) {
            return redirect()->back()
                ->with('error', "Tidak ada data untuk di-export di periode {$currentPeriode}");
        }

        $filename = 'Data_Mahasiswa_' . str_replace(['/', ' '], '_', $currentPeriode) . '_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new StudentsExport($students), $filename);
    }

    /**
     * Download template
     */
    public function downloadTemplate()
    {
        $currentPeriode = PeriodeHelper::getCurrentPeriode();
        
        // Create sample data
        $sampleData = collect([
            (object)[
                'admit_term' => '2410',
                'campus' => 'KMG',
                'faculty' => 'School of Computer Science',
                'program' => 'Computer Science',
                'binusian_id' => '2024123456',
                'nim' => '2502123456',
                'binusian' => '2025',
                'name' => 'Contoh Mahasiswa',
                'tindak_lanjut' => 'Merespon',
                'evaluasi' => 'Off Track',
                'sks_kumulatif' => 60,
                'sks_sisa' => 84,
                'study_target_10' => '2029/2030 Semester Ganjil',
                'study_target_14' => '2031/2032 Semester Ganjil',
                'prediksi_smt_selesai' => '2030/2031 Semester Genap',
                'no_hp' => '628123456789',
                'notes_srsc' => 'Catatan follow up',
                'status_warna' => 'Merespon tapi belum re-active',
            ]
        ]);
        
        $filename = 'Template_Import_Mahasiswa_' . str_replace(['/', ' '], '_', $currentPeriode) . '.xlsx';
        
        return Excel::download(new StudentsExport($sampleData), $filename);
    }
}