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
        Schema::create('exit_clearance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('initiated_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'in_progress', 'cleared', 'rejected'])->default('pending');
            $table->date('exit_date');
            $table->text('reason')->nullable();
            $table->boolean('assets_returned')->default(false);
            $table->boolean('financial_cleared')->default(false);
            $table->date('clearance_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exit_clearance_requests');
    }
};
