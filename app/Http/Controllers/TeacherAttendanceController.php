<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSession;
use App\Models\Attendance;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeacherAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user();
        $ekskuls = $teacher->taughtExtracurriculars;
        $ekskulIds = $ekskuls->pluck('id');
        
        $query = Attendance::with(['student', 'attendanceSession.schedule.extracurricular'])
            ->whereHas('attendanceSession.schedule', function($q) use ($ekskulIds, $request) {
                $q->whereIn('extracurricular_id', $ekskulIds);
                if ($request->filled('ekskul_id')) {
                    $q->where('extracurricular_id', $request->ekskul_id);
                }
                if ($request->filled('date')) {
                    $q->whereDate('activity_date', $request->date);
                }
            });
            
        // Statistics
        $totalHadir = (clone $query)->where('status', 'present')->count();
        $totalSakit = (clone $query)->where('status', 'sick')->count();
        $totalIzin = (clone $query)->where('status', 'permitted')->count();
        $totalAlpa = (clone $query)->where('status', 'absent')->count();
        $totalSiswa = $totalHadir + $totalSakit + $totalIzin + $totalAlpa;
        
        $persentaseHadir = $totalSiswa > 0 ? round(($totalHadir / $totalSiswa) * 100) : 0;
            
        $attendances = $query->latest()->paginate(15)->withQueryString();
            
        $todaySchedules = Schedule::whereIn('extracurricular_id', $ekskulIds)
            ->whereDate('activity_date', today())
            ->with('extracurricular')
            ->get();
            
        $totalActiveStudents = \App\Models\ExtracurricularRegistration::whereIn('extracurricular_id', $ekskulIds)
            ->where('status', 'approved')
            ->count();
            
        $allSchedules = Schedule::whereIn('extracurricular_id', $ekskulIds)
            ->latest('activity_date')
            ->limit(50)
            ->get();
            
        $activeStudents = \App\Models\ExtracurricularRegistration::with('student')
            ->whereIn('extracurricular_id', $ekskulIds)
            ->where('status', 'approved')
            ->get();
            
        $activeSession = \App\Models\AttendanceSession::where('opened_by', Auth::id())
            ->where('status', 'open')
            ->first();
            
        return view('teacher.attendances.index', compact(
            'attendances', 'ekskuls', 'totalHadir', 'totalSakit', 'totalIzin', 'totalAlpa', 'persentaseHadir', 'todaySchedules', 'totalActiveStudents', 'allSchedules', 'activeStudents', 'activeSession'
        ));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'student_id' => 'required|exists:users,id',
            'status' => 'required|in:present,late,permitted,sick,absent',
            'notes' => 'nullable|string'
        ]);

        // Find or create attendance session for this schedule
        $session = AttendanceSession::firstOrCreate(
            ['schedule_id' => $request->schedule_id],
            [
                'status' => 'closed',
                'opened_by' => Auth::id(),
                'opened_at' => now(),
            ]
        );

        // Check if attendance already exists
        $exists = Attendance::where('attendance_session_id', $session->id)
            ->where('student_id', $request->student_id)
            ->exists();
            
        if ($exists) {
            return back()->with('error', 'Siswa tersebut sudah memiliki riwayat presensi pada jadwal ini.');
        }

        Attendance::create([
            'attendance_session_id' => $session->id,
            'student_id' => $request->student_id,
            'status' => $request->status,
            'method' => 'manual',
            'checked_at' => now(),
            'notes' => $request->notes,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Data presensi berhasil ditambahkan.');
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'status' => 'required|in:present,late,permitted,sick,absent',
            'notes' => 'nullable|string'
        ]);

        $attendance->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'is_verified_by_teacher' => true,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Data presensi berhasil diperbarui.');
    }
    
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return back()->with('success', 'Data presensi berhasil dihapus.');
    }

    public function startSession(Request $request)
    {
        if ($request->has('schedule_id') && !empty($request->schedule_id)) {
            $schedule = Schedule::findOrFail($request->schedule_id);
        } elseif ($request->has('extracurricular_id') && !empty($request->extracurricular_id)) {
            // Verify teacher owns this extracurricular
            $extracurricular = \App\Models\Extracurricular::where('id', $request->extracurricular_id)
                ->where('teacher_id', Auth::id())
                ->firstOrFail();
                
            $schedule = Schedule::create([
                'extracurricular_id' => $extracurricular->id,
                'title' => 'Sesi Latihan Tambahan',
                'activity_date' => now()->toDateString(),
                'start_time' => now()->format('H:i'),
                'end_time' => now()->addHours(2)->format('H:i'),
                'attendance_start_at' => now()->format('H:i'),
                'attendance_end_at' => now()->addHours(2)->format('H:i'),
                'location' => 'Lapangan Utama / Ruang Kelas',
                'created_by' => Auth::id(),
            ]);
        } else {
            return back()->with('error', 'Pilih jadwal atau ekstrakurikuler terlebih dahulu.');
        }

        $participantsCount = \App\Models\ExtracurricularRegistration::where('extracurricular_id', $schedule->extracurricular_id)
            ->where('status', 'approved')
            ->count();
            
        if ($participantsCount === 0) {
            return back()->with('error', 'Tidak dapat memulai presensi karena belum ada siswa yang terdaftar/disetujui di ekstrakurikuler ini.');
        }

        $existingSession = AttendanceSession::where('schedule_id', $schedule->id)
            ->where('status', 'open')
            ->first();

        if ($existingSession) {
            return redirect()->route('teacher.attendances.live', $existingSession->id);
        }

        $session = AttendanceSession::create([
            'schedule_id' => $schedule->id,
            'status' => 'open',
            'opened_by' => Auth::id(),
            'opened_at' => now(),
            'qr_secret_hash' => sprintf("%06d", mt_rand(1, 999999)),
            'qr_expires_at' => now()->addSeconds(10),
            'qr_last_rotated_at' => now(),
        ]);

        return redirect()->route('teacher.attendances.live', $session->id);
    }

    public function live(AttendanceSession $session)
    {
        if ($session->opened_by != Auth::id()) {
            abort(403);
        }

        $session->load('schedule.extracurricular');
        return view('teacher.attendances.live', compact('session'));
    }

    public function getLiveQr(AttendanceSession $session)
    {
        if ($session->status !== 'open') {
            return response()->json(['error' => 'Session is closed'], 400);
        }

        if (now()->greaterThanOrEqualTo($session->qr_expires_at)) {
            $session->update([
                'qr_secret_hash' => sprintf("%06d", mt_rand(1, 999999)),
                'qr_expires_at' => now()->addSeconds(10),
                'qr_last_rotated_at' => now(),
            ]);
        }

        $qrData = json_encode([
            'session_id' => $session->id,
            'secret' => $session->qr_secret_hash
        ]);

        return response()->json([
            'expires_at' => $session->qr_expires_at->toIso8601String(),
            'hash' => $session->qr_secret_hash,
            'qr_payload' => $qrData
        ]);
    }

    public function getLiveData(AttendanceSession $session)
    {
        $attendances = $session->attendances()->with('student')->latest()->get();
        return response()->json(['attendances' => $attendances]);
    }

    public function closeSession(AttendanceSession $session)
    {
        if ($session->opened_by != Auth::id()) {
            abort(403);
        }

        $session->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return redirect()->route('teacher.attendances.index')->with('success', 'Sesi absensi telah ditutup.');
    }
}
