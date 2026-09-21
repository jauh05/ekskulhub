<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Extracurricular;
use App\Models\ExtracurricularRegistration;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttendanceConsistencyTest extends TestCase
{
    use RefreshDatabase;

    private User $teacher;
    private User $student;
    private Extracurricular $extracurricular;
    private Schedule $schedule;
    private AttendanceSession $session;

    protected function setUp(): void
    {
        parent::setUp();

        $this->teacher = User::factory()->create(['role' => 'teacher', 'status' => 'active']);
        $this->student = User::factory()->create(['role' => 'student', 'status' => 'active']);
        $this->extracurricular = Extracurricular::create([
            'name' => 'Basket',
            'slug' => 'basket',
            'teacher_id' => $this->teacher->id,
            'created_by' => $this->teacher->id,
        ]);
        ExtracurricularRegistration::create([
            'student_id' => $this->student->id,
            'extracurricular_id' => $this->extracurricular->id,
            'status' => 'approved',
        ]);
        $this->schedule = Schedule::create([
            'extracurricular_id' => $this->extracurricular->id,
            'title' => 'Latihan',
            'activity_date' => today(),
            'start_time' => '08:00',
            'end_time' => '10:00',
            'location' => 'Lapangan',
            'created_by' => $this->teacher->id,
        ]);
        $this->session = AttendanceSession::create([
            'schedule_id' => $this->schedule->id,
            'status' => 'open',
            'opened_by' => $this->teacher->id,
            'opened_at' => now(),
            'qr_secret_hash' => '123456',
            'session_code' => 'ABC123',
        ]);

        Storage::fake('public');
    }

    public function test_student_submissions_replace_the_latest_state_without_adding_rows(): void
    {
        $this->submitAsStudent([
            'type' => 'hadir',
            'method' => 'qr',
            'qr_code' => json_encode(['secret' => '123456']),
        ]);
        $this->assertLatestState('present', 'qr');

        $this->submitAsStudent([
            'type' => 'izin',
            'reason' => 'Ada kegiatan keluarga',
            'proof' => UploadedFile::fake()->create('izin.pdf', 10, 'application/pdf'),
        ]);
        $permission = $this->assertLatestState('permission', 'manual');
        $this->assertSame('Ada kegiatan keluarga', $permission->notes);
        $this->assertNotNull($permission->proof_file);

        $oldProof = $permission->proof_file;
        $this->submitAsStudent([
            'type' => 'hadir',
            'method' => 'qr',
            'qr_code' => '123456',
        ]);
        $present = $this->assertLatestState('present', 'qr');
        $this->assertNull($present->notes);
        $this->assertNull($present->proof_file);
        Storage::disk('public')->assertMissing($oldProof);

        $this->submitAsStudent([
            'type' => 'hadir',
            'method' => 'selfie',
            'selfie' => UploadedFile::fake()->image('selfie.jpg'),
        ]);
        $selfie = $this->assertLatestState('present', 'selfie');
        $this->assertSame('pending', $selfie->selfie_status);
        $this->assertNotNull($selfie->selfie_path);

        $oldSelfie = $selfie->selfie_path;
        $this->submitAsStudent([
            'type' => 'hadir',
            'method' => 'manual',
            'session_code' => 'abc123',
        ]);
        $manual = $this->assertLatestState('present', 'manual');
        $this->assertNull($manual->selfie_path);
        $this->assertNull($manual->selfie_status);
        Storage::disk('public')->assertMissing($oldSelfie);

        $this->submitAsStudent([
            'type' => 'hadir',
            'method' => 'manual',
            'session_code' => 'ABC123',
        ]);
        $this->assertLatestState('present', 'manual');
    }

    public function test_alpha_can_be_replaced_by_student_qr_manual_selfie_or_permission(): void
    {
        Attendance::create([
            'attendance_session_id' => $this->session->id,
            'student_id' => $this->student->id,
            'status' => 'absent',
            'method' => 'manual',
        ]);

        $this->submitAsStudent([
            'type' => 'hadir',
            'method' => 'qr',
            'qr_code' => '123456',
        ]);
        $this->assertLatestState('present', 'qr');

        Attendance::where('student_id', $this->student->id)->update(['status' => 'absent']);
        $this->submitAsStudent([
            'type' => 'hadir',
            'method' => 'manual',
            'session_code' => 'ABC123',
        ]);
        $this->assertLatestState('present', 'manual');

        Attendance::where('student_id', $this->student->id)->update(['status' => 'absent']);
        $this->submitAsStudent([
            'type' => 'hadir',
            'method' => 'selfie',
            'selfie' => UploadedFile::fake()->image('alpha-selfie.jpg'),
        ]);
        $this->assertLatestState('present', 'selfie');

        Attendance::where('student_id', $this->student->id)->update(['status' => 'absent']);
        $this->submitAsStudent([
            'type' => 'izin',
            'reason' => 'Sakit',
        ]);
        $this->assertLatestState('permission', 'manual');
    }

    public function test_teacher_manual_entry_updates_alpha_and_live_data_stays_unique(): void
    {
        Attendance::create([
            'attendance_session_id' => $this->session->id,
            'student_id' => $this->student->id,
            'status' => 'absent',
            'method' => 'manual',
        ]);

        $this->actingAs($this->teacher)->post(route('teacher.attendances.store'), [
            'schedule_id' => $this->schedule->id,
            'student_id' => $this->student->id,
            'status' => 'present',
            'notes' => 'Hadir manual',
        ])->assertSessionHasNoErrors();

        $attendance = $this->assertLatestState('present', 'manual');
        $this->assertTrue($attendance->is_verified_by_teacher);

        $response = $this->actingAs($this->teacher)
            ->getJson(route('teacher.attendances.live.data', $this->session));

        $response->assertOk()->assertJsonCount(1, 'attendances');
    }

    public function test_auto_alpha_only_inserts_missing_students_and_never_overwrites_attendance(): void
    {
        Attendance::create([
            'attendance_session_id' => $this->session->id,
            'student_id' => $this->student->id,
            'status' => 'present',
            'method' => 'qr',
        ]);

        $this->session->closeWithAutoAlpha();

        $this->assertLatestState('present', 'qr');
        $this->assertSame('closed', $this->session->fresh()->status);
    }

    private function submitAsStudent(array $data): void
    {
        $this->actingAs($this->student)->post(route('student.attendances.store'), [
            'schedule_id' => $this->schedule->id,
            ...$data,
        ])->assertSessionHasNoErrors();
    }

    private function assertLatestState(string $status, string $method): Attendance
    {
        $query = Attendance::where('attendance_session_id', $this->session->id)
            ->where('student_id', $this->student->id);

        $this->assertSame(1, $query->count());
        $attendance = $query->firstOrFail();
        $this->assertSame($status, $attendance->status);
        $this->assertSame($method, $attendance->method);

        return $attendance;
    }
}
