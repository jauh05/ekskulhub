<?php

namespace App\Http\Controllers;

use App\Models\Extracurricular;
use App\Services\PenilaianService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class TeacherReportController extends Controller
{
    public function __construct(private PenilaianService $penilaian)
    {
    }

    public function index(Request $request)
    {
        $ekskuls = Auth::user()->taughtExtracurriculars()->orderBy('name')->get();
        $ekskul = $this->ekskulDipilih($request, $ekskuls);

        $komponen = $ekskul ? $this->penilaian->komponen($ekskul) : collect();
        $baris = $ekskul ? $this->penilaian->rekapEkskul($ekskul) : collect();
        $totalBobot = $ekskul ? PenilaianService::totalBobot($ekskul, $komponen) : 0;

        $ringkasan = [
            'siswa' => $baris->count(),
            'sudah_dinilai' => $baris->where('sudah_dinilai', true)->count(),
            'lulus' => $baris->where('lulus', true)->count(),
            'tidak_lulus' => $baris->where('lulus', false)->count(),
            'rata_rata' => $baris->whereNotNull('nilai_akhir')->avg('nilai_akhir'),
        ];

        $batasPredikat = $ekskul ? PenilaianService::batasPredikat($ekskul) : PenilaianService::BATAS_PREDIKAT;

        return view('teacher.reports.index', compact('ekskuls', 'ekskul', 'komponen', 'baris', 'totalBobot', 'ringkasan', 'batasPredikat'));
    }

    /** Simpan pembagian persen: bobot kehadiran dan bobot tiap kolom nilai. Total tidak boleh lebih dari 100. */
    public function updateWeights(Request $request)
    {
        $data = $request->validate([
            'extracurricular_id' => 'required|integer|exists:extracurriculars,id',
            'attendance_weight' => 'required|numeric|min:0|max:100',
            'weights' => 'nullable|array',
            'weights.*' => 'nullable|numeric|min:0|max:100',
        ]);

        $ekskul = Extracurricular::findOrFail($data['extracurricular_id']);
        abort_unless(Auth::user()->taughtExtracurriculars()->whereKey($ekskul->id)->exists(), 403);

        $komponen = $this->penilaian->komponen($ekskul);
        $bobotBaru = collect($data['weights'] ?? [])->map(fn ($nilai) => $nilai === null ? null : (float) $nilai);

        $total = (float) $data['attendance_weight'] + $komponen->sum(
            fn ($c) => (float) ($bobotBaru->get($c->id) ?? $c->weight)
        );

        if (round($total, 2) > 100) {
            return back()
                ->withErrors(['weights' => 'Total pembagian nilai ' . number_format($total, 2) . '% melebihi 100%.'])
                ->withInput();
        }

        DB::transaction(function () use ($ekskul, $data, $komponen, $bobotBaru) {
            $ekskul->update(['attendance_weight' => $data['attendance_weight']]);

            foreach ($komponen as $c) {
                $nilai = $bobotBaru->get($c->id);
                if ($nilai !== null) {
                    $c->update(['weight' => $nilai]);
                }
            }
        });

        return redirect()
            ->route('teacher.reports.index', ['ekskul_id' => $ekskul->id])
            ->with('success', 'Pembagian nilai disimpan.');
    }

    /** Simpan batas predikat A, B, C, dan D. Batas harus berurutan: A > B > C > D. */
    public function updatePredicates(Request $request)
    {
        $data = $request->validate([
            'extracurricular_id' => 'required|integer|exists:extracurriculars,id',
            'batas_a' => 'required|numeric|min:0|max:100',
            'batas_b' => 'required|numeric|min:0|max:100',
            'batas_c' => 'required|numeric|min:0|max:100',
            'batas_d' => 'required|numeric|min:0|max:100',
        ]);

        $ekskul = Extracurricular::findOrFail($data['extracurricular_id']);
        abort_unless(Auth::user()->taughtExtracurriculars()->whereKey($ekskul->id)->exists(), 403);

        if (! ($data['batas_a'] > $data['batas_b'] && $data['batas_b'] > $data['batas_c'] && $data['batas_c'] > $data['batas_d'])) {
            return back()
                ->withErrors(['batas' => 'Batas harus berurutan: A lebih besar dari B, B lebih besar dari C, dan C lebih besar dari D.'])
                ->withInput();
        }

        $ekskul->update([
            'batas_a' => $data['batas_a'],
            'batas_b' => $data['batas_b'],
            'batas_c' => $data['batas_c'],
            'batas_d' => $data['batas_d'],
        ]);

        return redirect()
            ->route('teacher.reports.index', ['ekskul_id' => $ekskul->id])
            ->with('success', 'Batas predikat disimpan.');
    }

    public function export(Request $request)
    {
        $ekskuls = Auth::user()->taughtExtracurriculars()->orderBy('name')->get();
        $ekskul = $this->ekskulDipilih($request, $ekskuls);
        abort_unless($ekskul, 404);

        $komponen = $this->penilaian->komponen($ekskul);
        $baris = $this->penilaian->rekapEkskul($ekskul);

        $filename = 'rekap_nilai_' . str_replace(' ', '_', strtolower($ekskul->name)) . '_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($baris, $komponen) {
            $file = fopen('php://output', 'w');

            $judul = ['No', 'Nama', 'Kelas', 'Kehadiran (%)'];
            foreach ($komponen as $c) {
                $judul[] = $c->name . ' (bobot ' . rtrim(rtrim(number_format((float) $c->weight, 2, '.', ''), '0'), '.') . '%)';
            }
            $judul = array_merge($judul, ['Nilai Akhir', 'Predikat', 'Status']);
            fputcsv($file, $judul);

            foreach ($baris as $no => $row) {
                $isi = [$no + 1, $row['nama'], $row['kelas'], $row['persen_hadir']];
                foreach ($komponen as $c) {
                    $isi[] = $row['nilai'][$c->id] ?? '';
                }
                $isi[] = $row['nilai_akhir'] ?? '';
                $isi[] = $row['predikat'] ?? '';
                $isi[] = $row['lulus'] === null ? 'Belum dinilai' : ($row['lulus'] ? 'Lulus' : 'Tidak lulus');
                fputcsv($file, $isi);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function ekskulDipilih(Request $request, $ekskuls): ?Extracurricular
    {
        if ($request->filled('ekskul_id')) {
            return $ekskuls->firstWhere('id', (int) $request->ekskul_id) ?? $ekskuls->first();
        }

        return $ekskuls->first();
    }
}
