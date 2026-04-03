<?php

namespace App\Services;

use App\Models\Student;
use App\Helpers\PeriodeHelper;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;

class StudentDataService
{
    /**
     * Import data from Excel - AUTO DETECT FORMAT
     */
    public function importFromExcel($file)
    {
        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheetNames = $spreadsheet->getSheetNames();
            
            Log::info('Sheet names found: ' . implode(', ', $sheetNames));
            
            // Detect format
            if (in_array('Data All', $sheetNames)) {
                // FORMAT LAMA (Follow_Up_Genap_25_2.xlsx)
                Log::info('Detected OLD format (Data All tab)');
                return $this->importOldFormat($spreadsheet);
            } else if (in_array('Sheet1', $sheetNames)) {
                // FORMAT BARU (Follow_Up_Ganjil_26_1.xlsx)
                Log::info('Detected NEW format (Sheet1 tab)');
                return $this->importNewFormat($spreadsheet);
            } else {
                throw new \Exception('Format Excel tidak dikenali. File harus punya tab "Data All" atau "Sheet1".');
            }
            
        } catch (\Exception $e) {
            Log::error('Excel import error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * IMPORT OLD FORMAT (Data All tab - 18 columns)
     */
    private function importOldFormat($spreadsheet)
    {
        try {
            $worksheet = $spreadsheet->getSheetByName('Data All');
            
            if (!$worksheet) {
                throw new \Exception('Sheet "Data All" tidak ditemukan.');
            }
            
            // Unhide all rows
            foreach ($worksheet->getRowIterator() as $row) {
                $worksheet->getRowDimension($row->getRowIndex())->setVisible(true);
            }
            
            // Remove auto-filter
            $worksheet->setAutoFilter('');
            
            $currentPeriode = PeriodeHelper::getCurrentPeriode();
            $highestRow = $worksheet->getHighestRow();
            
            Log::info("Processing OLD format: {$highestRow} rows");
            
            $imported = 0;
            $updated = 0;
            $errors = [];
            
            // Start from row 2 (skip header)
            for ($row = 2; $row <= $highestRow; $row++) {
                try {
                    // OLD FORMAT - 18 columns
                    $admitTerm = $this->cleanValue($worksheet->getCell("A{$row}")->getValue());
                    $campus = $this->cleanValue($worksheet->getCell("B{$row}")->getValue());
                    $faculty = $this->cleanValue($worksheet->getCell("C{$row}")->getValue());
                    $program = $this->cleanValue($worksheet->getCell("D{$row}")->getValue());
                    $binusianId = $this->cleanValue($worksheet->getCell("E{$row}")->getValue());
                    $nim = $this->cleanValue($worksheet->getCell("F{$row}")->getValue());
                    $binusian = $this->cleanValue($worksheet->getCell("G{$row}")->getValue());
                    $name = $this->cleanValue($worksheet->getCell("H{$row}")->getValue());
                    $tindakLanjut = $this->cleanValue($worksheet->getCell("I{$row}")->getValue());
                    $evaluasi = $this->cleanValue($worksheet->getCell("J{$row}")->getValue());
                    $sksKumulatif = $this->cleanValue($worksheet->getCell("K{$row}")->getValue());
                    $sksSisa = $this->cleanValue($worksheet->getCell("L{$row}")->getValue());
                    $studyTarget10 = $this->cleanValue($worksheet->getCell("M{$row}")->getValue());
                    $studyTarget14 = $this->cleanValue($worksheet->getCell("N{$row}")->getValue());
                    $prediksiSmtSelesai = $this->cleanValue($worksheet->getCell("O{$row}")->getValue());
                    $noHp = $this->cleanValue($worksheet->getCell("P{$row}")->getValue());
                    $notesSrsc = $this->cleanValue($worksheet->getCell("Q{$row}")->getValue());
                    $keterangan = $this->cleanValue($worksheet->getCell("R{$row}")->getValue());
                    
                    // Skip empty rows
                    if (empty($nim) || empty($name)) {
                        continue;
                    }
                    
                    // GET CELL COLOR FROM NIM COLUMN (F)
                    $nimCell = $worksheet->getCell("F{$row}");
                    $fill = $nimCell->getStyle()->getFill();
                    $bgColor = $fill->getStartColor()->getRGB();
                    
                    // Map color to status
                    $statusWarna = $this->mapColorToStatus($bgColor);
                    
                    // FALLBACK: Use Tindak Lanjut mapping
                    if (!$statusWarna) {
                        $statusWarna = $this->mapTindakLanjutToStatus($tindakLanjut);
                    }
                    
                    // Check if student exists
                    $existingStudent = Student::withTrashed()
                        ->where('nim', $nim)
                        ->where('periode_akademik', $currentPeriode)
                        ->first();
                    
                    if ($existingStudent) {
                        if ($existingStudent->trashed()) {
                            $existingStudent->restore();
                        }
                        
                        $existingStudent->update([
                            'admit_term' => $admitTerm,
                            'campus' => $campus,
                            'faculty' => $faculty,
                            'program' => $program,
                            'binusian_id' => $binusianId,
                            'binusian' => $binusian,
                            'name' => $name,
                            'tindak_lanjut' => $tindakLanjut,
                            'evaluasi' => $evaluasi,
                            'sks_kumulatif' => $sksKumulatif,
                            'sks_sisa' => $sksSisa,
                            'study_target_10' => $studyTarget10,
                            'study_target_14' => $studyTarget14,
                            'prediksi_smt_selesai' => $prediksiSmtSelesai,
                            'no_hp' => $noHp,
                            'notes_srsc' => $notesSrsc,
                            'status_warna' => $statusWarna,
                            'keterangan' => $keterangan,
                        ]);
                        
                        $updated++;
                    } else {
                        Student::create([
                            'periode_akademik' => $currentPeriode,
                            'admit_term' => $admitTerm,
                            'campus' => $campus,
                            'faculty' => $faculty,
                            'program' => $program,
                            'binusian_id' => $binusianId,
                            'nim' => $nim,
                            'binusian' => $binusian,
                            'name' => $name,
                            'tindak_lanjut' => $tindakLanjut,
                            'evaluasi' => $evaluasi,
                            'sks_kumulatif' => $sksKumulatif,
                            'sks_sisa' => $sksSisa,
                            'study_target_10' => $studyTarget10,
                            'study_target_14' => $studyTarget14,
                            'prediksi_smt_selesai' => $prediksiSmtSelesai,
                            'no_hp' => $noHp,
                            'notes_srsc' => $notesSrsc,
                            'status_warna' => $statusWarna,
                            'keterangan' => $keterangan,
                        ]);
                        
                        $imported++;
                    }
                    
                } catch (\Exception $e) {
                    $errors[] = "Row {$row}: " . $e->getMessage();
                    Log::error("Import error at row {$row}: " . $e->getMessage());
                }
            }
            
            Log::info("OLD format import completed: {$imported} imported, {$updated} updated");
            
            return [
                'success' => true,
                'imported' => $imported,
                'updated' => $updated,
                'errors' => $errors,
                'format' => 'OLD (Data All)',
            ];
            
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    /**
     * IMPORT NEW FORMAT (Sheet1 - 14 columns)
     */
    private function importNewFormat($spreadsheet)
    {
        try {
            $worksheet = $spreadsheet->getSheet(0); // Sheet1
            
            // Unhide all rows
            foreach ($worksheet->getRowIterator() as $row) {
                $worksheet->getRowDimension($row->getRowIndex())->setVisible(true);
            }
            
            // Remove auto-filter
            $worksheet->setAutoFilter('');
            
            $currentPeriode = PeriodeHelper::getCurrentPeriode();
            $highestRow = $worksheet->getHighestRow();
            
            Log::info("Processing NEW format: {$highestRow} rows");
            
            // DEBUG: Check header row
            $headers = [];
            for ($col = 'A'; $col <= 'N'; $col++) {
                $headers[$col] = $worksheet->getCell("{$col}1")->getValue();
            }
            Log::info("Header row: ", $headers);
            
            $imported = 0;
            $updated = 0;
            $errors = [];
            $skipped = 0;
            
            // Start from row 2 (skip header)
            for ($row = 2; $row <= $highestRow; $row++) {
                try {
                    // NEW FORMAT - 14 columns
                    // A: Status
                    // B: Admit Term
                    // C: Campus
                    // D: Program
                    // E: Academic Career
                    // F: Binusian ID
                    // G: NIM
                    // H: Name
                    // I: Binusian
                    // J: Req. Term
                    // K: Status 25.20
                    // L: Leave Start Form
                    // M: No Kontak Mahasiswa
                    // N: No Kontak Orangtua
                    
                    $status = $this->cleanValue($worksheet->getCell("A{$row}")->getValue());
                    $admitTerm = $this->cleanValue($worksheet->getCell("B{$row}")->getValue());
                    $campus = $this->cleanValue($worksheet->getCell("C{$row}")->getValue());
                    $program = $this->cleanValue($worksheet->getCell("D{$row}")->getValue());
                    $academicCareer = $this->cleanValue($worksheet->getCell("E{$row}")->getValue());
                    $binusianId = $this->cleanValue($worksheet->getCell("F{$row}")->getValue());
                    $nim = $this->cleanValue($worksheet->getCell("G{$row}")->getValue());
                    $name = $this->cleanValue($worksheet->getCell("H{$row}")->getValue());
                    $binusian = $this->cleanValue($worksheet->getCell("I{$row}")->getValue());
                    $reqTerm = $this->cleanValue($worksheet->getCell("J{$row}")->getValue());
                    $status2520 = $this->cleanValue($worksheet->getCell("K{$row}")->getValue());
                    $leaveStartForm = $this->cleanValue($worksheet->getCell("L{$row}")->getValue());
                    $noKontakMhs = $this->cleanValue($worksheet->getCell("M{$row}")->getValue());
                    $noKontakOrtu = $this->cleanValue($worksheet->getCell("N{$row}")->getValue());
                    
                    // Skip empty rows
                    if (empty($nim) || empty($name)) {
                        $skipped++;
                        if ($row % 50 === 0) {
                            Log::info("Row {$row}: Skipped (empty NIM or Name)");
                        }
                        continue;
                    }
                    
                    // Map to old format fields
                    $faculty = $this->getFacultyFromCareer($academicCareer);
                    $tindakLanjut = $status; // Use main status as tindak lanjut
                    $statusWarna = $this->mapStatusNewFormat($status, $status2520);
                    
                    // Combine notes
                    $notesSrsc = "Status 25.20: {$status2520}";
                    if ($leaveStartForm) {
                        $notesSrsc .= " | Leave Form: {$leaveStartForm}";
                    }
                    
                    $keterangan = "Req Term: {$reqTerm}";
                    if ($noKontakOrtu) {
                        $keterangan .= " | Kontak Ortu: {$noKontakOrtu}";
                    }
                    
                    // Log every 50 rows
                    if ($row % 50 === 0) {
                        Log::info("Row {$row}: NIM={$nim}, Status={$status}, Status2520={$status2520}, StatusWarna={$statusWarna}");
                    }
                    
                    // Check if student exists
                    $existingStudent = Student::withTrashed()
                        ->where('nim', $nim)
                        ->where('periode_akademik', $currentPeriode)
                        ->first();
                    
                    if ($existingStudent) {
                        if ($existingStudent->trashed()) {
                            $existingStudent->restore();
                        }
                        
                        $existingStudent->update([
                            'admit_term' => $admitTerm,
                            'campus' => $campus,
                            'faculty' => $faculty,
                            'program' => $program,
                            'binusian_id' => $binusianId,
                            'binusian' => $binusian,
                            'name' => $name,
                            'tindak_lanjut' => $tindakLanjut,
                            'evaluasi' => null, // Not in new format
                            'sks_kumulatif' => null,
                            'sks_sisa' => null,
                            'study_target_10' => null,
                            'study_target_14' => null,
                            'prediksi_smt_selesai' => $leaveStartForm,
                            'no_hp' => $noKontakMhs,
                            'notes_srsc' => $notesSrsc,
                            'status_warna' => $statusWarna,
                            'keterangan' => $keterangan,
                        ]);
                        
                        $updated++;
                    } else {
                        Student::create([
                            'periode_akademik' => $currentPeriode,
                            'admit_term' => $admitTerm,
                            'campus' => $campus,
                            'faculty' => $faculty,
                            'program' => $program,
                            'binusian_id' => $binusianId,
                            'nim' => $nim,
                            'binusian' => $binusian,
                            'name' => $name,
                            'tindak_lanjut' => $tindakLanjut,
                            'evaluasi' => null,
                            'sks_kumulatif' => null,
                            'sks_sisa' => null,
                            'study_target_10' => null,
                            'study_target_14' => null,
                            'prediksi_smt_selesai' => $leaveStartForm,
                            'no_hp' => $noKontakMhs,
                            'notes_srsc' => $notesSrsc,
                            'status_warna' => $statusWarna,
                            'keterangan' => $keterangan,
                        ]);
                        
                        $imported++;
                    }
                    
                } catch (\Exception $e) {
                    $errors[] = "Row {$row}: " . $e->getMessage();
                    Log::error("Import error at row {$row}: " . $e->getMessage());
                }
            }
            
            Log::info("NEW format import completed: {$imported} imported, {$updated} updated, {$skipped} skipped");
            
            return [
                'success' => true,
                'imported' => $imported,
                'updated' => $updated,
                'errors' => $errors,
                'format' => 'NEW (Sheet1)',
            ];
            
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    /**
     * Map status from NEW format to status_warna
     */
    private function mapStatusNewFormat($status, $status2520)
    {
        $statusLower = strtolower(trim($status ?? ''));
        $status2520Lower = strtolower(trim($status2520 ?? ''));
        
        Log::info("Mapping status: Status='{$status}', Status2520='{$status2520}'");
        
        // Priority 1: Check Status 25.20
        if (str_contains($status2520Lower, 'active') && !str_contains($status2520Lower, 'not')) {
            return 'Re-active';
        }
        
        if (str_contains($status2520Lower, 'unofficial')) {
            return 'Tidak Terhubung';
        }
        
        // Priority 2: Check main Status
        if (str_contains($statusLower, 'not active')) {
            return 'Tidak Terhubung';
        }
        
        if (str_contains($statusLower, 'active') && !str_contains($statusLower, 'not')) {
            return 'Re-active';
        }
        
        if (str_contains($statusLower, 'leave')) {
            return 'Tidak Terhubung';
        }
        
        if (str_contains($statusLower, 'withdrawn')) {
            return 'Undur Diri';
        }
        
        // Default to empty status for unknown
        Log::warning("Unknown status combination: Status='{$status}', Status2520='{$status2520}' - defaulting to empty");
        return null;
    }
    
    /**
     * Get faculty from academic career code
     */
    private function getFacultyFromCareer($career)
    {
        $career = strtoupper(trim($career ?? ''));
        
        $mapping = [
            'BDS1' => 'School of Design',
            'BCS1' => 'School of Computer Science',
            'BIS1' => 'School of Information Systems',
            'BBM1' => 'Business School',
            'BEC1' => 'Faculty of Economics and Communication',
            'BEG1' => 'Faculty of Engineering',
            'BHM1' => 'Faculty of Humanities',
            'BLA1' => 'Faculty of Languages',
        ];
        
        return $mapping[$career] ?? "Faculty ({$career})";
    }
    
    /**
     * Map cell background color to status (OLD FORMAT)
     * UPDATED: Added ORANGE for "Konfirmasi DO" and NULL for no color
     */
    private function mapColorToStatus($rgbColor)
    {
        $color = strtoupper($rgbColor ?? 'FFFFFF');
        
        // Remove alpha channel if present
        if (strlen($color) === 8) {
            $color = substr($color, 2);
        }
        
        if (strlen($color) < 6) {
            return null;
        }
        
        // Default white/no color = null (empty status)
        if (in_array($color, ['FFFFFF', 'FFFFFFFF', '000000'])) {
            return null; // No status / empty
        }
        
        // EXACT COLOR MATCHING
        $colorMap = [
            // Cyan - Proses Re-active
            '00FFFF' => 'Proses Re-active',
            '00B0F0' => 'Proses Re-active',
            '9DC3E6' => 'Proses Re-active',
            
            // Green - Re-active
            '00FF00' => 'Re-active',
            '00B050' => 'Re-active',
            '92D050' => 'Re-active',
            'C6E0B4' => 'Re-active',
            
            // Purple - Merespon
            'FF00FF' => 'Merespon tapi belum re-active',
            'B4A7D6' => 'Merespon tapi belum re-active',
            'D9D2E9' => 'Merespon tapi belum re-active',
            
            // Cream - Undur Diri
            'FFF2CC' => 'Undur Diri',
            'FFE699' => 'Undur Diri',
            'F4B084' => 'Undur Diri',
            
            // Red - Tidak Terhubung
            'FF0000' => 'Tidak Terhubung',
            'FF6666' => 'Tidak Terhubung',
            'FFC7CE' => 'Tidak Terhubung',
            
            // Yellow - Terhubung Tapi Tidak Merespon
            'FFFF00' => 'Terhubung Tapi Tidak Merespon',
            'FFFF99' => 'Terhubung Tapi Tidak Merespon',
            'FFEB9C' => 'Terhubung Tapi Tidak Merespon',
            
            // ORANGE - Konfirmasi DO (NEW!)
            'FFA500' => 'Konfirmasi DO',
            'FF8C00' => 'Konfirmasi DO',
            'FFA500' => 'Konfirmasi DO',
            'ED7D31' => 'Konfirmasi DO',
            'F4B183' => 'Konfirmasi DO',
            'FFC000' => 'Konfirmasi DO',
        ];
        
        if (isset($colorMap[$color])) {
            return $colorMap[$color];
        }
        
        // FUZZY MATCHING
        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));
        
        // Orange detection (R > G > B)
        if ($r > 200 && $g > 100 && $g < 180 && $b < 100 && $r > $g && $g > $b) {
            return 'Konfirmasi DO';
        }
        
        if ($b > 200 && $g > 200 && $r < 100) {
            return 'Proses Re-active';
        }
        
        if ($g > 180 && $r < 150 && $b < 150) {
            return 'Re-active';
        }
        
        if ($r > 150 && $b > 150 && $g < 200) {
            return 'Merespon tapi belum re-active';
        }
        
        if ($r > 200 && $g > 200 && $b < 150) {
            return 'Terhubung Tapi Tidak Merespon';
        }
        
        if ($r > 200 && $g < 150 && $b < 150) {
            return 'Tidak Terhubung';
        }
        
        if ($r > 230 && $g > 200 && $b > 150 && $r > $g) {
            return 'Undur Diri';
        }
        
        // Unknown color = return null (empty status)
        return null;
    }
    
    /**
     * Fallback: Map Tindak Lanjut to Status
     */
    private function mapTindakLanjutToStatus($tindakLanjut)
    {
        $mapping = [
            'Data Pengajuan Reactive 25.2' => 'Proses Re-active',
            'Dalam Proses Reactive' => 'Proses Re-active',
            'Sudah Terdata Aktif 25.2' => 'Re-active',
            'Merespon' => 'Merespon tapi belum re-active',
            'Mengajukan Undur Diri/DO' => 'Undur Diri',
            'Konfirmasi DO' => 'Konfirmasi DO', // NEW!
            'Belum Terhubung' => 'Tidak Terhubung',
            'Terhubung Tapi Tidak Merespon' => 'Terhubung Tapi Tidak Merespon',
            'Unofficial Leave' => 'Tidak Terhubung',
        ];
        
        if (isset($mapping[$tindakLanjut])) {
            return $mapping[$tindakLanjut];
        }
        
        // Fuzzy matching
        $lower = strtolower($tindakLanjut ?? '');
        
        if (str_contains($lower, 'konfirmasi do')) {
            return 'Konfirmasi DO';
        }
        
        if (str_contains($lower, 'reactive')) {
            if (str_contains($lower, 'proses') || str_contains($lower, 'pengajuan')) {
                return 'Proses Re-active';
            } else if (str_contains($lower, 'aktif')) {
                return 'Re-active';
            }
            return 'Proses Re-active';
        }
        
        if (str_contains($lower, 'merespon')) {
            return 'Merespon tapi belum re-active';
        }
        
        if (str_contains($lower, 'undur') || str_contains($lower, 'do')) {
            return 'Undur Diri';
        }
        
        return null; // Empty status for unknown
    }
    
    /**
     * Clean cell value
     */
    private function cleanValue($value)
    {
        if ($value === null || $value === '' || $value === '#N/A') {
            return null;
        }
        
        // Handle formula values - keep the result
        if (is_string($value) && str_starts_with($value, '=')) {
            // For formulas, PhpSpreadsheet usually returns calculated value
            // If it returns the formula string, we skip it
            return null;
        }
        
        return trim($value);
    }
    
    /**
     * Compare data with previous week
     */
    public function compareWithPreviousWeek()
    {
        $currentPeriode = PeriodeHelper::getCurrentPeriode();
        
        $latestSnapshot = \App\Models\WeeklySnapshot::where('periode_akademik', $currentPeriode)
            ->orderBy('created_at', 'desc')
            ->first();
        
        $previousSnapshot = \App\Models\WeeklySnapshot::where('periode_akademik', $currentPeriode)
            ->where('created_at', '<', $latestSnapshot?->created_at ?? now())
            ->orderBy('created_at', 'desc')
            ->first();
        
        if (!$latestSnapshot || !$previousSnapshot) {
            return [
                'total_change' => 0,
                'total_percentage' => 0,
                'changes_by_status' => [],
            ];
        }
        
        $latestData = json_decode($latestSnapshot->data_snapshot, true);
        $previousData = json_decode($previousSnapshot->data_snapshot, true);
        
        $totalChange = ($latestData['total_students'] ?? 0) - ($previousData['total_students'] ?? 0);
        $totalPercentage = $previousData['total_students'] > 0 
            ? round(($totalChange / $previousData['total_students']) * 100, 1) 
            : 0;
        
        return [
            'total_change' => $totalChange,
            'total_percentage' => $totalPercentage,
            'latest_snapshot' => $latestSnapshot,
            'previous_snapshot' => $previousSnapshot,
        ];
    }
    
    /**
     * Get weekly trend
     */
    public function getWeeklyTrend($weeks = 8)
    {
        $currentPeriode = PeriodeHelper::getCurrentPeriode();
        
        $snapshots = \App\Models\WeeklySnapshot::where('periode_akademik', $currentPeriode)
            ->orderBy('created_at', 'desc')
            ->limit($weeks)
            ->get()
            ->reverse();
        
        $labels = [];
        $data = [];
        
        foreach ($snapshots as $snapshot) {
            $labels[] = $snapshot->created_at->format('d M');
            $snapshotData = json_decode($snapshot->data_snapshot, true);
            $data[] = $snapshotData['total_students'] ?? 0;
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
    
    /**
     * Get stats by program
     */
    public function getStatsByProgram()
    {
        $currentPeriode = PeriodeHelper::getCurrentPeriode();
        
        return Student::where('periode_akademik', $currentPeriode)
            ->select('program', \DB::raw('count(*) as total'))
            ->groupBy('program')
            ->orderBy('total', 'desc')
            ->get();
    }
    
    /**
     * Get stats by status
     */
    public function getStatsByStatus()
    {
        $currentPeriode = PeriodeHelper::getCurrentPeriode();
        
        return Student::where('periode_akademik', $currentPeriode)
            ->select('status_warna', \DB::raw('count(*) as total'))
            ->groupBy('status_warna')
            ->orderBy('total', 'desc')
            ->get();
    }
}