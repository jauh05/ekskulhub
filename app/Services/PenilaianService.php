<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\AssessmentComponent;
use App\Models\Attendance;
use App\Models\Extracurricular;
use App\Models\ExtracurricularRegistration;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Satu-satunya tempat yang menghitung nilai akhir, predikat, dan status lulus.
 *
 * Nilai akhir = (persen kehadiran x bobot kehadiran + Σ nilai komponen x bobot komponen) / 100.
 * Sel nilai yang kosong dihitung 0. Predikat hanya muncul kalau sudah ada minimal satu nilai.
 */
class PenilaianService
{
    /** Nama periode internal: satu baris penilaian per pendaftaran. */
    public const PERIODE = 'Penilaian';

    /** Nilai akhir minimum untuk tiap predikat. Di bawah D berarti E. */
    public const BATAS_PREDIKAT = ['A' => 85, 'B' => 75, 'C' => 65, 'D' => 50];

    /** Predikat yang dinyatakan lulus. D dan E tidak lulus. */
    public const PREDIKAT_LULUS = ['A', 'B', 'C'];

    /** Batas predikat milik ekstrakurikuler. Kalau kolom kosong, pakai batas bawaan. */
    public static function batasPredikat(Extracurricular $ekskul): array
    {
        return [
            'A' => (float) ($ekskul->batas_a ?? self::BATAS_PREDIKAT['A']),
            'B' => (float) ($ekskul->batas_b ?? self::BATAS_PREDIKAT['B']),
            'C' => (float) ($ekskul->batas_c ?? self::BATAS_PREDIKAT['C']),
            'D' => (float) ($ekskul->batas_d ?? self::BATAS_PREDIKAT['D']),
        ];
    }

    public static function predikat(float $nilai, array $batas = self::BATAS_PREDIKAT): string
    {
        foreach ($batas as $huruf => $minimal) {
            if ($nilai >= $minimal) {
                return $huruf;
            }
        }

        return 'E';
    }

    public static function lulus(string $predikat): bool
    {
        return in_array($predikat, self::PREDIKAT_LULUS, true);
    }

    /** Total bobot (kehadiran + semua komponen). Harus tidak lebih dari 100. */
    public static function totalBobot(Extracurricular $ekskul, Collection $komponen): float
    {
        return round((float) $ekskul->attendance_weight + (float) $komponen->sum('weight'), 2);
    }

    /** Komponen nilai sesuai urutan tampilan. */
    public function komponen(Extracurricular $ekskul): Collection
    {
        return $ekskul->assessmentComponents()->orderBy('display_order')->orderBy('id')->get();
    }

    /** Kolom pertama. Kolom ini tidak boleh dihapus. */
    public function komponenDasar(Extracurricular $ekskul): ?AssessmentComponent
    {
        return $this->komponen($ekskul)->first();
    }

    /** Pastikan ekstrakurikuler punya kolom dasar "Nilai 1". Idempotent: tidak membuat ulang kalau sudah ada. */
    public function pastikanKomponenDasar(Extracurricular $ekskul): AssessmentComponent
    {
        return $this->komponenDasar($ekskul) ?? AssessmentComponent::create([
            'extracurricular_id' => $ekskul->id,
            'name' => 'Nilai 1',
            'weight' => 0,
            'display_order' => 1,
            'status' => 'active',
        ]);
    }

    /** Rekap semua siswa yang sudah disetujui di satu ekskul, diurutkan berdasarkan nama. */
    public function rekapEkskul(Extracurricular $ekskul): Collection
    {
        $komponen = $this->komponen($ekskul);
        $registrasi = ExtracurricularRegistration::with('student.studentProfile')
            ->where('extracurricular_id', $ekskul->id)
            ->where('status', 'approved')
            ->get()
            ->sortBy(fn ($reg) => mb_strtolower($reg->student->name ?? ''))
            ->values();

        $jadwalIds = $this->jadwalSelesai($ekskul);
        $hadir = $this->hadirPerSiswa($jadwalIds, $registrasi->pluck('student_id'));
        $penilaian = $this->penilaianUntuk($registrasi->pluck('id'));

        return $registrasi->map(fn ($reg) => $this->susunBaris(
            $reg,
            $ekskul,
            $komponen,
            $jadwalIds->count(),
            (int) ($hadir[$reg->student_id] ?? 0),
            $penilaian->get($reg->id),
        ))->values();
    }

