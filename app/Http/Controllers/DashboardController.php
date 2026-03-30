<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\WeeklySnapshot;
use App\Models\ActivityLog;
use App\Helpers\PeriodeHelper;
use App\Services\StudentDataService;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $studentService;

    public function __construct(StudentDataService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index()
    {
        $currentPeriode = PeriodeHelper::getCurrentPeriode();

        // Total students in current periode
        $totalStudents = Student::where('periode_akademik', $currentPeriode)->count();

        // Recap by program (current periode only)
        $recapByProgram = Student::where('periode_akademik', $currentPeriode)
            ->select('program', DB::raw('count(*) as total'))
            ->groupBy('program')
            ->orderBy('total', 'desc')
            ->get();

        // Recap by status (current periode only)
        $recapByStatus = Student::where('periode_akademik', $currentPeriode)
            ->select('tindak_lanjut', DB::raw('count(*) as total'))
            ->groupBy('tindak_lanjut')
            ->orderBy('total', 'desc')
            ->get();

        // Recap by status warna (current periode only)
        $recapByStatusWarna = Student::where('periode_akademik', $currentPeriode)
            ->select('status_warna', DB::raw('count(*) as total'))
            ->whereNotNull('status_warna')
            ->where('status_warna', '!=', '')
            ->groupBy('status_warna')
            ->orderBy('total', 'desc')
            ->get();

        // Latest snapshot
        $latestSnapshot = WeeklySnapshot::orderBy('created_at', 'desc')->first();

        // Comparison with previous week
        $comparison = $this->studentService->compareWithPreviousWeek();

        // Weekly trend
        $weeklyTrend = $this->studentService->getWeeklyTrend();

        // Recent activities (limit 10)
        $recentActivities = ActivityLog::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'currentPeriode',
            'totalStudents',
            'recapByProgram',
            'recapByStatus',
            'recapByStatusWarna',
            'latestSnapshot',
            'comparison',
            'weeklyTrend',
            'recentActivities'
        ));
    }
}
