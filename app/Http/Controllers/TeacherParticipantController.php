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
        
        $query = ExtracurricularRegistration::with(['student.studentProfile', 'extracurricular'])
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
}
