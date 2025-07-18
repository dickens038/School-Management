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
        Schema::table('students', function (Blueprint $table) {
            $table->string('combination')->nullable()->after('gender');
            $table->string('admission_status')->default('pending')->after('combination');
            $table->text('admission_notes')->nullable()->after('admission_status');
            $table->foreignId('admitted_by')->nullable()->after('admission_notes')->constrained('users');
            $table->timestamp('admitted_at')->nullable()->after('admitted_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['admitted_by']);
            $table->dropColumn(['combination', 'admission_status', 'admission_notes', 'admitted_by', 'admitted_at']);
        });
    }
};
