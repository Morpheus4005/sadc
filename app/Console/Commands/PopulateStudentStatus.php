<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;

class PopulateStudentStatus extends Command
{
    protected $signature = 'students:populate-status';
    protected $description = 'Populate status_warna for existing students based on tindak_lanjut';

    public function handle()
    {
        $this->info('🔄 Starting to populate student status...');
        
        // Get students without status
        $students = Student::whereNull('status_warna')
            ->orWhere('status_warna', '')
            ->get();
        
        if ($students->count() === 0) {
            $this->info('✅ All students already have status!');
            return Command::SUCCESS;
        }
        
        $mapping = [
            'Data Pengajuan Reactive 25.2' => 'Proses Re-active',
            'Dalam Proses Reactive' => 'Proses Re-active',
            'Sudah Terdata Aktif 25.2' => 'Re-active',
            'Merespon' => 'Merespon tapi belum re-active',
            'Mengajukan Undur Diri/DO' => 'Undur Diri',
            'Belum Terhubung' => 'Tidak Terhubung',
            'Terhubung Tapi Tidak Merespon' => 'Terhubung Tapi Tidak Merespon',
            'Unofficial Leave' => 'Tidak Terhubung',
            'Mengajukan Pindah Jurusan' => 'Merespon tapi belum re-active',
            'Tidak Realistis 7 Tahun' => 'Tidak Terhubung',
        ];
        
        $updated = 0;
        $bar = $this->output->createProgressBar($students->count());
        $bar->start();
        
        foreach ($students as $student) {
            // Try exact match first
            if (isset($mapping[$student->tindak_lanjut])) {
                $status = $mapping[$student->tindak_lanjut];
            } else {
                // Fuzzy match
                $tindakLower = strtolower($student->tindak_lanjut);
                
                if (str_contains($tindakLower, 'reactive') || str_contains($tindakLower, 're-active')) {
                    if (str_contains($tindakLower, 'proses') || str_contains($tindakLower, 'pengajuan')) {
                        $status = 'Proses Re-active';
                    } else if (str_contains($tindakLower, 'aktif') || str_contains($tindakLower, 'terdata')) {
                        $status = 'Re-active';
                    } else {
                        $status = 'Proses Re-active';
                    }
                } else if (str_contains($tindakLower, 'merespon')) {
                    $status = 'Merespon tapi belum re-active';
                } else if (str_contains($tindakLower, 'undur') || str_contains($tindakLower, 'do')) {
                    $status = 'Undur Diri';
                } else if (str_contains($tindakLower, 'belum terhubung')) {
                    $status = 'Tidak Terhubung';
                } else if (str_contains($tindakLower, 'terhubung') && str_contains($tindakLower, 'tidak merespon')) {
                    $status = 'Terhubung Tapi Tidak Merespon';
                } else {
                    $status = 'Tidak Terhubung';
                }
            }
            
            $student->status_warna = $status;
            $student->save();
            $updated++;
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        $this->info("✅ Successfully updated {$updated} students!");
        
        // Show summary
        $this->newLine();
        $this->table(
            ['Status', 'Count'],
            Student::select('status_warna', \DB::raw('count(*) as total'))
                ->whereNotNull('status_warna')
                ->groupBy('status_warna')
                ->get()
                ->map(fn($item) => [$item->status_warna, $item->total])
        );
        
        return Command::SUCCESS;
    }
}