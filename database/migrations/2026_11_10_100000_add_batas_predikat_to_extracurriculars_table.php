<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Batas nilai akhir minimum untuk predikat A, B, C, dan D per ekstrakurikuler.
     * Nilai di bawah batas D berarti E. Default sama dengan batas bawaan sistem.
     */
    public function up(): void
    {
        Schema::table('extracurriculars', function (Blueprint $table) {
            $table->decimal('batas_a', 5, 2)->default(85)->after('attendance_weight');
            $table->decimal('batas_b', 5, 2)->default(75)->after('batas_a');
            $table->decimal('batas_c', 5, 2)->default(65)->after('batas_b');
            $table->decimal('batas_d', 5, 2)->default(50)->after('batas_c');
        });
    }

    public function down(): void
    {
        Schema::table('extracurriculars', function (Blueprint $table) {
            $table->dropColumn(['batas_a', 'batas_b', 'batas_c', 'batas_d']);
        });
    }
};
