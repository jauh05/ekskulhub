<?php

namespace App\Http\Controllers;

use App\Models\ExtracurricularRegistration;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminRegistrationController extends Controller
{
    public function index()
    {
        $registrations = ExtracurricularRegistration::with(['student', 'extracurricular'])
            ->latest()
            ->paginate(15);
            
        return view('admin.registrations.index', compact('registrations'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected'])],
        ]);

        $registration = ExtracurricularRegistration::findOrFail($id);
        $registration->update($validated);

        return redirect()->route('admin.registrations.index')->with('success', 'Status pendaftaran berhasil diperbarui');
    }
}
