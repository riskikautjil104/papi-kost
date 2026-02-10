<?php

namespace App\Exports;

use App\Models\PaymentProof;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PaymentHistoryExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    protected $user;
    protected $year;

    public function __construct($user, $year)
    {
        $this->user = $user;
        $this->year = $year;
    }

    public function collection()
    {
        return PaymentProof::where('users_extended_id', $this->user->id)
            ->where('year', $this->year)
            ->with('approver')
            ->orderBy('month', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Bulan',
            'Tahun',
            'Jumlah (Rp)',
            'Metode Pembayaran',
            'Status',
            'Tanggal Bayar',
            'Disetujui Oleh',
            'Tanggal Disetujui',
            'Keterangan'
        ];
    }

    public function map($payment, $index = 0): array
    {
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $statusLabels = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak'
        ];

        return [
            $index + 1,
            $monthNames[$payment->month] ?? $payment->month,
            $payment->year,
            number_format($payment->amount, 0, ',', '.'),
            $payment->payment_method,
            $statusLabels[$payment->status] ?? $payment->status,
            $payment->created_at ? $payment->created_at->format('d/m/Y H:i') : '-',
            $payment->approver ? $payment->approver->name : '-',
            $payment->approved_at ? $payment->approved_at->format('d/m/Y H:i') : '-',
            $payment->description ?? '-'
        ];
    }

    public function title(): string
    {
        return 'Riwayat Pembayaran ' . $this->year;
    }
}
