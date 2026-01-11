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
        Schema::table('employees', function (Blueprint $table) {
            // Make email nullable
            $table->string('email')->nullable()->change();
            
            // Add field to track if email was created by IT
            $table->boolean('email_created_by_it')->default(false)->after('email');
            
            // Add field to track when email was created/updated by IT
            $table->timestamp('email_created_at')->nullable()->after('email_created_by_it');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Revert email to not nullable (with caution)
            $table->string('email')->nullable(false)->change();
            
            // Drop the tracking fields
            $table->dropColumn(['email_created_by_it', 'email_created_at']);
        });
    }
};
