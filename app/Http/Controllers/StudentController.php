<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ActivityLog;
use App\Helpers\PeriodeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Display a listing of students (filtered by current periode)
     */
    public function index(Request $request)
    {
        $currentPeriode = PeriodeHelper::getCurrentPeriode();
        
        // Base query - filter by current periode
        $query = Student::where('periode_akademik', $currentPeriode);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('binusian_id', 'like', "%{$search}%");
            });
        }

        // Program filter
        if ($request->filled('program')) {
            $query->where('program', $request->program);
        }

        // Status Warna filter
        if ($request->filled('status_warna')) {
            $query->where('status_warna', $request->status_warna);
        }

        // Get distinct programs from current periode only
        $programs = Student::where('periode_akademik', $currentPeriode)
            ->select('program')
            ->distinct()
            ->orderBy('program')
            ->pluck('program');

        // Get distinct status from current periode only
        $statusList = Student::where('periode_akademik', $currentPeriode)
            ->select('status_warna')
            ->distinct()
            ->whereNotNull('status_warna')
            ->where('status_warna', '!=', '')
            ->orderBy('status_warna')
            ->pluck('status_warna');

        $students = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('students.index', compact('students', 'programs', 'statusList'));
    }

    /**
     * Show the form for creating a new student
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created student
     */
    public function store(Request $request)
    {
        $currentPeriode = PeriodeHelper::getCurrentPeriode();
        
        $validator = Validator::make($request->all(), [
            'admit_term' => 'required|string',
            'campus' => 'required|string',
            'faculty' => 'required|string',
            'program' => 'required|string',
            'binusian_id' => 'required|string',
            'nim' => 'required|string|unique:students,nim',
            'binusian' => 'required|string',
            'name' => 'required|string',
            'tindak_lanjut' => 'required|string',
            'evaluasi' => 'required|string',
            'sks_kumulatif' => 'nullable|integer',
            'sks_sisa' => 'nullable|integer',
            'study_target_10' => 'nullable|string',
            'study_target_14' => 'nullable|string',
            'prediksi_smt_selesai' => 'nullable|string',
            'no_hp' => 'nullable|string',
            'notes_srsc' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Add periode to student data
        $studentData = $request->all();
        $studentData['periode_akademik'] = $currentPeriode;

        $student = Student::create($studentData);

        // Log activity
        ActivityLog::log(
            'create',
            "Created new student: {$student->name} (NIM: {$student->nim}) in periode {$currentPeriode}",
            'Student',
            $student->id
        );

        return redirect()->route('students.index')
            ->with('success', 'Data mahasiswa berhasil ditambahkan ke periode ' . $currentPeriode);
    }

    /**
     * Display the specified student
     */
    public function show(Student $student)
    {
        // Log activity
        ActivityLog::log(
            'view',
            "Viewed student details: {$student->name} (NIM: {$student->nim})",
            'Student',
            $student->id
        );

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, Student $student)
    {
        $validator = Validator::make($request->all(), [
            'admit_term' => 'required|string',
            'campus' => 'required|string',
            'faculty' => 'required|string',
            'program' => 'required|string',
            'binusian_id' => 'required|string',
            'nim' => 'required|string|unique:students,nim,' . $student->id,
            'binusian' => 'required|string',
            'name' => 'required|string',
            'tindak_lanjut' => 'required|string',
            'evaluasi' => 'required|string',
            'sks_kumulatif' => 'nullable|integer',
            'sks_sisa' => 'nullable|integer',
            'study_target_10' => 'nullable|string',
            'study_target_14' => 'nullable|string',
            'prediksi_smt_selesai' => 'nullable|string',
            'no_hp' => 'nullable|string',
            'notes_srsc' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Keep the same periode (don't allow changing periode)
        $studentData = $request->except('periode_akademik');
        $student->update($studentData);

        // Log activity
        ActivityLog::log(
            'update',
            "Updated student: {$student->name} (NIM: {$student->nim})",
            'Student',
            $student->id
        );

        return redirect()->route('students.show', $student)
            ->with('success', 'Data mahasiswa berhasil diupdate');
    }

    /**
     * Remove the specified student
     */
    public function destroy(Student $student)
    {
        $name = $student->name;
        $nim = $student->nim;

        $student->delete();

        // Log activity
        ActivityLog::log(
            'delete',
            "Deleted student: {$name} (NIM: {$nim})",
            'Student',
            null
        );

        return redirect()->route('students.index')
            ->with('success', 'Data mahasiswa berhasil dihapus');
    }

    /**
     * Delete all students in current periode
     */
    public function destroyAll(Request $request)
    {
        $request->validate([
            'confirmation' => 'required|in:DELETE ALL DATA',
            'force_delete' => 'sometimes|boolean',
        ]);

        $currentPeriode = PeriodeHelper::getCurrentPeriode();
        $forceDelete = $request->boolean('force_delete', true); // Default true
        
        // COUNT students
        $count = Student::where('periode_akademik', $currentPeriode)->count();
        
        if ($count === 0) {
            return redirect()->route('students.index')
                ->with('error', 'Tidak ada data untuk dihapus di periode ini.');
        }

        if ($forceDelete) {
            // FORCE DELETE (permanent)
            Student::where('periode_akademik', $currentPeriode)->forceDelete();
            $message = "✅ Berhasil menghapus permanen {$count} data mahasiswa di periode {$currentPeriode}";
        } else {
            // SOFT DELETE (can be restored)
            Student::where('periode_akademik', $currentPeriode)->delete();
            $message = "✅ Berhasil soft delete {$count} data mahasiswa di periode {$currentPeriode} (dapat di-restore)";
        }

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $forceDelete ? 'force_delete_all' : 'soft_delete_all',
            'description' => "Menghapus " . ($forceDelete ? 'permanen' : 'soft delete') . " semua data mahasiswa ({$count} mahasiswa) di periode {$currentPeriode}",
            'periode_akademik' => $currentPeriode,
        ]);

        return redirect()->route('students.index')->with('success', $message);
    }

    /**
     * Quick update status warna
     */
    public function updateStatus(Request $request, Student $student)
    {
        $request->validate([
            'status_warna' => 'required|string'
        ]);
        
        $oldStatus = $student->status_warna;
        $student->status_warna = $request->status_warna;
        $student->save();
        
        // Log activity
        ActivityLog::log(
            'update_status',
            "Updated status warna for {$student->name} from '{$oldStatus}' to '{$request->status_warna}'",
            'Student',
            $student->id
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diupdate',
            'status' => $student->status_warna,
            'colorClass' => $student->getStatusColorClass()
        ]);
    }
}