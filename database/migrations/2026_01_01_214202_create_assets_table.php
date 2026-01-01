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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('asset_type'); // e.g., Laptop, SIM Card, ID Card, Access Card
            $table->string('asset_name');
            $table->string('serial_number')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->date('assigned_date')->nullable();
            $table->date('return_date')->nullable();
            $table->enum('status', ['assigned', 'returned', 'damaged', 'lost'])->default('assigned');
            $table->text('return_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
