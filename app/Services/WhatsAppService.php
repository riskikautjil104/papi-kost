<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $apiUrl;
    protected string $apiKey;
    protected string $session;
    protected string $groupId;
    protected bool $enabled;

    public function __construct()
    {
        $this->apiUrl = rtrim(config('services.waha.api_url', 'http://localhost:3000'), '/');
        $this->apiKey = config('services.waha.api_key', '');
        $this->session = config('services.waha.session', 'default');
        $this->groupId = config('services.waha.group_id', '');
        $this->enabled = config('services.waha.enabled', false);
    }

    /**
     * HTTP client dengan API key header
     */
    protected function http()
    {
        $http = Http::baseUrl($this->apiUrl);

        if (!empty($this->apiKey)) {
            $http = $http->withHeaders(['X-Api-Key' => $this->apiKey]);
        }

        return $http;
    }

    /**
     * Kirim pesan teks ke grup WhatsApp via WAHA
     */
    public function sendToGroup(string $message, ?string $imageUrl = null): bool
    {
        if (!$this->enabled || empty($this->groupId)) {
            Log::warning('WhatsApp notification skipped: WAHA not configured');
            return false;
        }

        try {
            // Jika ada gambar, kirim sebagai image + caption
            if ($imageUrl) {
                return $this->sendImage($this->groupId, $imageUrl, $message);
            }

            // Kirim pesan teks biasa
            $response = $this->http()->post('/api/sendText', [
                'chatId' => $this->groupId,
                'text' => $message,
                'session' => $this->session,
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp group notification sent successfully', [
                    'group_id' => $this->groupId,
                ]);
                return true;
            }

            Log::error('WhatsApp WAHA API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp notification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim gambar + caption ke grup WhatsApp via WAHA
     */
    protected function sendImage(string $chatId, string $imageUrl, string $caption = ''): bool
    {
        try {
            $response = $this->http()->post('/api/sendImage', [
                'chatId' => $chatId,
                'file' => ['url' => $imageUrl],
                'caption' => $caption,
                'session' => $this->session,
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp image notification sent successfully', [
                    'group_id' => $chatId,
                ]);
                return true;
            }

            Log::error('WhatsApp WAHA sendImage failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp sendImage error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim notifikasi pembayaran baru ke grup
     */
    public function sendPaymentNotification(
        string $userName,
        string $roomNumber,
        float $amount,
        string $monthName,
        int $year,
        string $paymentMethod,
        ?string $proofImageUrl = null
    ): bool {
        $methodLabels = [
            'transfer' => 'Transfer Bank',
            'cash' => 'Tunai',
            'ewallet' => 'E-Wallet',
        ];

        $methodLabel = $methodLabels[$paymentMethod] ?? ucfirst($paymentMethod);

        $message = "💰 *PEMBAYARAN BARU*\n"
            . "━━━━━━━━━━━━━━━━━━━\n"
            . "👤 Nama: *{$userName}*\n"
            . "🏠 Kamar: *{$roomNumber}*\n"
            . "💵 Jumlah: *Rp " . number_format($amount, 0, ',', '.') . "*\n"
            . "📅 Periode: *{$monthName} {$year}*\n"
            . "💳 Metode: *{$methodLabel}*\n"
            . "📌 Status: ⏳ *Menunggu Persetujuan*\n"
            . "━━━━━━━━━━━━━━━━━━━\n"
            . "⏰ " . now()->format('d/m/Y H:i') . "\n"
            . "\n_Silakan cek dashboard admin untuk approve/reject._";

        return $this->sendToGroup($message, $proofImageUrl);
    }

    /**
     * Kirim notifikasi pembayaran di-approve ke grup
     */
    public function sendApprovalNotification(
        string $userName,
        string $roomNumber,
        float $amount,
        string $monthName,
        int $year
    ): bool {
        $message = "✅ *PEMBAYARAN DISETUJUI*\n"
            . "━━━━━━━━━━━━━━━━━━━\n"
            . "👤 Nama: *{$userName}*\n"
            . "🏠 Kamar: *{$roomNumber}*\n"
            . "💵 Jumlah: *Rp " . number_format($amount, 0, ',', '.') . "*\n"
            . "📅 Periode: *{$monthName} {$year}*\n"
            . "📌 Status: ✅ *Disetujui*\n"
            . "━━━━━━━━━━━━━━━━━━━\n"
            . "⏰ " . now()->format('d/m/Y H:i');

        return $this->sendToGroup($message);
    }

    /**
     * Kirim notifikasi pembayaran di-reject ke grup
     */
    public function sendRejectionNotification(
        string $userName,
        string $roomNumber,
        float $amount,
        string $monthName,
        int $year,
        string $reason
    ): bool {
        $message = "❌ *PEMBAYARAN DITOLAK*\n"
            . "━━━━━━━━━━━━━━━━━━━\n"
            . "👤 Nama: *{$userName}*\n"
            . "🏠 Kamar: *{$roomNumber}*\n"
            . "💵 Jumlah: *Rp " . number_format($amount, 0, ',', '.') . "*\n"
            . "📅 Periode: *{$monthName} {$year}*\n"
            . "📌 Status: ❌ *Ditolak*\n"
            . "📝 Alasan: {$reason}\n"
            . "━━━━━━━━━━━━━━━━━━━\n"
            . "⏰ " . now()->format('d/m/Y H:i');

        return $this->sendToGroup($message);
    }

    /**
     * Kirim notifikasi pengeluaran baru ke grup
     */
    public function sendExpenseNotification(
        string $category,
        float $amount,
        string $expenseDate,
        ?string $description = null,
        ?string $receiptImageUrl = null
    ): bool {
        $message = "🧾 *PENGELUARAN BARU*\n"
            . "━━━━━━━━━━━━━━━━━━━\n"
            . "📂 Kategori: *{$category}*\n"
            . "💵 Jumlah: *Rp " . number_format($amount, 0, ',', '.') . "*\n"
            . "📅 Tanggal: *{$expenseDate}*\n";

        if ($description) {
            $message .= "📝 Keterangan: {$description}\n";
        }

        $message .= "━━━━━━━━━━━━━━━━━━━\n"
            . "⏰ " . now()->format('d/m/Y H:i');

        return $this->sendToGroup($message, $receiptImageUrl);
    }

    /**
     * Kirim reminder pembayaran ke grup (H-3 / H-2 sebelum jatuh tempo)
     */
    public function sendPaymentReminder(
        array $unpaidUsers,
        int $dueDate,
        string $monthName,
        int $year
    ): bool {
        $message = "🔔 *REMINDER PEMBAYARAN IURAN*\n"
            . "━━━━━━━━━━━━━━━━━━━\n"
            . "📅 Periode: *{$monthName} {$year}*\n"
            . "⏰ Jatuh tempo: *Tanggal {$dueDate}*\n"
            . "━━━━━━━━━━━━━━━━━━━\n\n"
            . "Berikut penghuni yang *belum bayar/belum lunas*:\n\n";

        foreach ($unpaidUsers as $i => $user) {
            $no = $i + 1;
            $remaining = number_format($user['remaining'], 0, ',', '.');
            $message .= "{$no}. *{$user['name']}* (Kamar {$user['room']})\n"
                . "    Sisa: Rp {$remaining}\n";
        }

        $message .= "\n━━━━━━━━━━━━━━━━━━━\n"
            . "_Mohon segera lakukan pembayaran sebelum jatuh tempo. Terima kasih! 🙏_";

        return $this->sendToGroup($message);
    }

    /**
     * Kirim pesan ke nomor personal via WAHA
     */
    public function sendToPersonal(string $phone, string $message): bool
    {
        if (!$this->enabled) {
            return false;
        }

        // Format nomor: hapus 0 di depan, tambah 62
        $phone = preg_replace('/^0/', '62', $phone);
        $phone = preg_replace('/^\+/', '', $phone);
        $chatId = $phone . '@c.us';

        try {
            $response = $this->http()->post('/api/sendText', [
                'chatId' => $chatId,
                'text' => $message,
                'session' => $this->session,
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp personal notification sent', ['phone' => $phone]);
                return true;
            }

            Log::error('WhatsApp personal send failed', [
                'phone' => $phone,
                'status' => $response->status(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp personal error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim reminder personal ke user
     */
    public function sendPersonalReminder(
        string $phone,
        string $userName,
        float $remaining,
        int $dueDate,
        string $monthName,
        int $year
    ): bool {
        $message = "🔔 *Hai {$userName}!*\n\n"
            . "Ini reminder pembayaran iuran kosan kamu:\n\n"
            . "📅 Periode: *{$monthName} {$year}*\n"
            . "💵 Sisa bayar: *Rp " . number_format($remaining, 0, ',', '.') . "*\n"
            . "⏰ Jatuh tempo: *Tanggal {$dueDate}*\n\n"
            . "_Mohon segera lakukan pembayaran ya. Terima kasih! 🙏_";

        return $this->sendToPersonal($phone, $message);
    }
}
