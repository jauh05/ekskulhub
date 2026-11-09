<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentComponent;
use App\Models\AssessmentScore;
use App\Models\Extracurricular;
use App\Models\ExtracurricularRegistration;
use App\Services\PenilaianService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherGradingController extends Controller
{
    public function __construct(private PenilaianService $penilaian)
    {
    }

    public function index(Request $request)
    {
        $ekskuls = Auth::user()->taughtExtracurriculars()->orderBy('name')->get();
        $ekskul = $this->ekskulDipilih($request, $ekskuls);

        if ($ekskul) {
            $this->penilaian->pastikanKomponenDasar($ekskul);
        }

        $komponen = $ekskul ? $this->penilaian->komponen($ekskul) : collect();
        $baris = $ekskul ? $this->penilaian->rekapEkskul($ekskul) : collect();

        return view('teacher.grading.index', compact('ekskuls', 'ekskul', 'komponen', 'baris'));
    }

    /** Tambah kolom nilai baru di sebelah kanan kolom terakhir. */
    public function storeComponent(Request $request)
    {
        $data = $request->validate([
            'extracurricular_id' => 'required|integer|exists:extracurriculars,id',
            'name' => 'required|string|max:40',
        ]);

        $ekskul = Extracurricular::findOrFail($data['extracurricular_id']);
        $this->pastikanMilikGuru($ekskul);

        $komponen = AssessmentComponent::create([
            'extracurricular_id' => $ekskul->id,
            'name' => trim($data['name']),
            'weight' => 0,
            'display_order' => (int) $ekskul->assessmentComponents()->max('display_order') + 1,
            'status' => 'active',
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Kolom nilai ditambahkan.',
            'komponen' => ['id' => $komponen->id, 'name' => $komponen->name],
        ]);
    }

    /** Ganti nama kolom. Nama boleh diubah untuk semua kolom. */
    public function updateComponent(Request $request, AssessmentComponent $component)
    {
        $this->pastikanMilikGuru($component->extracurricular);

        $data = $request->validate(['name' => 'required|string|max:40']);
        $component->update(['name' => trim($data['name'])]);

        return response()->json([
            'ok' => true,
            'message' => 'Nama kolom diperbarui.',
            'komponen' => ['id' => $component->id, 'name' => $component->name],
        ]);
    }

    /**
     * Hapus kolom. Kolom pertama tidak bisa dihapus.
     * Kalau kolom sudah berisi nilai, backend mewajibkan konfirmasi ganda (flag konfirmasi_ganda).
     */
    public function destroyComponent(Request $request, AssessmentComponent $component)
    {
        $ekskul = $component->extracurricular;
        $this->pastikanMilikGuru($ekskul);

        $dasar = $this->penilaian->komponenDasar($ekskul);
        if ($dasar && $dasar->id === $component->id) {
            return response()->json(['message' => 'Kolom nilai pertama tidak bisa dihapus.'], 422);
        }

        $terisi = AssessmentScore::where('assessment_component_id', $component->id)
            ->whereNotNull('score')
            ->count();

        if ($terisi > 0 && ! $request->boolean('konfirmasi_ganda')) {
            return response()->json([
                'message' => "Kolom ini sudah berisi {$terisi} nilai. Konfirmasi ganda diperlukan.",
                'terisi' => $terisi,
            ], 422);
        }

        $component->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Kolom nilai dihapus.',
            'terisi' => $terisi,
        ]);
    }

    /** Simpan satu sel nilai. Nilai kosong menghapus sel. */
    public function updateScore(Request $request)
    {
        $data = $request->validate([
            'registration_id' => 'required|integer|exists:extracurricular_registrations,id',
            'component_id' => 'required|integer|exists:assessment_components,id',
            'score' => 'nullable|numeric|min:0|max:100',
        ]);

        $reg = ExtracurricularRegistration::with('extracurricular')->findOrFail($data['registration_id']);
        $komponen = AssessmentComponent::findOrFail($data['component_id']);

        if ($komponen->extracurricular_id !== $reg->extracurricular_id) {
            return response()->json(['message' => 'Kolom tidak sesuai dengan ekstrakurikuler siswa.'], 422);
        }
        if ($reg->status !== 'approved') {
            return response()->json(['message' => 'Hanya siswa yang sudah disetujui yang bisa dinilai.'], 422);
        }
        $this->pastikanMilikGuru($reg->extracurricular);

        $assessment = Assessment::firstOrCreate(
            ['registration_id' => $reg->id, 'period_name' => PenilaianService::PERIODE],
            ['final_score' => 0, 'assessed_by' => Auth::id(), 'assessed_at' => now()],
        );

        $kunci = ['assessment_id' => $assessment->id, 'assessment_component_id' => $komponen->id];

        if ($data['score'] === null) {
            AssessmentScore::where($kunci)->delete();
        } else {
            AssessmentScore::updateOrCreate($kunci, ['score' => $data['score']]);
        }

        $assessment->update(['assessed_by' => Auth::id(), 'assessed_at' => now()]);

        $baris = $this->penilaian->rekapEkskul($reg->extracurricular)->firstWhere('registration_id', $reg->id);

        return response()->json(['ok' => true, 'baris' => $baris]);
    }

    private function ekskulDipilih(Request $request, $ekskuls): ?Extracurricular
    {
        if ($request->filled('ekskul_id')) {
            return $ekskuls->firstWhere('id', (int) $request->ekskul_id) ?? $ekskuls->first();
        }

        return $ekskuls->first();
    }

    private function pastikanMilikGuru(Extracurricular $ekskul): void
    {
        abort_unless(Auth::user()->taughtExtracurriculars()->whereKey($ekskul->id)->exists(), 403);
    }
}
