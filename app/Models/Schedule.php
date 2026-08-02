<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $guarded = ['id'];
    
    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
            'qr_enabled' => 'boolean',
            'selfie_enabled' => 'boolean',
            'manual_enabled' => 'boolean',
            'attendance_start_at' => 'datetime',
            'attendance_end_at' => 'datetime',
            'late_after' => 'datetime',
        ];
    }

    public function extracurricular(): BelongsTo
    {
        return $this->belongsTo(Extracurricular::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendanceSession(): HasOne
    {
        return $this->hasOne(AttendanceSession::class);
    }
}
