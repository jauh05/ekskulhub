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
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropUnique('student_profiles_nis_unique');
            $table->dropColumn('nis');
            $table->string('school_name')->nullable()->after('user_id');
        });

        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->string('school_name')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn('school_name');
            $table->string('nis')->unique()->after('user_id');
        });

        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->dropColumn('school_name');
        });
    }
};