    /** Rekap satu pendaftaran, dipakai di halaman siswa dan dashboard. */
    public function rekapRegistrasi(ExtracurricularRegistration $reg): array
    {
        $reg->loadMissing('extracurricular', 'student.studentProfile');
        $ekskul = $reg->extracurricular;
        $jadwalIds = $this->jadwalSelesai($ekskul);
        $hadir = $this->hadirPerSiswa($jadwalIds, collect([$reg->student_id]));
        $penilaian = $this->penilaianUntuk(collect([$reg->id]))->get($reg->id);

        return $this->susunBaris(
            $reg,
            $ekskul,
            $this->komponen($ekskul),
            $jadwalIds->count(),
            (int) ($hadir[$reg->student_id] ?? 0),
            $penilaian,
        );
    }

    /** Sesi yang tanggalnya sudah lewat atau hari ini. Sesi yang belum berlangsung tidak dihitung. */
    private function jadwalSelesai(Extracurricular $ekskul): Collection
    {
        return Schedule::where('extracurricular_id', $ekskul->id)
            ->whereDate('activity_date', '<=', Carbon::today())
            ->pluck('id');
    }

    /** Jumlah kehadiran (status present) per siswa, hanya untuk sesi yang dihitung. */
    private function hadirPerSiswa(Collection $jadwalIds, Collection $studentIds): Collection
    {
        if ($jadwalIds->isEmpty() || $studentIds->isEmpty()) {
            return collect();
        }

        return Attendance::whereIn('student_id', $studentIds)
            ->where('status', 'present')
            ->whereHas('attendanceSession', fn ($q) => $q->whereIn('schedule_id', $jadwalIds))
            ->selectRaw('student_id, COUNT(*) as total')
            ->groupBy('student_id')
            ->pluck('total', 'student_id');
    }

    private function penilaianUntuk(Collection $registrationIds): Collection
    {
        return Assessment::with('scores')
            ->where('period_name', self::PERIODE)
            ->whereIn('registration_id', $registrationIds)
            ->get()
            ->keyBy('registration_id');
    }

    private function susunBaris(
        ExtracurricularRegistration $reg,
        Extracurricular $ekskul,
        Collection $komponen,
        int $totalSesi,
        int $jumlahHadir,
        ?Assessment $penilaian,
    ): array {
        $skor = $penilaian ? $penilaian->scores->keyBy('assessment_component_id') : collect();

        $persenHadir = $totalSesi > 0 ? round($jumlahHadir / $totalSesi * 100, 1) : 0.0;
        $bobotHadir = (float) $ekskul->attendance_weight;
        $kontribusiHadir = round($persenHadir * $bobotHadir / 100, 2);

        $nilai = [];
        $sudahDinilai = false;
        $nilaiAkhir = $kontribusiHadir;

        foreach ($komponen as $komponenNilai) {
            $score = $skor->get($komponenNilai->id)?->score;
            $nilai[$komponenNilai->id] = $score !== null ? (float) $score : null;

            if ($score !== null) {
                $sudahDinilai = true;
            }

            $nilaiAkhir += ((float) ($score ?? 0)) * (float) $komponenNilai->weight / 100;
        }

        $nilaiAkhir = round($nilaiAkhir, 2);

        // Predikat dan status lulus hanya dihitung setelah total bobot tepat 100%.
        // Sebelum itu nilai akhir tetap tampil, tapi belum dinyatakan lulus atau tidak.
        $bobotLengkap = round(self::totalBobot($ekskul, $komponen), 2) === 100.0;
        $predikat = ($sudahDinilai && $bobotLengkap)
            ? self::predikat($nilaiAkhir, self::batasPredikat($ekskul))
            : null;

        return [
            'registration_id' => $reg->id,
            'student_id' => $reg->student_id,
            'nama' => $reg->student->name ?? '-',
            'kelas' => $reg->student->studentProfile->class_name ?? '-',
            'ekskul' => $ekskul->name,
            'persen_hadir' => $persenHadir,
            'bobot_hadir' => $bobotHadir,
            'kontribusi_hadir' => $kontribusiHadir,
            'nilai' => $nilai,
            'nilai_akhir' => $sudahDinilai ? $nilaiAkhir : null,
            'predikat' => $predikat,
            'lulus' => $predikat !== null ? self::lulus($predikat) : null,
            'bobot_lengkap' => $bobotLengkap,
            'sudah_dinilai' => $sudahDinilai,
        ];
    }
}
