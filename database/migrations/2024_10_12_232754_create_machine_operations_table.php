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
        Schema::create('machine_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained('users');
            $table->foreignId('design_id')->constrained('designs');
            $table->foreignId('assistant_id')->nullable()->constrained('users');
            $table->integer('quantity');
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('machine_operations');
    }
};
