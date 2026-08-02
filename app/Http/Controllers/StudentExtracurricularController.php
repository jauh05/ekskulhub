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
        
        // List user's registrations
        $myRegistrations = ExtracurricularRegistration::where('student_id', $user->id)->pluck('status', 'extracurricular_id')->toArray();
        
        // Only get the extracurriculars the user is registered for
        $extracurriculars = Extracurricular::whereIn('id', array_keys($myRegistrations))->get();
        
        return view('student.extracurriculars.index', compact('extracurriculars', 'myRegistrations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_code' => 'required|string|exists:extracurriculars,class_code'
        ], [
            'class_code.exists' => 'Kode Kelas tidak valid atau tidak ditemukan.'
        ]);

        $user = Auth::user();

        // Check if student profile is complete
        if (!$user->studentProfile) {
            return redirect()->route('student.profile.index')->with('error', 'Silakan lengkapi profil terlebih dahulu.');
        }
        
        $extracurricular = Extracurricular::where('class_code', $request->class_code)->first();

        ExtracurricularRegistration::firstOrCreate([
            'student_id' => $user->id,
            'extracurricular_id' => $extracurricular->id
        ], [
            'status' => 'pending'
        ]);

        return redirect()->route('student.extracurriculars.index')->with('success', 'Berhasil mendaftar. Menunggu persetujuan pembina.');
    }
}
