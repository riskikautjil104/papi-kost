<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Tahunan {{ $year }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .total-row { background-color: #e9ecef; font-weight: bold; }
        .summary-box { 
            border: 1px solid #ddd; 
            padding: 15px; 
            margin-bottom: 20px;
            background-color: #f8f9fa;
        }
        .summary-item { margin-bottom: 10px; }
        .summary-label { font-weight: bold; display: inline-block; width: 150px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN TAHUNAN KONTRAKAN</h1>
        <p>Tahun: {{ $year }}</p>
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <div class="summary-box">
        <div class="summary-item">
            <span class="summary-label">Total Pemasukan:</span>
            <span>Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Pengeluaran:</span>
            <span>Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Saldo Akhir:</span>
            <span>Rp {{ number_format($totalBalance, 0, ',', '.') }}</span>
        </div>
    </div>

    <h3>Rincian Bulanan</h3>
    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th class="text-right">Pemasukan (Rp)</th>
                <th class="text-right">Pengeluaran (Rp)</th>
                <th class="text-right">Saldo (Rp)</th>
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
            @foreach($monthlyData as $month => $data)
            <tr>
                <td>{{ $monthNames[$month] }}</td>
                <td class="text-right">{{ number_format($data['income'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($data['expense'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($data['balance'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td>TOTAL</td>
                <td class="text-right">{{ number_format($totalIncome, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalExpense, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalBalance, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <h3>Pengeluaran per Kategori</h3>
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th class="text-right">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php
            $categoryLabels = [
                'listrik' => 'Listrik',
                'air' => 'Air',
                'internet' => 'Internet',
                'kebersihan' => 'Kebersihan',
                'perbaikan' => 'Perbaikan',
                'perlengkapan' => 'Perlengkapan',
                'lainnya' => 'Lainnya'
            ];
            @endphp
            @forelse($expenseByCategory as $category)
            <tr>
                <td>{{ $categoryLabels[$category->category] ?? ucfirst($category->category) }}</td>
                <td class="text-right">{{ number_format($category->total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" style="text-align: center;">Tidak ada data pengeluaran</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
