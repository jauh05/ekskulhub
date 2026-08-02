<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Extracurricular;
use App\Models\ExtracurricularRegistration;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalStudents = User::where('role', 'student')->where('status', 'active')->count();
        $totalTeachers = User::where('role', 'teacher')->where('status', 'active')->count();
        $totalEkskul = Extracurricular::where('status', 'active')->count();
        $pendingRegistrations = ExtracurricularRegistration::where('status', 'pending')->count();
        
        $recentRegistrations = ExtracurricularRegistration::with(['student', 'extracurricular'])
            ->latest()
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact('totalStudents', 'totalTeachers', 'totalEkskul', 'pendingRegistrations', 'recentRegistrations'));
    }
}
