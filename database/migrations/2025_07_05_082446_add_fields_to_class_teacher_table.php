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
        Schema::table('class_teacher', function (Blueprint $table) {
            $table->boolean('is_class_teacher')->default(false)->after('teacher_id');
            $table->timestamp('assigned_at')->nullable()->after('is_class_teacher');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null')->after('assigned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_teacher', function (Blueprint $table) {
            $table->dropForeign(['assigned_by']);
            $table->dropColumn(['is_class_teacher', 'assigned_at', 'assigned_by']);
        });
    }
};
