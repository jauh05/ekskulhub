<?php

namespace App\Http\Controllers;

use App\Models\Extracurricular;
use App\Services\PenilaianService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Nilai');

        $judul = ['No', 'Nama', 'Kelas', 'Kehadiran'];
        foreach ($komponen as $c) {
            $judul[] = $c->name . ' (' . $this->angkaRapi($c->weight) . '%)';
        }
        $judul = array_merge($judul, ['Nilai Akhir', 'Predikat', 'Status']);
        $kolomAkhir = Coordinate::stringFromColumnIndex(count($judul));

        // Judul laporan
        $sheet->mergeCells("A1:{$kolomAkhir}1");
        $sheet->setCellValue('A1', 'Rekap Nilai ' . $ekskul->name);
        $sheet->getStyle("A1:{$kolomAkhir}1")->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '164E2E']],
            'alignment' => ['vertical' => 'center', 'indent' => 1],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        $sheet->mergeCells("A2:{$kolomAkhir}2");
        $sheet->setCellValue('A2', 'Dicetak ' . now()->format('d/m/Y H:i') . '   ·   Total bobot ' . $this->angkaRapi(PenilaianService::totalBobot($ekskul, $komponen)) . '%');
        $sheet->getStyle("A2:{$kolomAkhir}2")->applyFromArray([
            'font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '64748B']],
        ]);

        // Header tabel
        foreach ($judul as $i => $teks) {
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($i + 1) . '3', $teks);
        }
        $sheet->getStyle("A3:{$kolomAkhir}3")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1F7A45']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(36);

        // Isi tabel
        $baris_ke = 4;
        foreach ($baris as $no => $row) {
            $isi = [$no + 1, $row['nama'], $row['kelas'], $row['persen_hadir'] / 100];
            foreach ($komponen as $c) {
                $isi[] = $row['nilai'][$c->id] ?? null;
            }
            $isi[] = $row['nilai_akhir'];
            $isi[] = $row['predikat'];
            $isi[] = $row['lulus'] === null ? 'Belum dinilai' : ($row['lulus'] ? 'Lulus' : 'Tidak lulus');

            foreach ($isi as $i => $nilai) {
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($i + 1) . $baris_ke, $nilai ?? '');
            }

            // Baris bergantian (diberi lebih dulu supaya warna status di bawahnya tidak tertimpa)
            if ($baris_ke % 2 === 1) {
                $sheet->getStyle("A{$baris_ke}:{$kolomAkhir}{$baris_ke}")->applyFromArray([
                    'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'F1F5F9']],
                ]);
            }

            // Status berwarna: hijau untuk lulus, merah untuk tidak lulus
            $sel = $kolomAkhir . $baris_ke;
            if ($row['lulus'] === true) {
                $sheet->getStyle($sel)->applyFromArray(['font' => ['bold' => true, 'color' => ['rgb' => '166534']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'DCFCE7']]]);
            } elseif ($row['lulus'] === false) {
                $sheet->getStyle($sel)->applyFromArray(['font' => ['bold' => true, 'color' => ['rgb' => 'B91C1C']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FEE2E2']]]);
            } else {
                $sheet->getStyle($sel)->applyFromArray(['font' => ['color' => ['rgb' => '64748B']]]);
            }

            $baris_ke++;
        }

        $akhirData = $baris_ke - 1;
        if ($akhirData >= 4) {
            // Border di seluruh tabel
            $sheet->getStyle("A3:{$kolomAkhir}{$akhirData}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => 'CBD5E1']]],
            ]);
            $sheet->getStyle("A4:{$kolomAkhir}{$akhirData}")->applyFromArray([
                'alignment' => ['vertical' => 'center'],
            ]);
            $sheet->getStyle("A4:A{$akhirData}")->getAlignment()->setHorizontal('center');
            $sheet->getStyle("C4:{$kolomAkhir}{$akhirData}")->getAlignment()->setHorizontal('center');
            $sheet->getStyle("B4:B{$akhirData}")->getAlignment()->setHorizontal('left');

            // Format angka: kehadiran persen, nilai dua desimal
            $sheet->getStyle("D4:D{$akhirData}")->getNumberFormat()->setFormatCode('0.0%');
            $kolomNilaiPertama = 5;
            $kolomNilaiTerakhir = $kolomNilaiPertama + count($komponen) - 1 + 1; // termasuk Nilai Akhir
            for ($i = $kolomNilaiPertama; $i <= $kolomNilaiTerakhir; $i++) {
                $sheet->getStyle(Coordinate::stringFromColumnIndex($i) . "4:" . Coordinate::stringFromColumnIndex($i) . $akhirData)
                    ->getNumberFormat()->setFormatCode('0.00');
            }
        }

        // Lebar kolom
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(28);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(12);
        for ($i = 5; $i <= count($judul); $i++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setWidth($i >= count($judul) - 2 ? 16 : 18);
        }

        // Header tetap terlihat saat digulir
        $sheet->freezePane('C4');

        $namaFile = 'rekap_nilai_' . Str::slug($ekskul->name, '_') . '_' . now()->format('Ymd_His') . '.xlsx';
        $penulis = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($penulis) {
            $penulis->save('php://output');
        }, $namaFile, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /** Angka tanpa nol di belakang koma, misalnya 40 atau 12.5. */
    private function angkaRapi($nilai): string
    {
        return rtrim(rtrim(number_format((float) $nilai, 2, '.', ''), '0'), '.');
    }

    private function ekskulDipilih(Request $request, $ekskuls): ?Extracurricular
    {
        if ($request->filled('ekskul_id')) {
            return $ekskuls->firstWhere('id', (int) $request->ekskul_id) ?? $ekskuls->first();
        }

        return $ekskuls->first();
    }
}
