<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Extended users table for kontrakan
        Schema::create('users_extended', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('phone', 20)->unique();
            $table->string('room_number', 10)->nullable();
            $table->decimal('monthly_fee', 10, 2); // Iuran bulanan per orang (bervariasi)
            $table->date('join_date');
            $table->date('contract_end_date');
            $table->enum('status', ['active', 'inactive', 'pending', 'blocked'])->default('active');
            $table->text('address')->nullable();
            $table->string('emergency_contact', 20)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_extended_id')->constrained('users_extended')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->integer('month'); // 1-12
            $table->integer('year');
            $table->string('payment_method', 50)->default('transfer');
            $table->string('bank_name', 50)->nullable();
            $table->string('account_number', 50)->nullable();
            $table->string('proof_image', 255)->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('category', 50);
            $table->decimal('amount', 10, 2);
            $table->date('expense_date');
            $table->text('description')->nullable();
            $table->string('receipt_image', 255)->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_proof_id')->nullable()->constrained('payment_proofs')->onDelete('cascade');
            $table->enum('type', ['income', 'expense']);
            $table->decimal('amount', 10, 2);
            $table->integer('month');
            $table->integer('year');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->decimal('balance', 15, 2)->default(0);
            $table->date('balance_date')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('payment_proofs');
        Schema::dropIfExists('users_extended');
    }
};
