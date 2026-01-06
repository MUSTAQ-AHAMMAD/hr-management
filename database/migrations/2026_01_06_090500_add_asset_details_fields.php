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
            $table->decimal('asset_value', 10, 2)->nullable()->after('serial_number');
            $table->date('purchase_date')->nullable()->after('asset_value');
            $table->enum('condition', ['new', 'good', 'fair', 'poor'])->default('good')->after('purchase_date');
            $table->string('warranty_period')->nullable()->after('condition');
            $table->date('warranty_expiry')->nullable()->after('warranty_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['asset_value', 'purchase_date', 'condition', 'warranty_period', 'warranty_expiry']);
        });
    }
};
