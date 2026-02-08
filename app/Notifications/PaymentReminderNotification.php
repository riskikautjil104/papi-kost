<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $monthlyFee;
    public $dueDate;
    public $roomNumber;
    public $hasPaid;
    public $currentMonth;
    public $currentYear;
    public $paidAmount;

    /**
     * Create a new notification instance.
     */
    public function __construct($monthlyFee, $dueDate, $roomNumber, $hasPaid, $currentMonth, $currentYear, $paidAmount = 0)
    {
        $this->monthlyFee = $monthlyFee;
        $this->dueDate = $dueDate;
        $this->roomNumber = $roomNumber;
        $this->hasPaid = $hasPaid;
        $this->currentMonth = $currentMonth;
        $this->currentYear = $currentYear;
        $this->paidAmount = $paidAmount;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $monthName = $monthNames[$this->currentMonth] ?? 'Bulan ini';
        $remaining = $this->monthlyFee - $this->paidAmount;

        $mail = (new MailMessage)
            ->subject("Pengingat Pembayaran Iuran Kontrakan - {$monthName} {$this->currentYear}")
            ->greeting("Selamat Pagi, {$notifiable->name}!")
            ->line("Ini pengingat pembayaran iuran kontrakan Anda untuk bulan {$monthName} {$this->currentYear}.");

        if ($this->roomNumber) {
            $mail->line("🏠 Kamar: {$this->roomNumber}");
        }

        $mail->line("💰 Iuran Bulanan: Rp " . number_format($this->monthlyFee, 0, ',', '.'));

        if ($this->hasPaid) {
            $mail->line("✅ Status: LUNAS")
                 ->line("Terima kasih telah melakukan pembayaran tepat waktu! 🙏");
        } else {
            $mail->line("⚠️ Status: BELUM LUNAS")
                 ->line("📅 Jatuh Tempo: Tanggal {$this->dueDate} setiap bulan")
                 ->line("💵 Sisa Pembayaran: Rp " . number_format($remaining, 0, ',', '.'))
                 ->action('Bayar Sekarang', url('/user/payments/create'))
                 ->line("Silakan lakukan pembayaran sebelum tanggal jatuh tempo.");
        }

        $mail->line("Jika ada pertanyaan, silakan hubungi admin kontrakan.")
             ->salutation("Salam hangat,\nTim Kontrakan");

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'monthly_fee' => $this->monthlyFee,
            'due_date' => $this->dueDate,
            'room_number' => $this->roomNumber,
            'has_paid' => $this->hasPaid,
            'month' => $this->currentMonth,
            'year' => $this->currentYear,
            'paid_amount' => $this->paidAmount
        ];
    }
}
