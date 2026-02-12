<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_proof_id')->constrained('payment_proofs')->onDelete('cascade');
            $table->string('receipt_number', 50)->unique(); // Format: KWT-YYYYMM-XXXXX
            $table->string('tenant_name');
            $table->string('room_number', 10)->nullable();
            $table->decimal('amount', 10, 2);
            $table->integer('month');
            $table->integer('year');
            $table->string('payment_method', 50);
            $table->text('description')->nullable();
            $table->string('approved_by_name')->nullable();
            $table->timestamp('issued_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_receipts');
    }
};
