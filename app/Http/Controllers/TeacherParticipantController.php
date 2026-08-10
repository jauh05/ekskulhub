<?php

namespace App\Http\Controllers;

use App\Models\ExtracurricularRegistration;
use App\Models\Extracurricular;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherParticipantController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user();
        $ekskulIds = $teacher->taughtExtracurriculars()->pluck('id');
        $ekskuls = $teacher->taughtExtracurriculars;
        
        $query = ExtracurricularRegistration::with(['student.studentProfile', 'student.attendances', 'extracurricular.schedules'])
            ->whereIn('extracurricular_id', $ekskulIds)
            ->where('status', 'approved');
            
        // Filter by ekskul
        if ($request->filled('ekskul_id')) {
            $query->where('extracurricular_id', $request->ekskul_id);
        }
        
        // Calculate stats
        $totalSiswa = (clone $query)->count();
        $siswaBaruBulanIni = (clone $query)->whereMonth('created_at', now()->month)->count();
        $totalEkskul = $request->filled('ekskul_id') ? 1 : $ekskulIds->count();
            
        $participants = $query->latest()->paginate(15)->withQueryString();
            
        return view('teacher.participants.index', compact(
            'participants', 'ekskuls', 'totalSiswa', 'siswaBaruBulanIni', 'totalEkskul'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $registration = ExtracurricularRegistration::findOrFail($id);
        
        // Verify this registration belongs to an extracurricular taught by this teacher
        $teacher = Auth::user();
        if (!$teacher->taughtExtracurriculars->contains($registration->extracurricular_id)) {
            abort(403, 'Unauthorized action.');
        }

        $registration->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status pendaftaran berhasil diperbarui.');
    }

    public function resetPassword(Request $request, \App\Models\User $user)
    {
        $teacher = Auth::user();
        
        if ($user->role !== 'student') {
            abort(403, 'Hanya dapat mereset sandi siswa.');
        }

        $isStudentInTeacherEkskul = ExtracurricularRegistration::where('student_id', $user->id)
            ->whereIn('extracurricular_id', $teacher->taughtExtracurriculars()->pluck('id'))
            ->where('status', 'approved')
            ->exists();

        if (!$isStudentInTeacherEkskul) {
            abort(403, 'Unauthorized action. Siswa bukan peserta ekskul Anda.');
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make('password')
        ]);

        return back()->with('success', 'Kata sandi siswa ' . $user->name . ' berhasil direset menjadi: password');
    }
}
