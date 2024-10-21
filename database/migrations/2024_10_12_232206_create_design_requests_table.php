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
        Schema::create('design_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('design_request_header_id')->constrained('design_request_headers'); // Foreign key ke tabel design_request_headers
            $table->foreignId('assigned_designer_id')->nullable()->constrained('users');
            $table->foreignId('supervisor_id')->nullable()->constrained('users');
            $table->string('reference_image')->nullable();
            $table->bigInteger('price_per_piece')->nullable();
            $table->integer('total_pieces');
            $table->enum('status', ['pending', 'approved', 'redesign', 'in_design', 'in_production', 'in_qc', 'completed', 'shipped', 'cancelled'])->default('pending'); // Status untuk proses design request
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('design_requests');
    }
};
