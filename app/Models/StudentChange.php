<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentChange extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'snapshot_id',
        'change_type',
        'field_changed',
        'old_value',
        'new_value',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the student that owns the change
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the snapshot this change belongs to
     */
    public function snapshot()
    {
        return $this->belongsTo(WeeklySnapshot::class, 'snapshot_id');
    }

    /**
     * Scope for specific change types
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('change_type', $type);
    }

    /**
     * Get changes for a specific field
     */
    public function scopeForField($query, $field)
    {
        return $query->where('field_changed', $field);
    }

    /**
     * Track changes between old and new student data
     */
    public static function trackChanges($oldStudent, $newStudent, $snapshotId)
    {
        $changes = [];
        $fieldsToTrack = [
            'tindak_lanjut',
            'evaluasi',
            'sks_kumulatif',
            'program',
            'campus'
        ];

        foreach ($fieldsToTrack as $field) {
            if ($oldStudent->$field != $newStudent[$field]) {
                $changes[] = self::create([
                    'student_id' => $oldStudent->id,
                    'snapshot_id' => $snapshotId,
                    'change_type' => 'updated',
                    'field_changed' => $field,
                    'old_value' => $oldStudent->$field,
                    'new_value' => $newStudent[$field],
                ]);
            }
        }

        return $changes;
    }

    /**
     * Get human-readable change description
     */
    public function getDescriptionAttribute()
    {
        $fieldLabels = [
            'tindak_lanjut' => 'Tindak Lanjut',
            'evaluasi' => 'Evaluasi',
            'sks_kumulatif' => 'SKS Kumulatif',
            'program' => 'Program',
            'campus' => 'Campus'
        ];

        $fieldLabel = $fieldLabels[$this->field_changed] ?? $this->field_changed;

        return match($this->change_type) {
            'new' => "Mahasiswa baru ditambahkan",
            'deleted' => "Mahasiswa dihapus",
            'status_change' => "{$fieldLabel} berubah dari '{$this->old_value}' ke '{$this->new_value}'",
            'updated' => "{$fieldLabel} diupdate dari '{$this->old_value}' ke '{$this->new_value}'",
            default => "Perubahan pada {$fieldLabel}"
        };
    }
}
