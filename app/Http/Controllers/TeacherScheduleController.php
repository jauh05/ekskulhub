<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Extracurricular;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeacherScheduleController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        $ekskulIds = $teacher->taughtExtracurriculars()->pluck('id');
        
        $schedules = Schedule::with('extracurricular')
            ->whereIn('extracurricular_id', $ekskulIds)
            ->latest('activity_date')
            ->paginate(15);
            
        return view('teacher.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $teacher = Auth::user();
        $ekskuls = $teacher->taughtExtracurriculars;
        return view('teacher.schedules.create', compact('ekskuls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'extracurricular_id' => 'required|exists:extracurriculars,id',
            'activity_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'topic' => 'nullable|string',
            'repeat_type' => 'nullable|in:none,weekly',
            'repeat_until' => 'nullable|required_if:repeat_type,weekly|date|after_or_equal:activity_date',
        ]);

        $teacher = Auth::user();
        $ekskul = Extracurricular::findOrFail($request->extracurricular_id);
        
        if ($ekskul->teacher_id !== $teacher->id) {
            abort(403);
        }

        $baseData = [
            'extracurricular_id' => $request->extracurricular_id,
            'title' => 'Latihan ' . $ekskul->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'material' => $request->topic,
            'attendance_start_at' => Carbon::parse($request->activity_date . ' ' . $request->start_time)->subMinutes(30),
            'attendance_end_at' => Carbon::parse($request->activity_date . ' ' . $request->end_time)->addMinutes(30),
            'created_by' => $teacher->id,
            'status' => 'scheduled'
        ];

        if ($request->repeat_type === 'weekly') {
            $currentDate = Carbon::parse($request->activity_date);
            $endDate = Carbon::parse($request->repeat_until);
            
            while ($currentDate->lessThanOrEqualTo($endDate)) {
                $scheduleData = $baseData;
                $scheduleData['activity_date'] = $currentDate->format('Y-m-d');
                $scheduleData['attendance_start_at'] = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $request->start_time)->subMinutes(30);
                $scheduleData['attendance_end_at'] = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $request->end_time)->addMinutes(30);
                
                Schedule::create($scheduleData);
                $currentDate->addWeek();
            }
            return redirect()->route('teacher.dashboard')->with('success', 'Jadwal rutin mingguan berhasil ditambahkan');
        } else {
            $baseData['activity_date'] = $request->activity_date;
            Schedule::create($baseData);
            return redirect()->route('teacher.dashboard')->with('success', 'Jadwal berhasil ditambahkan');
        }
    }

    public function show(Schedule $schedule)
    {
        $teacher = Auth::user();
        if ($schedule->extracurricular->teacher_id !== $teacher->id) {
            abort(403);
        }
        
        return view('teacher.schedules.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        $teacher = Auth::user();
        if ($schedule->extracurricular->teacher_id !== $teacher->id) {
            abort(403);
        }
        $ekskuls = $teacher->taughtExtracurriculars;
        return view('teacher.schedules.edit', compact('schedule', 'ekskuls'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $teacher = Auth::user();
        if ($schedule->extracurricular->teacher_id !== $teacher->id) {
            abort(403);
        }

        $request->validate([
            'extracurricular_id' => 'required|exists:extracurriculars,id',
            'activity_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'topic' => 'nullable|string',
        ]);

        $schedule->update([
            'extracurricular_id' => $request->extracurricular_id,
            'activity_date' => $request->activity_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'material' => $request->topic,
            'attendance_start_at' => Carbon::parse($request->activity_date . ' ' . $request->start_time)->subMinutes(30),
            'attendance_end_at' => Carbon::parse($request->activity_date . ' ' . $request->end_time)->addMinutes(30),
        ]);

        return redirect()->route('teacher.dashboard')->with('success', 'Jadwal berhasil diupdate');
    }

    public function destroy(Schedule $schedule)
    {
        $teacher = Auth::user();
        if ($schedule->extracurricular->teacher_id !== $teacher->id) {
            abort(403);
        }

        $schedule->delete();
        return redirect()->route('teacher.dashboard')->with('success', 'Jadwal berhasil dihapus');
    }
}
