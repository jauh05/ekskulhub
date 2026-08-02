<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $schools = \App\Models\TeacherProfile::whereNotNull('school_name')
            ->distinct()
            ->pluck('school_name');
            
        return view('auth.register', compact('schools'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'school_name' => ['required', 'string', 'max:255'],
            'class' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'in:male,female'],
            'dob' => ['nullable', 'date'],
            'ekskul' => ['nullable', 'string'],
            'class_code' => ['nullable', 'string', 'exists:extracurriculars,class_code'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap tidak boleh lebih dari 255 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau masuk ke akun Anda.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal harus 8 karakter.',
            'school_name.required' => 'Nama sekolah wajib dipilih.',
            'class.required' => 'Kelas wajib dipilih.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Pilihan jenis kelamin tidak valid.',
            'class_code.exists' => 'Kode Kelas yang Anda masukkan tidak valid atau tidak ditemukan.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->whatsapp,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        \App\Models\StudentProfile::create([
            'user_id' => $user->id,
            'school_name' => $request->school_name,
            'class_name' => $request->class,
            'gender' => $request->gender,
            'birth_date' => $request->dob,
        ]);

        $extracurricular = null;
        if ($request->class_code) {
            $extracurricular = \App\Models\Extracurricular::where('class_code', $request->class_code)->first();
        } elseif ($request->ekskul) {
            $extracurricular = \App\Models\Extracurricular::where('name', 'like', '%' . $request->ekskul . '%')->first();
        }

        if ($extracurricular) {
            \App\Models\ExtracurricularRegistration::create([
                'student_id' => $user->id,
                'extracurricular_id' => $extracurricular->id,
                'status' => 'pending',
                'reason' => null,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('student.dashboard', absolute: false));
    }
}
