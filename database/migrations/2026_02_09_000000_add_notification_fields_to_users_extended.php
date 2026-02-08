<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users_extended', function (Blueprint $table) {
            $table->boolean('email_notification')->default(true)->after('notes');
            $table->integer('payment_due_date')->default(1)->after('email_notification'); // Tanggal jatuh tempo (1-31)
            $table->timestamp('last_notification_sent')->nullable()->after('payment_due_date');
        });
    }

    public function down(): void
    {
        Schema::table('users_extended', function (Blueprint $table) {
            $table->dropColumn(['email_notification', 'payment_due_date', 'last_notification_sent']);
        });
    }
};
