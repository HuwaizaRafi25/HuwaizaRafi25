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
        Schema::create('qc_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qc_id')->constrained('users');
            $table->foreignId('design_id')->constrained('designs');
            $table->integer('quantity_checked');
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('qc_operations');
    }
};
