<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $students;

    public function __construct($students)
    {
        $this->students = $students;
    }

    /**
     * Return collection of students
     */
    public function collection()
    {
        return $this->students;
    }

    /**
     * Define headers
     */
    public function headings(): array
    {
        return [
            'Admit Term',
            'Campus',
            'Faculty',
            'Program',
            'Binusian ID',
            'NIM',
            'Binusian',
            'Nama Mahasiswa',
            'Tindak Lanjut SRSC',
            'Evaluasi (On Track/Off Track)',
            'SKS Kumulatif',
            'SKS Sisa',
            'Study Target 10 Semester',
            'Study Target 14 Semester',
            'Prediksi Semester Selesai',
            'No. HP Mahasiswa',
            'Notes SRSC',
            'Status Warna',
        ];
    }

    /**
     * Map data to columns
     */
    public function map($student): array
    {
        return [
            $student->admit_term,
            $student->campus,
            $student->faculty,
            $student->program,
            $student->binusian_id,
            $student->nim,
            $student->binusian,
            $student->name,
            $student->tindak_lanjut,
            $student->evaluasi,
            $student->sks_kumulatif,
            $student->sks_sisa,
            $student->study_target_10,
            $student->study_target_14,
            $student->prediksi_smt_selesai,
            $student->no_hp,
            $student->notes_srsc,
            $student->status_warna,
        ];
    }

    /**
     * Apply styles
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Bold header row
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * Define column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15, // Admit Term
            'B' => 12, // Campus
            'C' => 15, // Faculty
            'D' => 40, // Program
            'E' => 15, // Binusian ID
            'F' => 15, // NIM
            'G' => 12, // Binusian
            'H' => 30, // Nama
            'I' => 35, // Tindak Lanjut
            'J' => 20, // Evaluasi
            'K' => 15, // SKS Kumulatif
            'L' => 12, // SKS Sisa
            'M' => 22, // Study Target 10
            'N' => 22, // Study Target 14
            'O' => 25, // Prediksi Smt Selesai
            'P' => 18, // No. HP
            'Q' => 30, // Notes SRSC
            'R' => 30, // Status Warna
        ];
    }
}
