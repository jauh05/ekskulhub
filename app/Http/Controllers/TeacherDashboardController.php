<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ExtracurricularRegistration;
use App\Models\Schedule;
use Carbon\Carbon;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        $extracurriculars = $teacher->taughtExtracurriculars;
        $ekskulIds = $extracurriculars->pluck('id');
        
        $totalParticipants = ExtracurricularRegistration::whereIn('extracurricular_id', $ekskulIds)
            ->where('status', 'approved')
            ->count();
            
        $schedulesThisMonth = Schedule::whereIn('extracurricular_id', $ekskulIds)
            ->whereMonth('activity_date', Carbon::now()->month)
            ->count();
        $upcomingSchedules = Schedule::whereIn('extracurricular_id', $ekskulIds)
            ->whereDate('activity_date', '>=', Carbon::today())
            ->with('extracurricular')
            ->orderBy('activity_date')
            ->orderBy('start_time')
            ->take(3)
            ->get();
        $recentParticipants = ExtracurricularRegistration::whereIn('extracurricular_id', $ekskulIds)
            ->where('status', 'approved')
            ->with(['student', 'extracurricular'])
            ->latest()
            ->take(5)
            ->get();
            
        $presentToday = \App\Models\Attendance::whereHas('attendanceSession.schedule', function($q) use ($ekskulIds) {
            $q->whereIn('extracurricular_id', $ekskulIds)->whereDate('activity_date', Carbon::today());
        })->where('status', 'present')->count();
        
        $pendingSessions = \App\Models\AttendanceSession::whereHas('schedule', function($q) use ($ekskulIds) {
            $q->whereIn('extracurricular_id', $ekskulIds);
        })->where('status', 'open')->count();
        
        $announcements = \App\Models\Announcement::whereIn('extracurricular_id', $ekskulIds)
            ->with('extracurricular')
            ->latest()
            ->take(3)
            ->get();
            
        return view('teacher.dashboard', compact('totalParticipants', 'schedulesThisMonth', 'extracurriculars', 'upcomingSchedules', 'recentParticipants', 'presentToday', 'pendingSessions', 'announcements'));
    }

    public function updateClassCode(Request $request, \App\Models\Extracurricular $extracurricular)
    {
        // Verify this teacher owns this extracurricular
        if ($extracurricular->teacher_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'class_code' => ['required', 'string', 'max:50', 'unique:extracurriculars,class_code,' . $extracurricular->id],
        ]);

        $extracurricular->update([
            'class_code' => strtoupper($request->class_code)
        ]);

        return redirect()->back()->with('success', 'Kode kelas berhasil diperbarui!');
    }
}
