<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('attendances', 'is_verified_by_teacher')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->boolean('is_verified_by_teacher')->default(false)->after('notes');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('attendances', 'is_verified_by_teacher')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->dropColumn('is_verified_by_teacher');
            });
        }
    }
};
