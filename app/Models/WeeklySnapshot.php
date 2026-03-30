<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WeeklySnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'periode_akademik',
        'snapshot_date',
        'week_label',
        'total_students',
        'data_summary',
        'uploaded_by',
        'file_name',
        'notes',
    ];

    protected $casts = [
        'snapshot_date' => 'date',
        'data_summary' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get student changes for this snapshot
     */
    public function studentChanges()
    {
        return $this->hasMany(StudentChange::class, 'snapshot_id');
    }

    /**
     * Get the previous week's snapshot
     */
    public function previousSnapshot()
    {
        return self::where('snapshot_date', '<', $this->snapshot_date)
            ->orderBy('snapshot_date', 'desc')
            ->first();
    }

    /**
     * Get the next week's snapshot
     */
    public function nextSnapshot()
    {
        return self::where('snapshot_date', '>', $this->snapshot_date)
            ->orderBy('snapshot_date', 'asc')
            ->first();
    }

    /**
     * Create a new snapshot from current data
     */
    public static function createSnapshot($uploadedBy = null, $fileName = null, $notes = null)
    {
        $today = Carbon::now();
        $snapshotDate = $today->isFriday() ? $today : $today->next(Carbon::FRIDAY);
        
        $students = Student::all();
        $totalStudents = $students->count();
        
        // Create summary data
        $dataSummary = [
            'by_program' => Student::getRecapByProgram()->toArray(),
            'by_status' => Student::getRecapByStatus()->toArray(),
            'by_evaluasi' => Student::getRecapByEvaluasi()->toArray(),
            'total' => $totalStudents,
        ];

        return self::create([
            'snapshot_date' => $snapshotDate->toDateString(),
            'week_label' => 'Week ' . $snapshotDate->weekOfYear . ' - ' . $snapshotDate->format('M Y'),
            'total_students' => $totalStudents,
            'data_summary' => $dataSummary,
            'uploaded_by' => $uploadedBy,
            'file_name' => $fileName,
            'notes' => $notes,
        ]);
    }

    /**
     * Get latest snapshot
     */
    public static function getLatest()
    {
        return self::orderBy('snapshot_date', 'desc')->first();
    }

    /**
     * Get snapshots for a specific month
     */
    public function scopeForMonth($query, $year, $month)
    {
        return $query->whereYear('snapshot_date', $year)
            ->whereMonth('snapshot_date', $month)
            ->orderBy('snapshot_date', 'desc');
    }

    /**
     * Compare with previous snapshot
     */
    public function compareWithPrevious()
    {
        $previous = $this->previousSnapshot();
        
        if (!$previous) {
            return [
                'has_previous' => false,
                'message' => 'No previous snapshot to compare'
            ];
        }

        $changes = [
            'has_previous' => true,
            'previous_date' => $previous->snapshot_date->format('d M Y'),
            'current_date' => $this->snapshot_date->format('d M Y'),
            'total_change' => $this->total_students - $previous->total_students,
            'by_program' => $this->compareArrays(
                $previous->data_summary['by_program'] ?? [],
                $this->data_summary['by_program'] ?? []
            ),
            'by_status' => $this->compareArrays(
                $previous->data_summary['by_status'] ?? [],
                $this->data_summary['by_status'] ?? []
            ),
        ];

        return $changes;
    }

    /**
     * Helper to compare two arrays
     */
    private function compareArrays($oldData, $newData)
    {
        $comparison = [];
        $allKeys = array_unique(array_merge(
            array_column($oldData, 'program') ?: array_column($oldData, 'tindak_lanjut'),
            array_column($newData, 'program') ?: array_column($newData, 'tindak_lanjut')
        ));

        foreach ($allKeys as $key) {
            $oldItem = collect($oldData)->firstWhere('program', $key) 
                       ?? collect($oldData)->firstWhere('tindak_lanjut', $key);
            $newItem = collect($newData)->firstWhere('program', $key)
                       ?? collect($newData)->firstWhere('tindak_lanjut', $key);

            $oldCount = $oldItem['count'] ?? 0;
            $newCount = $newItem['count'] ?? 0;
            $change = $newCount - $oldCount;

            $comparison[] = [
                'key' => $key,
                'old_count' => $oldCount,
                'new_count' => $newCount,
                'change' => $change,
                'percentage' => $oldCount > 0 ? round(($change / $oldCount) * 100, 1) : 0
            ];
        }

        return $comparison;
    }
}
