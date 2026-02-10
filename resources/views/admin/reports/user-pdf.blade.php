<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Riwayat Pembayaran - {{ $user->user->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0; color: #666; }
        .user-info { 
            border: 1px solid #ddd; 
            padding: 15px; 
            margin-bottom: 20px;
            background-color: #f8f9fa;
        }
        .user-info-row { margin-bottom: 8px; }
        .user-info-label { font-weight: bold; display: inline-block; width: 150px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .summary-box { 
            border: 1px solid #ddd; 
            padding: 15px; 
            margin-bottom: 20px;
            background-color: #e9ecef;
        }
        .summary-item { margin-bottom: 10px; }
        .summary-label { font-weight: bold; display: inline-block; width: 200px; }
        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }
        .status-approved { background-color: #28a745; color: white; }
        .status-pending { background-color: #ffc107; color: black; }
        .status-rejected { background-color: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h1>RiWAYAT PEMBAYARAN KONTRAKAN</h1>
        <p>Tahun: {{ $year }}</p>
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <div class="user-info">
        <div class="user-info-row">
            <span class="user-info-label">Nama:</span>
            <span>{{ $user->user->name }}</span>
        </div>
        <div class="user-info-row">
            <span class="user-info-label">Email:</span>
            <span>{{ $user->user->email }}</span>
        </div>
        <div class="user-info-row">
            <span class="user-info-label">No. Telepon:</span>
            <span>{{ $user->phone }}</span>
        </div>
        <div class="user-info-row">
            <span class="user-info-label">Kamar:</span>
            <span>{{ $user->room_number ?? '-' }}</span>
        </div>
        <div class="user-info-row">
            <span class="user-info-label">Iuran/Bulan:</span>
            <span>Rp {{ number_format($user->monthly_fee, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="summary-box">
        <div class="summary-item">
            <span class="summary-label">Total Dibayar:</span>
            <span>Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Bulan Lunas:</span>
            <span>{{ $monthsPaid }} / 12</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total yang Harus Dibayar:</span>
            <span>Rp {{ number_format($expectedTotal, 0, ',', '.') }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Status:</span>
            <span>
                @if($remaining <= 0)
                    <span class="status-badge status-approved">LUNAS</span>
                @else
                    <span class="status-badge status-pending">TUNGGAKAN: Rp {{ number_format($remaining, 0, ',', '.') }}</span>
                @endif
            </span>
        </div>
    </div>

    <h3>Detail Pembayaran</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Bulan</th>
                <th>Jumlah (Rp)</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Tanggal Bayar</th>
                <th>Disetujui Oleh</th>
            </tr>
        </thead>
        <tbody>
            @php
            $monthNames = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            @endphp
            @forelse($payments as $index => $payment)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $monthNames[$payment->month] ?? $payment->month }} {{ $payment->year }}</td>
                <td class="text-right">{{ number_format($payment->amount, 0, ',', '.') }}</td>
                <td>{{ ucfirst($payment->payment_method) }}</td>
                <td>
                    @if($payment->status == 'approved')
                        <span class="status-badge status-approved">Disetujui</span>
                    @elseif($payment->status == 'pending')
                        <span class="status-badge status-pending">Pending</span>
                    @else
                        <span class="status-badge status-rejected">Ditolak</span>
                    @endif
                </td>
                <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $payment->approver ? $payment->approver->name : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">Belum ada riwayat pembayaran</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right;">
        <p>Dicetak oleh: {{ auth()->user()->name ?? 'Administrator' }}</p>
        <p>Tanggal: {{ now()->format('d F Y') }}</p>
    </div>
</body>
</html>
