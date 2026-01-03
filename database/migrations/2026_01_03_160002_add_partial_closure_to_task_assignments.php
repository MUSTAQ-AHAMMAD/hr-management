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
        Schema::table('task_assignments', function (Blueprint $table) {
            $table->boolean('is_partially_closed')->default(false)->after('rejection_reason');
            $table->text('partial_closure_reason')->nullable()->after('is_partially_closed');
            $table->date('partial_closure_date')->nullable()->after('partial_closure_reason');
            $table->boolean('notify_on_availability')->default(false)->after('partial_closure_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_assignments', function (Blueprint $table) {
            $table->dropColumn(['is_partially_closed', 'partial_closure_reason', 'partial_closure_date', 'notify_on_availability']);
        });
    }
};
