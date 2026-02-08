<?php

namespace App\Console\Commands;

use App\Models\UsersExtended;
use App\Models\PaymentProof;
use App\Notifications\PaymentReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendPaymentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:send-reminders
                            {--test : Run in test mode (send to first user only)}
                            {--force : Force send even if already sent today}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send payment reminder emails to all active users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting payment reminder process...');

        $currentMonth = now()->month;
        $currentYear = now()->year;
        $today = now()->toDateString();

        // Get all active users with email notification enabled
        $query = UsersExtended::with('user')
            ->where('status', 'active')
            ->where('email_notification', true)
            ->where(function ($q) use ($today) {
                $q->whereNull('last_notification_sent')
                  ->orWhereDate('last_notification_sent', '<', $today);
            });

        if ($this->option('test')) {
            $query->limit(1);
            $this->warn('⚠️  TEST MODE: Only sending to first user');
        }

        $users = $query->get();

        if ($users->isEmpty()) {
            $this->warn('No users found to notify.');
            return 0;
        }

        $this->info("Found {$users->count()} user(s) to notify.");

        $sentCount = 0;
        $failedCount = 0;

        foreach ($users as $userExtended) {
            try {
                // Check if user has paid for current month
                $paidAmount = PaymentProof::where('users_extended_id', $userExtended->id)
                    ->where('month', $currentMonth)
                    ->where('year', $currentYear)
                    ->where('status', 'approved')
                    ->sum('amount');

                $hasPaid = $paidAmount >= $userExtended->monthly_fee;

                // Send notification
                $userExtended->user->notify(new PaymentReminderNotification(
                    $userExtended->monthly_fee,
                    $userExtended->payment_due_date ?? 1,
                    $userExtended->room_number,
                    $hasPaid,
                    $currentMonth,
                    $currentYear,
                    $paidAmount
                ));

                // Update last notification sent
                $userExtended->update(['last_notification_sent' => now()]);

                $this->info("✅ Sent to: {$userExtended->user->name} ({$userExtended->user->email}) - " . ($hasPaid ? 'LUNAS' : 'BELUM LUNAS'));
                $sentCount++;

            } catch (\Exception $e) {
                $this->error("❌ Failed to send to {$userExtended->user->name}: {$e->getMessage()}");
                Log::error('Payment reminder failed', [
                    'user_id' => $userExtended->user_id,
                    'error' => $e->getMessage()
                ]);
                $failedCount++;
            }
        }

        $this->newLine();
        $this->info("📊 Summary: {$sentCount} sent, {$failedCount} failed");

        return 0;
    }
}
