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
        Schema::create('assessments', function (Blueprint $table) {

            $table->id();
            $table->foreignId('registration_id')->constrained('extracurricular_registrations')->cascadeOnDelete();
            $table->string('period_name');
            $table->decimal('final_score', 5, 2)->default(0);
            $table->string('predicate')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('assessed_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('assessed_at')->useCurrent();
            $table->timestamps();
            
            $table->unique(['registration_id', 'period_name'], 'registration_period_unique');
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
