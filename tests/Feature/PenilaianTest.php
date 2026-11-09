<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\AssessmentComponent;
use App\Models\AssessmentScore;
use App\Models\Extracurricular;
use App\Models\ExtracurricularRegistration;
use App\Models\User;
use App\Services\PenilaianService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenilaianTest extends TestCase
{
    use RefreshDatabase;

    private function guru(): User
    {
        return User::factory()->create(['role' => 'teacher', 'status' => 'active']);
    }

    private function siswa(): User
    {
        return User::factory()->create(['role' => 'student', 'status' => 'active']);
    }

    private function ekskul(User $guru): Extracurricular
    {
        return Extracurricular::create([
            'name' => 'Basket',
            'slug' => 'basket-' . uniqid(),
            'teacher_id' => $guru->id,
            'created_by' => $guru->id,
            'status' => 'active',
        ]);
    }

    private function kolom(Extracurricular $ekskul, string $nama, float $bobot, int $urutan): AssessmentComponent
    {
        return AssessmentComponent::create([
            'extracurricular_id' => $ekskul->id,
            'name' => $nama,
            'weight' => $bobot,
            'display_order' => $urutan,
            'status' => 'active',
        ]);
    }

    private function pendaftaran(User $siswa, Extracurricular $ekskul): ExtracurricularRegistration
    {
        return ExtracurricularRegistration::create([
            'student_id' => $siswa->id,
            'extracurricular_id' => $ekskul->id,
            'status' => 'approved',
        ]);
    }

    public function test_kolom_bisa_ditambah_diubah_namanya_dan_dihapus_kecuali_kolom_pertama(): void
    {
        $guru = $this->guru();
        $ekskul = $this->ekskul($guru);
        $dasar = $this->kolom($ekskul, 'Nilai 1', 0, 1);

        $this->actingAs($guru)->postJson(route('teacher.grading.components.store'), [
            'extracurricular_id' => $ekskul->id,
            'name' => 'Penilaian Harian',
        ])->assertOk()->assertJsonPath('komponen.name', 'Penilaian Harian');

        $tambahan = AssessmentComponent::where('name', 'Penilaian Harian')->firstOrFail();
        $this->assertSame(2, $tambahan->display_order);

        $this->actingAs($guru)->patchJson(route('teacher.grading.components.update', $tambahan), [
            'name' => 'Ujian Tengah',
        ])->assertOk()->assertJsonPath('komponen.name', 'Ujian Tengah');

        $this->actingAs($guru)->deleteJson(route('teacher.grading.components.destroy', $tambahan))
            ->assertOk();
        $this->assertDatabaseMissing('assessment_components', ['id' => $tambahan->id]);

        // Kolom pertama tidak bisa dihapus
        $this->actingAs($guru)->deleteJson(route('teacher.grading.components.destroy', $dasar))
            ->assertStatus(422);
        $this->assertDatabaseHas('assessment_components', ['id' => $dasar->id]);
    }

    public function test_kolom_yang_sudah_berisi_nilai_wajib_konfirmasi_ganda(): void
    {
        $guru = $this->guru();
        $ekskul = $this->ekskul($guru);
        $this->kolom($ekskul, 'Nilai 1', 0, 1);
        $kedua = $this->kolom($ekskul, 'Nilai 2', 0, 2);

        $reg = $this->pendaftaran($this->siswa(), $ekskul);
        $this->actingAs($guru)->putJson(route('teacher.grading.scores.update'), [
            'registration_id' => $reg->id,
            'component_id' => $kedua->id,
            'score' => 80,
        ])->assertOk();

        // Tanpa konfirmasi ganda, penghapusan ditolak
        $this->actingAs($guru)->deleteJson(route('teacher.grading.components.destroy', $kedua))
            ->assertStatus(422);
        $this->assertDatabaseHas('assessment_components', ['id' => $kedua->id]);

        // Dengan konfirmasi ganda, kolom dan nilainya terhapus
        $this->actingAs($guru)->deleteJson(route('teacher.grading.components.destroy', $kedua), [
            'konfirmasi_ganda' => true,
        ])->assertOk();
        $this->assertDatabaseMissing('assessment_components', ['id' => $kedua->id]);
        $this->assertDatabaseMissing('assessment_scores', ['assessment_component_id' => $kedua->id]);
    }

    public function test_nilai_kosong_menghapus_sel_dan_guru_lain_ditolak(): void
    {
        $guru = $this->guru();
        $ekskul = $this->ekskul($guru);
        $kolom = $this->kolom($ekskul, 'Nilai 1', 0, 1);
        $reg = $this->pendaftaran($this->siswa(), $ekskul);

        $this->actingAs($guru)->putJson(route('teacher.grading.scores.update'), [
            'registration_id' => $reg->id, 'component_id' => $kolom->id, 'score' => 75,
        ])->assertOk();
        $this->assertDatabaseHas('assessment_scores', ['assessment_component_id' => $kolom->id, 'score' => 75]);

        $this->actingAs($guru)->putJson(route('teacher.grading.scores.update'), [
            'registration_id' => $reg->id, 'component_id' => $kolom->id, 'score' => null,
        ])->assertOk();
        $this->assertDatabaseMissing('assessment_scores', ['assessment_component_id' => $kolom->id]);

        $guruLain = $this->guru();
        $this->actingAs($guruLain)->putJson(route('teacher.grading.scores.update'), [
            'registration_id' => $reg->id, 'component_id' => $kolom->id, 'score' => 90,
        ])->assertStatus(403);
    }

    public function test_total_bobot_di_atas_100_ditolak(): void
    {
        $guru = $this->guru();
        $ekskul = $this->ekskul($guru);
        $this->kolom($ekskul, 'Nilai 1', 60, 1);
        $kedua = $this->kolom($ekskul, 'Nilai 2', 30, 2);

        $this->actingAs($guru)->patch(route('teacher.reports.weights.update'), [
            'extracurricular_id' => $ekskul->id,
            'attendance_weight' => 20,
            'weights' => [$kedua->id => 30],
        ])->assertSessionHasErrors('weights');

        $this->assertSame(0.0, (float) $ekskul->fresh()->attendance_weight);
    }

    public function test_nilai_akhir_predikat_dan_status_lulus_dihitung_dari_bobot(): void
    {
        $guru = $this->guru();
        $ekskul = $this->ekskul($guru);
        $ekskul->update(['attendance_weight' => 0]);
        $nilai1 = $this->kolom($ekskul, 'Nilai 1', 60, 1);
        $nilai2 = $this->kolom($ekskul, 'Nilai 2', 40, 2);

        $siswa = $this->siswa();
        $reg = $this->pendaftaran($siswa, $ekskul);
        $assessment = Assessment::create([
            'registration_id' => $reg->id,
            'period_name' => PenilaianService::PERIODE,
            'final_score' => 0,
            'assessed_by' => $guru->id,
            'assessed_at' => now(),
        ]);
        AssessmentScore::create(['assessment_id' => $assessment->id, 'assessment_component_id' => $nilai1->id, 'score' => 90]);
        AssessmentScore::create(['assessment_id' => $assessment->id, 'assessment_component_id' => $nilai2->id, 'score' => 70]);

        // 90 x 60% + 70 x 40% = 82 -> B -> lulus
        $baris = app(PenilaianService::class)->rekapRegistrasi($reg);
        $this->assertSame(82.0, $baris['nilai_akhir']);
        $this->assertSame('B', $baris['predikat']);
        $this->assertTrue($baris['lulus']);

        // Nilai akhir di bawah 50 = E, tidak lulus
        AssessmentScore::where('assessment_component_id', $nilai1->id)->update(['score' => 40]);
        AssessmentScore::where('assessment_component_id', $nilai2->id)->update(['score' => 30]);
        $baris = app(PenilaianService::class)->rekapRegistrasi($reg);
        $this->assertSame('E', $baris['predikat']);
        $this->assertFalse($baris['lulus']);

        // Predikat D juga tidak lulus
        $this->assertSame('D', PenilaianService::predikat(55));
        $this->assertFalse(PenilaianService::lulus('D'));
    }

    public function test_predikat_belum_muncul_sebelum_total_bobot_100(): void
    {
        $guru = $this->guru();
        $ekskul = $this->ekskul($guru);
        $ekskul->update(['attendance_weight' => 0]);
        $nilai1 = $this->kolom($ekskul, 'Nilai 1', 50, 1);

        $siswa = $this->siswa();
        $reg = $this->pendaftaran($siswa, $ekskul);
        $assessment = Assessment::create([
            'registration_id' => $reg->id,
            'period_name' => PenilaianService::PERIODE,
            'final_score' => 0,
            'assessed_by' => $guru->id,
            'assessed_at' => now(),
        ]);
        AssessmentScore::create(['assessment_id' => $assessment->id, 'assessment_component_id' => $nilai1->id, 'score' => 95]);

        // Bobot baru 50%: nilai akhir tampil, tapi predikat dan lulus belum ditentukan
        $baris = app(PenilaianService::class)->rekapRegistrasi($reg);
        $this->assertSame(47.5, $baris['nilai_akhir']);
        $this->assertFalse($baris['bobot_lengkap']);
        $this->assertNull($baris['predikat']);
        $this->assertNull($baris['lulus']);
    }

    public function test_halaman_penilaian_membuat_kolom_nilai_1_jika_belum_ada(): void
    {
        $guru = $this->guru();
        $ekskul = $this->ekskul($guru);

        $this->actingAs($guru)
            ->get(route('teacher.grading.index', ['ekskul_id' => $ekskul->id]))
            ->assertOk();

        $this->assertDatabaseHas('assessment_components', [
            'extracurricular_id' => $ekskul->id,
            'name' => 'Nilai 1',
            'display_order' => 1,
        ]);
    }

    public function test_batas_predikat_bisa_diatur_per_ekskul(): void
    {
        $guru = $this->guru();
        $ekskul = $this->ekskul($guru);
        $ekskul->update(['attendance_weight' => 0]);
        $nilai1 = $this->kolom($ekskul, 'Nilai 1', 100, 1);

        $siswa = $this->siswa();
        $reg = $this->pendaftaran($siswa, $ekskul);
        $assessment = Assessment::create([
            'registration_id' => $reg->id,
            'period_name' => PenilaianService::PERIODE,
            'final_score' => 0,
            'assessed_by' => $guru->id,
            'assessed_at' => now(),
        ]);
        AssessmentScore::create(['assessment_id' => $assessment->id, 'assessment_component_id' => $nilai1->id, 'score' => 80]);

        // Batas bawaan: 80 masuk B
        $this->assertSame('B', app(PenilaianService::class)->rekapRegistrasi($reg)['predikat']);

        // Guru menurunkan batas A menjadi 80 lewat halaman Hasil
        $this->actingAs($guru)->patch(route('teacher.reports.predicates.update'), [
            'extracurricular_id' => $ekskul->id,
            'batas_a' => 80,
            'batas_b' => 70,
            'batas_c' => 60,
            'batas_d' => 50,
        ])->assertSessionHasNoErrors();

        $baris = app(PenilaianService::class)->rekapRegistrasi($reg->fresh(['extracurricular']));
        $this->assertSame('A', $baris['predikat']);
        $this->assertTrue($baris['lulus']);
    }

    public function test_batas_predikat_harus_berurutan(): void
    {
        $guru = $this->guru();
        $ekskul = $this->ekskul($guru);

        $this->actingAs($guru)->patch(route('teacher.reports.predicates.update'), [
            'extracurricular_id' => $ekskul->id,
            'batas_a' => 70,
            'batas_b' => 75,
            'batas_c' => 65,
            'batas_d' => 50,
        ])->assertSessionHasErrors('batas');

        $this->assertSame(85.0, (float) $ekskul->fresh()->batas_a);
    }

    public function test_siswa_hanya_melihat_penilaian_miliknya(): void
    {
        $guru = $this->guru();
        $ekskul = $this->ekskul($guru);
        $this->kolom($ekskul, 'Nilai 1', 100, 1);

        $siswa = $this->siswa();
        $this->pendaftaran($siswa, $ekskul);

        $this->actingAs($siswa)->get(route('student.assessments.index'))
            ->assertOk()
            ->assertSee('Basket')
            ->assertSee('Belum dinilai');
    }
}
