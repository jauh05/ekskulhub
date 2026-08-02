<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TeacherProfile;
use App\Models\Extracurricular;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class TeacherRegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $extracurriculars = Extracurricular::where('status', 'active')->get();
        return view('auth.register-guru', compact('extracurriculars'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'nip' => ['nullable', 'string', 'max:50'],
            'school_name' => ['nullable', 'string', 'max:255'],
            'ekskul_name' => ['nullable', 'string', 'max:255'],
            'schedule' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
        ]);

        TeacherProfile::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'school_name' => $request->school_name,
        ]);

        // If ekskul_name is provided, create a new extracurricular assigned to this teacher
        if ($request->ekskul_name) {
            Extracurricular::create([
                'name' => $request->ekskul_name,
                'slug' => \Illuminate\Support\Str::slug($request->ekskul_name . '-' . time()),
                'teacher_id' => $user->id,
                'regular_day' => $request->schedule, // Storing full schedule string in regular_day for now
                'created_by' => $user->id,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('teacher.dashboard', absolute: false));
    }
}
