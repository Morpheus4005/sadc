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

    public function processImport(Request $request)
    {
        // RELAXED VALIDATION - accept more MIME types
        $request->validate([
            'excel_file' => [
                'required',
                'file',
                'max:10240', // 10MB
                'mimes:xlsx,xls',
                // Accept multiple MIME types for Excel files
                function ($attribute, $value, $fail) {
                    $allowedMimes = [
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
                        'application/vnd.ms-excel', // .xls
                        'application/octet-stream', // Sometimes Excel files are detected as this
                        'application/zip', // .xlsx is actually a ZIP file
                    ];
                    
                    $fileMime = $value->getMimeType();
                    $extension = strtolower($value->getClientOriginalExtension());
                    
                    // Accept if extension is correct OR MIME type is in allowed list
                    if (!in_array($extension, ['xlsx', 'xls']) && !in_array($fileMime, $allowedMimes)) {
                        $fail("File harus berformat Excel (.xlsx atau .xls). Detected MIME: {$fileMime}");
                    }
                }
            ]
        ]);
        
        try {
            $file = $request->file('excel_file');
            
            // Log for debugging
            Log::info('Import file received:', [
                'original_name' => $file->getClientOriginalName(),
                'extension' => $file->getClientOriginalExtension(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
            
            // Create temp directory if not exists
            $tempDir = storage_path('app/imports');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            // Save to temp location
            $tempPath = $tempDir . '/' . time() . '_' . $file->getClientOriginalName();
            $file->move($tempDir, basename($tempPath));
            
            // Import using service
            $studentService = new \App\Services\StudentDataService();
            $result = $studentService->importFromExcel($tempPath);
            
            // Delete temp file
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
            
            // Return with success message
            $message = "Import berhasil! Ditambahkan: {$result['added']}, Diupdate: {$result['updated']}";
            $message .= " (Periode: {$result['periode']}) | Format: {$result['format']}";
            
            return redirect()->route('import')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Import gagal: ' . $e->getMessage());
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