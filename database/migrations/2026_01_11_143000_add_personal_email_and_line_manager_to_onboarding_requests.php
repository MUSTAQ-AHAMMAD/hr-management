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
        Schema::table('onboarding_requests', function (Blueprint $table) {
            $table->string('personal_email')->nullable()->after('employee_id');
            $table->foreignId('line_manager_id')->nullable()->after('personal_email')->constrained('users')->onDelete('set null');
            $table->string('line_manager_email')->nullable()->after('line_manager_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('onboarding_requests', function (Blueprint $table) {
            $table->dropForeign(['line_manager_id']);
            $table->dropColumn(['personal_email', 'line_manager_id', 'line_manager_email']);
        });
    }
};
