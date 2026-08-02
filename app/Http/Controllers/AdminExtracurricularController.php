<?php

namespace App\Http\Controllers;

use App\Models\Extracurricular;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminExtracurricularController extends Controller
{
    public function index()
    {
        $extracurriculars = Extracurricular::with('teacher')->get();
        return view('admin.extracurriculars.index', compact('extracurriculars'));
    }

    public function create()
    {
        $teachers = User::where('role', 'teacher')->where('status', 'active')->get();
        return view('admin.extracurriculars.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teacher_id' => 'required|exists:users,id',
            'schedule' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        Extracurricular::create($validated);

        return redirect()->route('admin.extracurriculars.index')->with('success', 'Ekskul berhasil ditambahkan');
    }

    public function edit(Extracurricular $extracurricular)
    {
        $teachers = User::where('role', 'teacher')->where('status', 'active')->get();
        return view('admin.extracurriculars.edit', compact('extracurricular', 'teachers'));
    }

    public function update(Request $request, Extracurricular $extracurricular)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teacher_id' => 'required|exists:users,id',
            'schedule' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $extracurricular->update($validated);

        return redirect()->route('admin.extracurriculars.index')->with('success', 'Data ekskul berhasil diperbarui');
    }

    public function destroy(Extracurricular $extracurricular)
    {
        $extracurricular->delete();
        return redirect()->route('admin.extracurriculars.index')->with('success', 'Ekskul berhasil dihapus');
    }
}
