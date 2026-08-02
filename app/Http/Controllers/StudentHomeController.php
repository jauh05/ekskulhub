<?php

namespace App\Http\Controllers;

use App\Models\ExtracurricularRegistration;
use App\Models\Schedule;
use App\Models\AttendanceSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentHomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ekskul diikuti (termasuk yang pending/rejected)
        $ekskulTerdaftar = ExtracurricularRegistration::with('extracurricular')
            ->where('student_id', $user->id)
            ->get();
            
        $ekskulIds = $ekskulTerdaftar->where('status', 'approved')->pluck('extracurricular_id');
        $totalEkskul = $ekskulTerdaftar->where('status', 'approved')->count();
        
        // Kehadiran (Percentage)
        $totalSchedules = Schedule::whereIn('extracurricular_id', $ekskulIds)
            ->where('activity_date', '<=', Carbon::now())
            ->count();
            
        $attendancesCount = \App\Models\Attendance::where('student_id', $user->id)
            ->where('status', 'present')
            ->count();
            
        $attendancePercentage = $totalSchedules > 0 ? round(($attendancesCount / $totalSchedules) * 100) : 0;
        
        // Jadwal Hari Ini
        $jadwalHariIni = Schedule::with('extracurricular')
            ->whereIn('extracurricular_id', $ekskulIds)
            ->whereDate('activity_date', Carbon::today())
            ->get();

        // Pengumuman Terbaru
        $pengumuman = \App\Models\Announcement::with('extracurricular')
            ->whereIn('extracurricular_id', $ekskulIds)
            ->where('status', 'published')
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('student.dashboard', compact('user', 'totalEkskul', 'attendancePercentage', 'jadwalHariIni', 'ekskulTerdaftar', 'pengumuman'));
    }
}
