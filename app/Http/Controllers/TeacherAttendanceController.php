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
        AttendanceSession::autoCloseExpiredSessions();
        
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
            
        if ($request->filled('session_id')) {
            $query->where('attendance_session_id', $request->session_id);
        }
            
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
            
        // Fetch sessions for the new history table
        $attendanceSessionsQuery = \App\Models\AttendanceSession::with(['schedule.extracurricular'])
            ->withCount('attendances')
            ->whereHas('schedule', function($q) use ($ekskulIds, $request) {
                $q->whereIn('extracurricular_id', $ekskulIds);
                if ($request->filled('ekskul_id')) {
                    $q->where('extracurricular_id', $request->ekskul_id);
                }
            });
            
        $attendanceSessions = $attendanceSessionsQuery->latest('created_at')->paginate(5, ['*'], 'session_page')->withQueryString();
            
        return view('teacher.attendances.index', compact(
            'attendances', 'ekskuls', 'totalHadir', 'totalSakit', 'totalIzin', 'totalAlpa', 'persentaseHadir', 'todaySchedules', 'totalActiveStudents', 'allSchedules', 'activeStudents', 'activeSession', 'attendanceSessions'
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
        if ($attendance->selfie_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($attendance->selfie_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($attendance->selfie_path);
        }
        
        if ($attendance->proof_file && \Illuminate\Support\Facades\Storage::disk('public')->exists($attendance->proof_file)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($attendance->proof_file);
        }

        $attendance->delete();
        return back()->with('success', 'Data presensi berhasil dihapus.');
    }

    public function updateSession(Request $request, AttendanceSession $session)
    {
        if ($session->opened_by != Auth::id()) {
            abort(403);
        }

        $request->validate([
            'activity_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'status' => 'required|in:open,closed'
        ]);

        $session->schedule->update([
            'activity_date' => $request->activity_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        $wasOpen = $session->status === 'open';

        $session->update([
            'status' => $request->status
        ]);

        if ($wasOpen && $request->status === 'closed') {
            $session->closeWithAutoAlpha();
        }

        if (!$wasOpen && $request->status === 'open') {
            return redirect()->route('teacher.attendances.live', $session->id)->with('success', 'Sesi absensi berhasil dibuka kembali.');
        }

        return back()->with('success', 'Data sesi berhasil diperbarui.');
    }

    public function destroySession(AttendanceSession $session)
    {
        if ($session->opened_by != Auth::id()) {
            abort(403);
        }

        // This will cascade delete attendances because of DB constraints, but we can also manually do it or just let DB handle it.
        // It's safer to delete explicitly or let cascade handle it. Cascade is set in migration.
        $session->delete();

        return back()->with('success', 'Sesi beserta semua data presensinya berhasil dihapus.');
    }

    public function startSession(Request $request)
    {
        if ($request->has('schedule_id') && !empty($request->schedule_id)) {
            $schedule = Schedule::findOrFail($request->schedule_id);
            
            // Check if current time is within schedule attendance window
            $now = now()->format('H:i:s');
            $startTime = \Carbon\Carbon::parse($schedule->attendance_start_at)->format('H:i:s');
            $endTime = \Carbon\Carbon::parse($schedule->attendance_end_at)->format('H:i:s');
            
            // If it's a different date, we shouldn't allow it either, but typically todaySchedules are filtered by today.
            // Just comparing time is enough for today schedules.
            if ($now < $startTime || $now > $endTime) {
                return back()->with('error', 'Presensi gagal dimulai: Waktu sekarang ('.now()->format('H:i').') berada di luar rentang waktu jadwal ('.\Carbon\Carbon::parse($startTime)->format('H:i').' - '.\Carbon\Carbon::parse($endTime)->format('H:i').').');
            }
        } elseif ($request->has('extracurricular_id') && !empty($request->extracurricular_id)) {
            // Verify teacher owns this extracurricular
            $extracurricular = \App\Models\Extracurricular::where('id', $request->extracurricular_id)
                ->where('teacher_id', Auth::id())
                ->firstOrFail();
                
            $schedule = Schedule::create([
                'extracurricular_id' => $extracurricular->id,
                'title' => $request->topic ?? 'Sesi Latihan Tambahan',
                'activity_date' => $request->activity_date ?? now()->toDateString(),
                'start_time' => $request->start_time ?? now()->format('H:i'),
                'end_time' => $request->end_time ?? now()->addHours(2)->format('H:i'),
                'attendance_start_at' => $request->start_time ?? now()->format('H:i'),
                'attendance_end_at' => $request->end_time ?? now()->addHours(2)->format('H:i'),
                'location' => $request->location ?? 'Lapangan Utama / Ruang Kelas',
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
            'session_code' => strtoupper(\Illuminate\Support\Str::random(6)),
            'qr_expires_at' => now()->addSeconds(30),
            'qr_last_rotated_at' => now(),
        ]);

        return redirect()->route('teacher.attendances.live', $session->id);
    }

    public function live(AttendanceSession $session)
    {
        if ($session->opened_by != Auth::id()) {
            abort(403);
        }

        if (empty($session->session_code)) {
            $session->update([
                'session_code' => strtoupper(\Illuminate\Support\Str::random(6))
            ]);
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
                'session_code' => strtoupper(\Illuminate\Support\Str::random(6)),
                'qr_expires_at' => now()->addSeconds(30),
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
            'session_code' => $session->session_code,
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

        $session->closeWithAutoAlpha();

        return redirect()->route('teacher.attendances.index')->with('success', 'Sesi absensi telah ditutup.');
    }

    public function extendSession(Request $request, AttendanceSession $session)
    {
        if ($session->opened_by != Auth::id()) {
            abort(403);
        }

        $schedule = $session->schedule;
        
        // Perpanjang 15 menit
        $newEndTime = \Carbon\Carbon::parse($schedule->attendance_end_at)->addMinutes(15)->format('H:i:s');
        
        $schedule->update([
            'attendance_end_at' => $newEndTime
        ]);

        return back()->with('success', 'Waktu absensi berhasil diperpanjang 15 menit.');
    }
}
