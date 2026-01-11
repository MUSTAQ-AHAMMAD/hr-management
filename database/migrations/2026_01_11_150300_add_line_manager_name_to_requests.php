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
            $table->string('line_manager_name')->nullable()->after('line_manager_email');
        });

        Schema::table('exit_clearance_requests', function (Blueprint $table) {
            $table->string('line_manager_name')->nullable()->after('line_manager_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('onboarding_requests', function (Blueprint $table) {
            $table->dropColumn('line_manager_name');
        });

        Schema::table('exit_clearance_requests', function (Blueprint $table) {
            $table->dropColumn('line_manager_name');
        });
    }
};
