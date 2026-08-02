<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\ExtracurricularRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherGradingController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user();
        $ekskuls = $teacher->taughtExtracurriculars;
        $ekskulIds = $ekskuls->pluck('id');
        
        $query = Assessment::with(['registration.student', 'registration.extracurricular'])
            ->whereHas('registration', function($q) use ($ekskulIds, $request) {
                $q->whereIn('extracurricular_id', $ekskulIds);
                if ($request->filled('ekskul_id')) {
                    $q->where('extracurricular_id', $request->ekskul_id);
                }
            });
            
        if ($request->filled('period_name')) {
            $query->where('period_name', 'like', '%' . $request->period_name . '%');
        }
            
        // Calculate Stats
        $totalDinilai = (clone $query)->count();
        $rataRataNilai = $totalDinilai > 0 ? round((clone $query)->avg('final_score'), 1) : 0;
        
        $predikatA = (clone $query)->where('predicate', 'A')->count();
        $predikatB = (clone $query)->where('predicate', 'B')->count();
        $predikatC = (clone $query)->where('predicate', 'C')->count();
        
        $assessments = $query->latest()->paginate(15)->withQueryString();
            
        return view('teacher.grading.index', compact(
            'assessments', 'ekskuls', 'totalDinilai', 'rataRataNilai', 'predikatA', 'predikatB', 'predikatC'
        ));
    }

    public function create()
    {
        $teacher = Auth::user();
        $ekskulIds = $teacher->taughtExtracurriculars()->pluck('id');
        $registrations = ExtracurricularRegistration::with(['student', 'extracurricular'])
            ->whereIn('extracurricular_id', $ekskulIds)
            ->where('status', 'approved')
            ->get();
            
        return view('teacher.grading.create', compact('registrations'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:extracurricular_registrations,id',
            'period_name' => 'required|string',
            'final_score' => 'required|numeric|min:0|max:100',
            'predicate' => 'required|string|max:2',
            'notes' => 'nullable|string'
        ]);
        
        Assessment::updateOrCreate(
            [
                'registration_id' => $request->registration_id,
                'period_name' => $request->period_name
            ],
            [
                'final_score' => $request->final_score,
                'predicate' => $request->predicate,
                'notes' => $request->notes,
                'assessed_by' => Auth::id(),
                'assessed_at' => now(),
            ]
        );
        
        return redirect()->route('teacher.grading.index')->with('success', 'Penilaian berhasil disimpan');
    }
}
