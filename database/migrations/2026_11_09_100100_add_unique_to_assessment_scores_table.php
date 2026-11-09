<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Satu sel nilai (siswa x kolom) hanya boleh punya satu skor.
     */
    public function up(): void
    {
        Schema::table('assessment_scores', function (Blueprint $table) {
            $table->unique(['assessment_id', 'assessment_component_id'], 'assessment_component_unique');
        });
    }

    public function down(): void
    {
        Schema::table('assessment_scores', function (Blueprint $table) {
            $table->dropUnique('assessment_component_unique');
        });
    }
};
