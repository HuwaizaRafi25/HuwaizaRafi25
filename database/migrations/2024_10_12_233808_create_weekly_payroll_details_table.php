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
        Schema::create('weekly_payroll_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weekly_payroll_header_id')->constrained('weekly_payroll_headers');
            $table->foreignId('daily_payroll_header_id')->constrained('daily_payroll_headers');
            $table->bigInteger('subtotal_pay');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('weekly_payroll_details');
    }
};
