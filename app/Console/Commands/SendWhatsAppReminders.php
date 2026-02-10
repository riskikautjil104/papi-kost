<?php

namespace App\Console\Commands;

use App\Models\UsersExtended;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendWhatsAppReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:send-wa-reminders
                            {--days=2,3 : Days before due date to send reminder (comma separated)}
                            {--test : Run in test mode (dry run, no actual send)}
                            {--force : Force send regardless of day check}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send WhatsApp group reminder H-2/H-3 before payment due date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting WhatsApp payment reminder process...');

        $whatsapp = new WhatsAppService();

        if (!config('services.waha.enabled')) {
            $this->warn('⚠️  WhatsApp (WAHA) is disabled. Set WAHA_ENABLED=true in .env');
            return 0;
        }

        $currentMonth = now()->month;
        $currentYear = now()->year;
        $today = now()->day;

        // Parse days option (e.g., "2,3" → [2, 3])
        $reminderDays = array_map('intval', explode(',', $this->option('days')));

        $this->info("📅 Today: " . now()->format('d/m/Y'));
        $this->info("🔔 Reminder days before due: " . implode(', ', $reminderDays));

        // Get all active users
        $users = UsersExtended::with('user')
            ->where('status', 'active')
            ->get();

        if ($users->isEmpty()) {
            $this->warn('No active users found.');
            return 0;
        }

        // Group users by payment_due_date
        $unpaidUsers = [];
        $dueDate = null;
        $shouldSend = false;

        foreach ($users as $userExtended) {
            $userDueDate = $userExtended->payment_due_date ?? 1;

            // Check if today is H-2 or H-3 before this user's due date
            $daysUntilDue = $userDueDate - $today;

            // Handle month wrap (e.g., due date is 2nd, today is 29th)
            if ($daysUntilDue < 0) {
                $daysInMonth = now()->daysInMonth;
                $daysUntilDue = ($daysInMonth - $today) + $userDueDate;
            }

            $isReminderDay = in_array($daysUntilDue, $reminderDays) || $this->option('force');

            if (!$isReminderDay) {
                continue;
            }

            // Check if user has paid for current month
            if ($userExtended->isFullyPaidForMonth($currentMonth, $currentYear)) {
                $this->info("✅ {$userExtended->user->name} (Kamar {$userExtended->room_number}) - LUNAS, skip");
                continue;
            }

            $remaining = $userExtended->getRemainingForMonth($currentMonth, $currentYear);

            $unpaidUsers[] = [
                'name' => $userExtended->user->name,
                'room' => $userExtended->room_number,
                'remaining' => $remaining,
                'phone' => $userExtended->phone,
                'due_date' => $userDueDate,
            ];

            $dueDate = $userDueDate;
            $shouldSend = true;

            $this->info("⏳ {$userExtended->user->name} (Kamar {$userExtended->room_number}) - Sisa: Rp " . number_format($remaining, 0, ',', '.'));
        }

        if (!$shouldSend || empty($unpaidUsers)) {
            $this->info('🎉 Semua penghuni sudah lunas atau belum waktunya reminder!');
            return 0;
        }

        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        $monthName = $monthNames[$currentMonth];

        // Determine the most common due date for group message
        $dueDates = array_column($unpaidUsers, 'due_date');
        $commonDueDate = array_count_values($dueDates);
        arsort($commonDueDate);
        $mainDueDate = array_key_first($commonDueDate);

        if ($this->option('test')) {
            $this->warn('⚠️  TEST MODE: Tidak mengirim pesan, hanya simulasi');
            $this->info("📤 Akan kirim reminder grup untuk {$monthName} {$currentYear}");
            $this->info("👥 Total belum bayar: " . count($unpaidUsers) . " orang");
            return 0;
        }

        // Send group reminder
        $this->info("\n📤 Mengirim reminder ke grup WhatsApp...");

        try {
            $sent = $whatsapp->sendPaymentReminder(
                $unpaidUsers,
                $mainDueDate,
                $monthName,
                $currentYear
            );

            if ($sent) {
                $this->info("✅ Reminder grup berhasil dikirim!");
                Log::info('WhatsApp group reminder sent', [
                    'month' => $monthName,
                    'year' => $currentYear,
                    'unpaid_count' => count($unpaidUsers),
                ]);
            } else {
                $this->error("❌ Gagal mengirim reminder ke grup!");
            }
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            Log::error('WhatsApp reminder failed: ' . $e->getMessage());
        }

        $this->newLine();
        $this->info("📊 Summary: " . count($unpaidUsers) . " penghuni belum lunas");

        return 0;
    }
}
