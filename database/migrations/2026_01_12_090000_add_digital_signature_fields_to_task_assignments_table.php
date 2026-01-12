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
            $table->string('approved_by_name')->nullable()->after('completed_date');
            $table->string('approved_by_email')->nullable()->after('approved_by_name');
            $table->timestamp('digital_signature_date')->nullable()->after('approved_by_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_assignments', function (Blueprint $table) {
            $table->dropColumn([
                'approved_by_name',
                'approved_by_email',
                'digital_signature_date',
            ]);
        });
    }
};
