<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payroll_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('design_request_id')->constrained('design_requests');
            $table->decimal('pay_designer', 10, 2)->nullable();
            $table->decimal('pay_machine_operator', 10, 2)->nullable();
            $table->decimal('pay_qc', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('payroll_jobs');
    }
};
