<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->studentProfile;
        $schools = \App\Models\TeacherProfile::whereNotNull('school_name')
            ->distinct()
            ->pluck('school_name');
        return view('student.profile.index', compact('user', 'profile', 'schools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'class_name' => 'required|string|max:20',
            'gender' => 'required|in:male,female',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        
        StudentProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'school_name' => $request->school_name,
                'class_name' => $request->class_name,
                'gender' => $request->gender,
                'parent_name' => $request->parent_name,
                'parent_phone' => $request->parent_phone,
            ]
        );

        return redirect()->route('student.dashboard')->with('success', 'Profil berhasil diperbarui');
    }
}
