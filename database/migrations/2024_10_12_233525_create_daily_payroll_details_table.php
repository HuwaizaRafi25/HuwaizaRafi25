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
        Schema::create('daily_payroll_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_payroll_header_id')->constrained('daily_payroll_headers');
            $table->foreignId('design_request_id')->constrained('design_requests');
            $table->enum('job_type', ['designer', 'machine_operator', 'qc']);
            $table->integer('pieces_worked');
            $table->bigInteger('pay_per_piece');
            $table->bigInteger('subtotal_pay');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('daily_payroll_details');
    }
};
