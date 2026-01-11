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
        Schema::table('exit_clearance_requests', function (Blueprint $table) {
            $table->foreignId('line_manager_id')->nullable()->after('employee_id')->constrained('users')->onDelete('set null');
            $table->string('line_manager_email')->nullable()->after('line_manager_id');
            $table->enum('line_manager_approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            $table->timestamp('line_manager_approved_at')->nullable()->after('line_manager_approval_status');
            $table->text('line_manager_approval_notes')->nullable()->after('line_manager_approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exit_clearance_requests', function (Blueprint $table) {
            $table->dropForeign(['line_manager_id']);
            $table->dropColumn([
                'line_manager_id',
                'line_manager_email',
                'line_manager_approval_status',
                'line_manager_approved_at',
                'line_manager_approval_notes'
            ]);
        });
    }
};
