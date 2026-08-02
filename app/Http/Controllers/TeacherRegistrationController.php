<?php

namespace App\Http\Controllers;

use App\Models\ExtracurricularRegistration;
use App\Models\Extracurricular;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user();
        $ekskulIds = $teacher->taughtExtracurriculars()->pluck('id');
        $ekskuls = $teacher->taughtExtracurriculars;
        
        $query = ExtracurricularRegistration::with(['student.studentProfile', 'student.attendances', 'extracurricular.schedules'])
            ->whereIn('extracurricular_id', $ekskulIds)
            ->where('status', 'pending');
            
        // Filter by ekskul
        if ($request->filled('ekskul_id')) {
            $query->where('extracurricular_id', $request->ekskul_id);
        }
        
        // Calculate stats
        $totalSiswa = (clone $query)->count();
        $siswaBaruBulanIni = (clone $query)->whereMonth('created_at', now()->month)->count();
        $totalEkskul = $request->filled('ekskul_id') ? 1 : $ekskulIds->count();
            
        $participants = $query->latest()->paginate(15)->withQueryString();
            
        return view('teacher.registrations.index', compact(
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

        return redirect()->route('teacher.registrations.index')->with('success', 'Status pendaftaran berhasil diperbarui.');
    }
}
