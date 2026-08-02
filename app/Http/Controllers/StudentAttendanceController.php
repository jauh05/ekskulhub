<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $attendances = Attendance::with('attendanceSession.schedule.extracurricular')
            ->where('student_id', $user->id)
            ->latest()
            ->paginate(15);
            
        return view('student.attendances.index', compact('attendances'));
    }

    public function create(Request $request)
    {
        $scheduleId = $request->schedule_id;
        if (!$scheduleId) {
            return redirect()->route('student.dashboard')->with('error', 'Jadwal tidak valid');
        }
        
        $schedule = Schedule::with('extracurricular')->findOrFail($scheduleId);
        return view('student.attendances.create', compact('schedule'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'selfie' => 'nullable|image|max:5120' // max 5MB
        ]);

        $user = Auth::user();
        $scheduleId = $request->schedule_id;
        
        // Find open attendance session
        $attendanceSession = AttendanceSession::where('schedule_id', $scheduleId)
            ->where('status', 'open')
            ->first();
            
        if (!$attendanceSession) {
            return redirect()->route('student.dashboard')->with('error', 'Sesi absensi belum dibuka atau sudah ditutup');
        }
        
        $attendance = Attendance::firstOrNew([
            'student_id' => $user->id,
            'attendance_session_id' => $attendanceSession->id
        ]);
        
        if ($request->hasFile('selfie')) {
            $path = $request->file('selfie')->store('attendance_selfies', 'public');
            $attendance->selfie_path = $path;
            $attendance->selfie_status = 'pending';
        }
        
        $attendance->status = 'present';
        $attendance->method = 'selfie';
        $attendance->checked_at = now();
        $attendance->save();

        return redirect()->route('student.attendances.index')->with('success', 'Berhasil mencatat kehadiran');
    }
}
