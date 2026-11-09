<?php

namespace App\Http\Controllers;

use App\Models\ExtracurricularRegistration;
use App\Services\PenilaianService;
use Illuminate\Support\Facades\Auth;

class StudentAssessmentController extends Controller
{
    public function __construct(private PenilaianService $penilaian)
    {
    }

    public function index()
    {
        $registrasi = ExtracurricularRegistration::with('extracurricular')
            ->where('student_id', Auth::id())
            ->where('status', 'approved')
            ->get();

        $penilaian = $registrasi->map(fn ($reg) => [
            'ekskul' => $reg->extracurricular,
            'komponen' => $this->penilaian->komponen($reg->extracurricular),
            'baris' => $this->penilaian->rekapRegistrasi($reg),
        ]);

        return view('student.assessments.index', compact('penilaian'));
    }
}
