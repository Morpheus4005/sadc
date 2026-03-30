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
        $currentPeriode = PeriodeHelper::getCurrentPeriode();
        
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls|max:10240',
        ]);

        try {
            $file = $request->file('excel_file');
            $replaceExisting = $request->has('replace_existing');
            
            // Import using StudentDataService with current periode
            $result = $this->studentService->importFromExcel($file, $replaceExisting, $currentPeriode);

            if ($result['success']) {
                $message = "" . ($result['message'] ?? 'Import berhasil!');
                $message .= " Ditambahkan: {$result['imported']}, Diupdate: {$result['updated']}";
                $message .= " (Periode: {$currentPeriode})";
                
                if (!empty($result['errors'])) {
                    return redirect()->route('dashboard')
                        ->with('success', $message)
                        ->with('import_errors', $result['errors']);
                }

                return redirect()->route('dashboard')->with('success', $message);
            } else {
                return redirect()->back()
                    ->with('error', $result['message'] ?? 'Import gagal')
                    ->with('import_errors', $result['errors'] ?? []);
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error: ' . $e->getMessage());
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