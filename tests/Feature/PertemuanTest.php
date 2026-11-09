<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Extracurricular;
use App\Models\Schedule;
use App\Models\User;
use App\Services\PertemuanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class PertemuanTest extends TestCase
{
    use RefreshDatabase;

    private function ekskul(User $guru): Extracurricular
    {
        return Extracurricular::create([
            'name' => 'Pramuka',
            'slug' => 'pramuka-' . uniqid(),
            'teacher_id' => $guru->id,
            'created_by' => $guru->id,
            'status' => 'active',
        ]);
    }

    /** Jadwal dengan sesi absensi. Kalau $adaKehadiran true, sesi punya satu catatan kehadiran. */
    private function pertemuan(Extracurricular $ekskul, User $guru, string $tanggal, bool $adaKehadiran): void
    {
        $jadwal = Schedule::create([
            'extracurricular_id' => $ekskul->id,
            'title' => 'Latihan',
            'activity_date' => $tanggal,
            'start_time' => '15:00:00',
            'end_time' => '17:00:00',
            'location' => 'Lapangan',
            'status' => 'completed',
            'created_by' => $guru->id,
        ]);

        $sesi = AttendanceSession::create([
            'schedule_id' => $jadwal->id,
            'status' => 'finished',
            'opened_by' => $guru->id,
            'opened_at' => now(),
        ]);

        if ($adaKehadiran) {
            $siswa = User::factory()->create(['role' => 'student', 'status' => 'active']);
            Attendance::create([
                'attendance_session_id' => $sesi->id,
                'student_id' => $siswa->id,
                'status' => 'present',
                'method' => 'manual',
                'checked_at' => now(),
            ]);
        }
    }

    public function test_pertemuan_per_bulan_hanya_menghitung_jadwal_yang_sudah_ada_absensinya(): void
    {
        $guru = User::factory()->create(['role' => 'teacher', 'status' => 'active']);
        $ekskul = $this->ekskul($guru);

        $this->pertemuan($ekskul, $guru, '2026-03-05', true);
        $this->pertemuan($ekskul, $guru, '2026-03-12', true);
        $this->pertemuan($ekskul, $guru, '2026-03-19', false); // belum diabsen, tidak dihitung
        $this->pertemuan($ekskul, $guru, '2026-07-01', true);
        $this->pertemuan($ekskul, $guru, '2025-03-05', true); // tahun lain, tidak dihitung

        $hasil = PertemuanService::perBulan(collect([$ekskul->id]), 2026);

        $this->assertCount(12, $hasil);
        $this->assertSame(2, $hasil[3]);
        $this->assertSame(1, $hasil[7]);
        $this->assertSame(0, $hasil[1]);
    }

    public function test_pertemuan_per_bulan_kosong_jika_tidak_ada_ekskul(): void
    {
        $hasil = PertemuanService::perBulan(collect(), 2026);

        $this->assertSame(array_fill(1, 12, 0), $hasil);
    }

    public function test_halaman_absensi_dan_dashboard_guru_menampilkan_pertemuan_per_bulan(): void
    {
        $guru = User::factory()->create(['role' => 'teacher', 'status' => 'active']);
        $ekskul = $this->ekskul($guru);
        $this->pertemuan($ekskul, $guru, now()->format('Y-m-d'), true);

        $this->actingAs($guru)->get(route('teacher.dashboard'))
            ->assertOk()
            ->assertSee('Pertemuan per bulan');

        $this->actingAs($guru)->get(route('teacher.attendances.index'))
            ->assertOk()
            ->assertSee('Pertemuan per bulan');
    }
}
