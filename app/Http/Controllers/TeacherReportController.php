<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ExtracurricularRegistration;
use App\Models\Assessment;
use App\Models\AttendanceSession;
use App\Models\Schedule;
use Illuminate\Support\Facades\Response;

class TeacherReportController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user();
        $ekskuls = $teacher->taughtExtracurriculars;
        $ekskulIds = $ekskuls->pluck('id');
        
        $query = ExtracurricularRegistration::with(['student.studentProfile', 'extracurricular'])
            ->whereIn('extracurricular_id', $ekskulIds)
            ->where('status', 'approved');
            
        if ($request->filled('ekskul_id')) {
            $query->where('extracurricular_id', $request->ekskul_id);
        }
        
        $registrations = $query->get();
            
        $reports = [];
        $totalKehadiranPercentage = 0;
        $totalNilai = 0;
        $countSiswaDinilai = 0;
        
        foreach ($registrations as $reg) {
            // Count total schedules for THIS particular ekskul
            $totalSchedules = Schedule::where('extracurricular_id', $reg->extracurricular_id)->count();
            
            $attendancesCount = AttendanceSession::where('student_id', $reg->student_id)
                ->whereHas('schedule', function($q) use ($reg) {
                    $q->where('extracurricular_id', $reg->extracurricular_id);
                })
                ->where('status', 'present')
                ->count();
                
            $attendancePercentage = $totalSchedules > 0 ? round(($attendancesCount / $totalSchedules) * 100) : 0;
            $totalKehadiranPercentage += $attendancePercentage;
            
            $latestAssessment = Assessment::where('registration_id', $reg->id)->latest()->first();
            if ($latestAssessment) {
                $totalNilai += $latestAssessment->final_score;
                $countSiswaDinilai++;
            }
            
            $reports[] = (object) [
                'student' => $reg->student,
                'extracurricular' => $reg->extracurricular,
                'attendance_percentage' => $attendancePercentage,
                'final_score' => $latestAssessment ? $latestAssessment->final_score : '-',
                'predicate' => $latestAssessment ? $latestAssessment->predicate : '-',
                'period' => $latestAssessment ? $latestAssessment->period_name : '-'
            ];
        }
        
        $rataRataKehadiran = count($reports) > 0 ? round($totalKehadiranPercentage / count($reports), 1) : 0;
        $rataRataNilai = $countSiswaDinilai > 0 ? round($totalNilai / $countSiswaDinilai, 1) : 0;
        $totalSiswaAktif = count($reports);
            
        return view('teacher.reports.index', compact(
            'reports', 'ekskuls', 'rataRataKehadiran', 'rataRataNilai', 'totalSiswaAktif'
        ));
    }

    public function export()
    {
        $teacher = Auth::user();
        $ekskulIds = $teacher->taughtExtracurriculars()->pluck('id');
        
        $registrations = ExtracurricularRegistration::with(['student.studentProfile', 'extracurricular'])
            ->whereIn('extracurricular_id', $ekskulIds)
            ->where('status', 'approved')
            ->get();
            
        $totalSchedules = Schedule::whereIn('extracurricular_id', $ekskulIds)->count();
        
        $filename = "rekap_nilai_absensi_" . date('Ymd_His') . ".csv";
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $callback = function() use($registrations, $totalSchedules, $ekskulIds) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array('Nama Siswa', 'Kelas', 'Ekstrakurikuler', 'Kehadiran (%)', 'Periode', 'Nilai Angka', 'Predikat'));

            foreach ($registrations as $reg) {
                $attendancesCount = AttendanceSession::where('student_id', $reg->student_id)
                    ->whereHas('schedule', function($q) use ($ekskulIds) {
                        $q->whereIn('extracurricular_id', $ekskulIds);
                    })
                    ->where('status', 'present')
                    ->count();
                    
                $attendancePercentage = $totalSchedules > 0 ? round(($attendancesCount / $totalSchedules) * 100) : 0;
                $latestAssessment = Assessment::where('registration_id', $reg->id)->latest()->first();

                fputcsv($file, array(
                    $reg->student->name,
                    $reg->student->studentProfile->class ?? '-',
                    $reg->extracurricular->name,
                    $attendancePercentage . '%',
                    $latestAssessment ? $latestAssessment->period_name : '-',
                    $latestAssessment ? $latestAssessment->final_score : '-',
                    $latestAssessment ? $latestAssessment->predicate : '-'
                ));
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
