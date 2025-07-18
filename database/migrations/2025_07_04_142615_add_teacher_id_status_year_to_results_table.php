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
        Schema::table('results', function (Blueprint $table) {
            if (!Schema::hasColumn('results', 'teacher_id')) {
                $table->foreignId('teacher_id')->nullable()->after('subject_id')->constrained('teachers');
            }
            if (!Schema::hasColumn('results', 'status')) {
                $table->string('status')->default('pending')->after('term');
            }
            if (!Schema::hasColumn('results', 'year')) {
                $table->string('year')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            if (Schema::hasColumn('results', 'teacher_id')) {
                $table->dropForeign(['teacher_id']);
                $table->dropColumn('teacher_id');
            }
            if (Schema::hasColumn('results', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('results', 'year')) {
                $table->dropColumn('year');
            }
        });
    }
};
