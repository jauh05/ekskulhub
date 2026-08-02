<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminExtracurricularController;
use App\Http\Controllers\AdminRegistrationController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\StudentHomeController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\StudentExtracurricularController;
use App\Http\Controllers\StudentAttendanceController;
use App\Http\Controllers\StudentAssessmentController;
use App\Http\Controllers\TeacherScheduleController;
use App\Http\Controllers\TeacherParticipantController;
use App\Http\Controllers\TeacherAttendanceController;
use App\Http\Controllers\TeacherGradingController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'teacher') return redirect()->route('teacher.dashboard');
        if ($role === 'student') return redirect()->route('student.dashboard');
    }
    return redirect('/');
})->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'active'])->group(function () {
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', AdminUserController::class);
        Route::resource('extracurriculars', AdminExtracurricularController::class);
        Route::get('registrations', [AdminRegistrationController::class, 'index'])->name('registrations.index');
        Route::patch('registrations/{id}', [AdminRegistrationController::class, 'update'])->name('registrations.update');
    });

    Route::prefix('guru')->name('teacher.')->middleware('role:teacher')->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
        Route::post('/dashboard/class-code/{extracurricular}', [TeacherDashboardController::class, 'updateClassCode'])->name('class_code.update');
        Route::resource('schedules', TeacherScheduleController::class);
        Route::resource('participants', TeacherParticipantController::class)->only(['index', 'update']);
        Route::post('/attendances/session/start', [TeacherAttendanceController::class, 'startSession'])->name('attendances.start');
        Route::get('/attendances/session/{session}/live', [TeacherAttendanceController::class, 'live'])->name('attendances.live');
        Route::get('/attendances/session/{session}/api/qr', [TeacherAttendanceController::class, 'getLiveQr'])->name('attendances.live.qr');
        Route::get('/attendances/session/{session}/api/data', [TeacherAttendanceController::class, 'getLiveData'])->name('attendances.live.data');
        Route::post('/attendances/session/{session}/close', [TeacherAttendanceController::class, 'closeSession'])->name('attendances.live.close');
        Route::resource('attendances', TeacherAttendanceController::class)->only(['index', 'update']);
        Route::resource('grading', TeacherGradingController::class);
        
        Route::get('/report', [\App\Http\Controllers\TeacherReportController::class, 'index'])->name('reports.index');
        Route::get('/report/export', [\App\Http\Controllers\TeacherReportController::class, 'export'])->name('reports.export');
    });

    Route::prefix('siswa')->name('student.')->middleware('role:student')->group(function () {
        Route::get('/', [StudentHomeController::class, 'index'])->name('dashboard');
        
        Route::get('/profil', [StudentProfileController::class, 'index'])->name('profile.index');
        Route::post('/profil', [StudentProfileController::class, 'store'])->name('profile.store');
        
        Route::get('/ekskul', [StudentExtracurricularController::class, 'index'])->name('extracurriculars.index');
        Route::post('/ekskul', [StudentExtracurricularController::class, 'store'])->name('extracurriculars.store');
        
        Route::get('/absensi', [StudentAttendanceController::class, 'index'])->name('attendances.index');
        Route::get('/absensi/scan', [StudentAttendanceController::class, 'create'])->name('attendances.create');
        Route::post('/absensi', [StudentAttendanceController::class, 'store'])->name('attendances.store');
        
        Route::get('/penilaian', [StudentAssessmentController::class, 'index'])->name('assessments.index');
    });
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
