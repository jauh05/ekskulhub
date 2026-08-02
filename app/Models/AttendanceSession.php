<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceSession extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
    
    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
            'qr_expires_at' => 'datetime',
            'qr_last_rotated_at' => 'datetime',
        ];
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function opener(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function closeWithAutoAlpha()
    {
        if ($this->status === 'closed') return;

        $extracurricularId = $this->schedule->extracurricular_id;
        
        $studentIds = \App\Models\ExtracurricularRegistration::where('extracurricular_id', $extracurricularId)
            ->where('status', 'approved')
            ->pluck('student_id');
            
        $attendedStudentIds = \App\Models\Attendance::where('attendance_session_id', $this->id)
            ->pluck('student_id');
            
        $absentStudentIds = $studentIds->diff($attendedStudentIds);
        
        $now = now();
        $absentRecords = [];
        foreach ($absentStudentIds as $id) {
            $absentRecords[] = [
                'student_id' => $id,
                'attendance_session_id' => $this->id,
                'status' => 'absent',
                'method' => 'manual',
                'checked_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        
        if (count($absentRecords) > 0) {
            \App\Models\Attendance::insert($absentRecords);
        }

        $this->update([
            'status' => 'closed',
            'closed_at' => $now,
        ]);
    }

    public static function autoCloseExpiredSessions()
    {
        $expiredSessions = self::where('status', 'open')
            ->where('opened_at', '<=', now()->subHours(2))
            ->with('schedule')
            ->get();
            
        foreach ($expiredSessions as $session) {
            $session->closeWithAutoAlpha();
        }
    }
}
