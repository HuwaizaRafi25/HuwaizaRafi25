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
        Schema::create('transaction_headers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users');
            $table->bigInteger('total_price');
            $table->enum('payment_type', ['cash', 'credit', 'e-wallet', 'transfer_bank'])->default('cash');
            $table->string('payment_proof_pic')->nullable();
            $table->enum('status', ['pending', 'paid', 'unpaid', 'cancelled'])->default('[pending]');
            $table->integer('rating')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_headers');
    }
};
