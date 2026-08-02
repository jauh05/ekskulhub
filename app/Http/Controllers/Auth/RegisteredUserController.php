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
        return view('auth.register');
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
            'nisn' => ['required', 'string', 'max:50'],
            'class' => ['required', 'string', 'max:50'],
            'dob' => ['nullable', 'date'],
            'ekskul' => ['nullable', 'string'],
            'class_code' => ['nullable', 'string'],
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
            'nis' => $request->nisn,
            'class_name' => $request->class,
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
