<?php

namespace App\Http\Controllers;

use App\Models\Extracurricular;
use App\Models\ExtracurricularRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentExtracurricularController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // List all active ekskul
        $extracurriculars = Extracurricular::where('status', 'active')->get();
        
        // List user's registrations
        $myRegistrations = ExtracurricularRegistration::where('student_id', $user->id)->pluck('status', 'extracurricular_id')->toArray();
        
        return view('student.extracurriculars.index', compact('extracurriculars', 'myRegistrations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'extracurricular_id' => 'required|exists:extracurriculars,id'
        ]);

        $user = Auth::user();

        // Check if student profile is complete
        if (!$user->studentProfile) {
            return redirect()->route('student.profile.index')->with('error', 'Silakan lengkapi profil terlebih dahulu.');
        }

        ExtracurricularRegistration::firstOrCreate([
            'student_id' => $user->id,
            'extracurricular_id' => $request->extracurricular_id
        ], [
            'status' => 'pending'
        ]);

        return redirect()->route('student.extracurriculars.index')->with('success', 'Berhasil mendaftar. Menunggu persetujuan admin.');
    }
}
