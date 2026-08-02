<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\Extracurricular;
use App\Models\ExtracurricularRegistration;
use App\Models\Schedule;
use App\Models\AttendanceSession;
use App\Models\Attendance;
use App\Models\AssessmentComponent;
use App\Models\Assessment;
use App\Models\AssessmentScore;
use App\Models\Announcement;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password123'); // Demo password
        $adminPassword = Hash::make('badminton123');

        // 1 Admin
        $admin = User::create([
            'name' => 'Jauhar Fauzi',
            'email' => 'jauhar@ekskul.test',
            'password' => $adminPassword,
            'role' => 'admin',
        ]);

        // 2 Guru
        $guru1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@ekskul.test',
            'password' => $password,
            'role' => 'teacher',
        ]);
        TeacherProfile::create([
            'user_id' => $guru1->id,
            'nip' => '198001012005011001',
            'address' => 'Jl. Merdeka No 1',
        ]);

        $guru2 = User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@ekskul.test',
            'password' => $password,
            'role' => 'teacher',
        ]);
        TeacherProfile::create([
            'user_id' => $guru2->id,
            'nip' => '198202022006022002',
            'address' => 'Jl. Pahlawan No 2',
        ]);

        // 5 Siswa
        $siswaList = [];
        for ($i = 1; $i <= 5; $i++) {
            $siswa = User::create([
                'name' => 'Siswa ' . $i,
                'email' => 'siswa'.$i.'@ekskul.test',
                'password' => $password,
                'role' => 'student',
            ]);
            StudentProfile::create([
                'user_id' => $siswa->id,
                'class_name' => '10A',
                'gender' => $i % 2 == 0 ? 'female' : 'male',
                'parent_name' => 'Ortu ' . $i,
                'parent_phone' => '0812345678' . $i,
            ]);
            $siswaList[] = $siswa;
        }

        // 3 Ekskul
        $ekskulPramuka = Extracurricular::create([
            'name' => 'Pramuka',
            'slug' => 'pramuka',
            'description' => 'Kegiatan kepanduan',
            'teacher_id' => $guru1->id,
            'regular_day' => 'Jumat',
            'start_time' => '15:00:00',
            'end_time' => '17:00:00',
            'capacity' => 50,
            'created_by' => $admin->id,
        ]);

        $ekskulBasket = Extracurricular::create([
            'name' => 'Basket',
            'slug' => 'basket',
            'description' => 'Olahraga basket',
            'teacher_id' => $guru2->id,
            'regular_day' => 'Sabtu',
            'start_time' => '14:00:00',
            'end_time' => '16:00:00',
            'capacity' => 30,
            'created_by' => $admin->id,
        ]);

        $ekskulRobotik = Extracurricular::create([
            'name' => 'Robotik',
            'slug' => 'robotik',
            'description' => 'Ekskul robotik dan programming',
            'teacher_id' => $guru1->id,
            'regular_day' => 'Kamis',
            'start_time' => '15:00:00',
            'end_time' => '17:00:00',
            'capacity' => 20,
            'created_by' => $admin->id,
        ]);

        // Pendaftaran
        // Siswa 1 daftar Pramuka (Approved), Basket (Pending)
        ExtracurricularRegistration::create([
            'student_id' => $siswaList[0]->id,
            'extracurricular_id' => $ekskulPramuka->id,
            'status' => 'approved',
            'reviewed_by' => $admin->id,
            'reviewed_at' => Carbon::now(),
        ]);
        ExtracurricularRegistration::create([
            'student_id' => $siswaList[0]->id,
            'extracurricular_id' => $ekskulBasket->id,
            'status' => 'pending',
        ]);

        // Siswa 2 daftar Pramuka (Approved)
        ExtracurricularRegistration::create([
            'student_id' => $siswaList[1]->id,
            'extracurricular_id' => $ekskulPramuka->id,
            'status' => 'approved',
            'reviewed_by' => $admin->id,
            'reviewed_at' => Carbon::now(),
        ]);

        // Siswa 3 daftar Basket (Approved)
        $regS3Basket = ExtracurricularRegistration::create([
            'student_id' => $siswaList[2]->id,
            'extracurricular_id' => $ekskulBasket->id,
            'status' => 'approved',
            'reviewed_by' => $admin->id,
            'reviewed_at' => Carbon::now(),
        ]);

        // Jadwal & Absensi Pramuka
        $jadwal1 = Schedule::create([
            'extracurricular_id' => $ekskulPramuka->id,
            'title' => 'Latihan Tali Temali',
            'activity_date' => Carbon::today(),
            'start_time' => '15:00:00',
            'end_time' => '17:00:00',
            'location' => 'Lapangan Utama',
            'status' => 'completed',
            'attendance_start_at' => Carbon::today()->setTime(14, 45),
            'attendance_end_at' => Carbon::today()->setTime(15, 30),
            'late_after' => Carbon::today()->setTime(15, 15),
            'qr_enabled' => true,
            'created_by' => $guru1->id,
        ]);

        $session1 = AttendanceSession::create([
            'schedule_id' => $jadwal1->id,
            'status' => 'finished',
            'opened_by' => $guru1->id,
            'opened_at' => Carbon::today()->setTime(14, 45),
            'closed_at' => Carbon::today()->setTime(15, 30),
        ]);

        Attendance::create([
            'attendance_session_id' => $session1->id,
            'student_id' => $siswaList[0]->id,
            'status' => 'present',
            'method' => 'qr',
            'checked_at' => Carbon::today()->setTime(14, 50),
        ]);

        Attendance::create([
            'attendance_session_id' => $session1->id,
            'student_id' => $siswaList[1]->id,
            'status' => 'late',
            'method' => 'manual',
            'checked_at' => Carbon::today()->setTime(15, 20),
            'notes' => 'Terlambat karena hujan',
        ]);

        // Komponen Penilaian & Nilai (Basket)
        $komponenDisiplin = AssessmentComponent::create([
            'extracurricular_id' => $ekskulBasket->id,
            'name' => 'Disiplin',
            'weight' => 40,
        ]);
        $komponenSkill = AssessmentComponent::create([
            'extracurricular_id' => $ekskulBasket->id,
            'name' => 'Keterampilan',
            'weight' => 60,
        ]);

        $assessment = Assessment::create([
            'registration_id' => $regS3Basket->id,
            'period_name' => 'Semester 1 2026',
            'final_score' => 88,
            'predicate' => 'Baik',
            'assessed_by' => $guru2->id,
        ]);

        AssessmentScore::create([
            'assessment_id' => $assessment->id,
            'assessment_component_id' => $komponenDisiplin->id,
            'score' => 85,
        ]);
        AssessmentScore::create([
            'assessment_id' => $assessment->id,
            'assessment_component_id' => $komponenSkill->id,
            'score' => 90,
        ]);

        // Pengumuman
        Announcement::create([
            'extracurricular_id' => $ekskulPramuka->id,
            'title' => 'Persiapan Kemah',
            'content' => 'Harap bawa peralatan lengkap untuk kemah sabtu besok.',
            'status' => 'published',
            'published_at' => Carbon::now(),
            'created_by' => $guru1->id,
        ]);
    }
}
