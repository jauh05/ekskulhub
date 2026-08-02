<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {

            $table->id();
            $table->foreignId('extracurricular_id')->constrained('extracurriculars')->cascadeOnDelete();
            $table->string('title');
            $table->date('activity_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location');
            $table->text('material')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->dateTime('attendance_start_at')->nullable();
            $table->dateTime('attendance_end_at')->nullable();
            $table->dateTime('late_after')->nullable();
            $table->boolean('qr_enabled')->default(false);
            $table->boolean('selfie_enabled')->default(false);
            $table->boolean('manual_enabled')->default(true);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
