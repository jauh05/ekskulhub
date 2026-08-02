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
            'type' => 'required|in:hadir,izin',
            'method' => 'nullable|required_if:type,hadir|in:qr,manual,selfie',
            'reason' => 'nullable|required_if:type,izin|string|max:255',
            'proof' => 'nullable|file|max:1024|mimes:jpg,jpeg,png,pdf',
            'qr_code' => 'nullable|required_if:method,qr|string',
            'session_code' => 'nullable|required_if:method,manual|string',
            'selfie' => 'nullable|required_if:method,selfie|image|max:5120'
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
        
        if ($request->type === 'izin') {
            $attendance->status = 'permission';
            $attendance->method = 'manual'; // Just default to manual for izin
            $attendance->notes = $request->reason;
            
            if ($request->hasFile('proof')) {
                $path = $request->file('proof')->store('attendance_proofs', 'public');
                $attendance->proof_file = $path;
            }
        } else {
            // Type == hadir
            if ($request->method === 'qr') {
                $qrData = json_decode($request->qr_code, true);
                $secret = $qrData['secret'] ?? $request->qr_code;
                
                if ($secret !== $attendanceSession->qr_secret_hash) {
                    return back()->with('error', 'QR Code tidak valid atau sudah kedaluwarsa.');
                }
                $attendance->method = 'qr';
            } elseif ($request->method === 'manual') {
                if (strtoupper($request->session_code) !== strtoupper($attendanceSession->session_code)) {
                    return back()->with('error', 'Kode sesi tidak valid.');
                }
                $attendance->method = 'manual';
            } elseif ($request->method === 'selfie') {
                $path = $request->file('selfie')->store('attendance_selfies', 'public');
                $attendance->selfie_path = $path;
                $attendance->selfie_status = 'pending';
                $attendance->method = 'selfie';
            }
            $attendance->status = 'present';
        }
        
        $attendance->checked_at = now();
        $attendance->save();

        return redirect()->route('student.attendances.index')->with('success', 'Data presensi berhasil disimpan.');
    }

    public function getActiveSessions(Request $request)
    {
        $user = Auth::user();
        
        AttendanceSession::autoCloseExpiredSessions();
        
        // Ekskul diikuti (approved)
        $ekskulIds = \App\Models\ExtracurricularRegistration::where('student_id', $user->id)
            ->where('status', 'approved')
            ->pluck('extracurricular_id');
            
        // Get active sessions
        $activeSessions = \App\Models\AttendanceSession::with('schedule.extracurricular')
            ->where('status', 'open')
            ->whereHas('schedule', function($q) use ($ekskulIds) {
                $q->whereIn('extracurricular_id', $ekskulIds);
            })
            ->get();
            
        $result = [];
        foreach ($activeSessions as $session) {
            $alreadyAttended = \App\Models\Attendance::where('attendance_session_id', $session->id)
                ->where('student_id', $user->id)
                ->exists();
                
            $result[] = [
                'id' => $session->id,
                'schedule_id' => $session->schedule_id,
                'extracurricular_name' => $session->schedule->extracurricular->name,
                'already_attended' => $alreadyAttended
            ];
        }
        
        return response()->json($result);
    }
}
