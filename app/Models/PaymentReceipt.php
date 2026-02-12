<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_proof_id',
        'receipt_number',
        'tenant_name',
        'room_number',
        'amount',
        'month',
        'year',
        'payment_method',
        'description',
        'approved_by_name',
        'issued_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'issued_at' => 'datetime',
    ];

    /**
     * Relasi ke PaymentProof
     */
    public function paymentProof(): BelongsTo
    {
        return $this->belongsTo(PaymentProof::class);
    }

    /**
     * Generate nomor kwitansi unik
     * Format: KWT-YYYYMM-XXXXX
     */
    public static function generateReceiptNumber(int $month, int $year): string
    {
        $prefix = 'KWT-' . $year . str_pad($month, 2, '0', STR_PAD_LEFT);

        // Cari nomor urut terakhir untuk bulan & tahun ini
        $lastReceipt = self::where('receipt_number', 'like', $prefix . '%')
            ->orderBy('receipt_number', 'desc')
            ->first();

        if ($lastReceipt) {
            $lastNumber = (int) substr($lastReceipt->receipt_number, -5);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Auto-generate kwitansi dari PaymentProof yang di-approve
     */
    public static function generateFromPayment(PaymentProof $payment): self
    {
        $payment->load(['userExtended.user', 'approver']);

        $receiptNumber = self::generateReceiptNumber($payment->month, $payment->year);

        return self::create([
            'payment_proof_id' => $payment->id,
            'receipt_number' => $receiptNumber,
            'tenant_name' => $payment->userExtended?->user?->name ?? 'Unknown',
            'room_number' => $payment->userExtended?->room_number ?? '-',
            'amount' => $payment->amount,
            'month' => $payment->month,
            'year' => $payment->year,
            'payment_method' => $payment->payment_method,
            'description' => "Pembayaran iuran {$payment->month_name} {$payment->year}",
            'approved_by_name' => $payment->approver?->name ?? 'Admin',
            'issued_at' => now(),
        ]);
    }

    /**
     * Accessor: Nama bulan dalam Bahasa Indonesia
     */
    public function getMonthNameAttribute(): string
    {
        $months = [
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
            12 => 'Desember',
        ];

        return $months[$this->month] ?? '';
    }

    /**
     * Accessor: Periode lengkap (Bulan Tahun)
     */
    public function getPeriodAttribute(): string
    {
        return $this->month_name . ' ' . $this->year;
    }

    /**
     * Accessor: Label metode pembayaran
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        $labels = [
            'transfer' => 'Transfer Bank',
            'cash' => 'Tunai',
            'e-wallet' => 'E-Wallet',
        ];

        return $labels[$this->payment_method] ?? ucfirst($this->payment_method);
    }

    /**
     * Terbilang - konversi angka ke huruf (Bahasa Indonesia)
     */
    public static function terbilang(float $angka): string
    {
        $angka = abs($angka);
        $huruf = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
        $temp = '';

        if ($angka < 12) {
            $temp = ' ' . $huruf[(int)$angka];
        } elseif ($angka < 20) {
            $temp = self::terbilang($angka - 10) . ' Belas';
        } elseif ($angka < 100) {
            $temp = self::terbilang($angka / 10) . ' Puluh' . self::terbilang($angka % 10);
        } elseif ($angka < 200) {
            $temp = ' Seratus' . self::terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $temp = self::terbilang($angka / 100) . ' Ratus' . self::terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $temp = ' Seribu' . self::terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $temp = self::terbilang($angka / 1000) . ' Ribu' . self::terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $temp = self::terbilang($angka / 1000000) . ' Juta' . self::terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            $temp = self::terbilang($angka / 1000000000) . ' Miliar' . self::terbilang($angka % 1000000000);
        } elseif ($angka < 1000000000000000) {
            $temp = self::terbilang($angka / 1000000000000) . ' Triliun' . self::terbilang($angka % 1000000000000);
        }

        return trim($temp);
    }
}
