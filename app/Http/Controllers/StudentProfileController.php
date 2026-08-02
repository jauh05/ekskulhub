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
        return view('student.profile.index', compact('user', 'profile'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|max:50',
            'class' => 'required|string|max:20',
            'gender' => 'required|in:male,female',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        
        StudentProfile::updateOrCreate(
            ['user_id' => $user->id],
            $request->all()
        );

        return redirect()->route('student.dashboard')->with('success', 'Profil berhasil diperbarui');
    }
}
