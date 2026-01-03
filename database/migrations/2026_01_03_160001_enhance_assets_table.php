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
        Schema::table('assets', function (Blueprint $table) {
            $table->enum('acceptance_status', ['pending_acceptance', 'accepted', 'rejected'])->default('pending_acceptance')->after('status');
            $table->decimal('depreciation_value', 10, 2)->nullable()->after('acceptance_status');
            $table->text('damage_notes')->nullable()->after('depreciation_value');
            $table->date('acceptance_date')->nullable()->after('damage_notes');
            $table->foreignId('task_assignment_id')->nullable()->after('acceptance_date')->constrained('task_assignments')->onDelete('set null');
            $table->foreignId('department_id')->nullable()->after('task_assignment_id')->constrained('departments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign(['task_assignment_id']);
            $table->dropForeign(['department_id']);
            $table->dropColumn(['acceptance_status', 'depreciation_value', 'damage_notes', 'acceptance_date', 'task_assignment_id', 'department_id']);
        });
    }
};
