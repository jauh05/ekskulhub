<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeacherAnnouncementController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        $ekskuls = $teacher->taughtExtracurriculars;
        $ekskulIds = $ekskuls->pluck('id');
        
        $announcements = Announcement::whereIn('extracurricular_id', $ekskulIds)
            ->with('extracurricular')
            ->latest()
            ->paginate(10);
            
        return view('teacher.announcements.index', compact('announcements'));
    }

    public function create()
    {
        $teacher = Auth::user();
        $ekskuls = $teacher->taughtExtracurriculars;
        return view('teacher.announcements.create', compact('ekskuls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'extracurricular_id' => 'required|exists:extracurriculars,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
        ]);

        $teacher = Auth::user();
        $ekskuls = $teacher->taughtExtracurriculars->pluck('id')->toArray();

        if (!in_array($request->extracurricular_id, $ekskuls)) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->only(['extracurricular_id', 'title', 'content', 'status']);
        $data['created_by'] = $teacher->id;
        
        if ($data['status'] == 'published') {
            $data['published_at'] = now();
        }

        Announcement::create($data);

        return redirect()->route('teacher.announcements.index')->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function edit(Announcement $announcement)
    {
        $teacher = Auth::user();
        $ekskuls = $teacher->taughtExtracurriculars;
        
        if ($announcement->extracurricular->teacher_id !== $teacher->id) {
            abort(403);
        }

        return view('teacher.announcements.edit', compact('announcement', 'ekskuls'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $teacher = Auth::user();
        
        if ($announcement->extracurricular->teacher_id !== $teacher->id) {
            abort(403);
        }

        $request->validate([
            'extracurricular_id' => 'required|exists:extracurriculars,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
        ]);

        $data = $request->only(['extracurricular_id', 'title', 'content', 'status']);
        
        if ($data['status'] == 'published' && !$announcement->published_at) {
            $data['published_at'] = now();
        } elseif ($data['status'] == 'draft') {
            $data['published_at'] = null;
        }

        $announcement->update($data);

        return redirect()->route('teacher.announcements.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement)
    {
        $teacher = Auth::user();
        
        if ($announcement->extracurricular->teacher_id !== $teacher->id) {
            abort(403);
        }

        $announcement->delete();

        return redirect()->route('teacher.announcements.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
