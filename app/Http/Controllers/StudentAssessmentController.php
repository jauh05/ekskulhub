<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAssessmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $assessments = Assessment::with('registration.extracurricular')
            ->whereHas('registration', function($query) use ($user) {
                $query->where('student_id', $user->id);
            })
            ->latest()
            ->paginate(15);
            
        return view('student.assessments.index', compact('assessments'));
    }
}
