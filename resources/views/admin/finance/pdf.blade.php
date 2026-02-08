<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan - {{ $month }}/{{ $year }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .header p {
            color: #666;
            font-size: 14px;
        }
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .summary-row {
            display: table-row;
        }
        .summary-cell {
            display: table-cell;
            padding: 10px;
            border: 1px solid #ddd;
        }
        .summary-header {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .expenses {
            margin-top: 30px;
        }
        .expenses h2 {
            color: #333;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .total {
            font-weight: bold;
            background-color: #e8f4f8;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Keuangan Kontrakan</h1>
        <p>Periode: {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}</p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-row">
            <div class="summary-cell summary-header">Saldo Dompet Saat Ini</div>
            <div class="summary-cell">Rp {{ number_format($walletBalance, 0, ',', '.') }}</div>
        </div>
        <div class="summary-row">
            <div class="summary-cell summary-header">Total Pemasukan Bulan Ini</div>
            <div class="summary-cell">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</div>
        </div>
        <div class="summary-row">
            <div class="summary-cell summary-header">Total Pengeluaran Bulan Ini</div>
            <div class="summary-cell">Rp {{ number_format($monthlyExpense, 0, ',', '.') }}</div>
        </div>
        <div class="summary-row">
            <div class="summary-cell summary-header total">Selisih (Pemasukan - Pengeluaran)</div>
            <div class="summary-cell total">Rp {{ number_format($monthlyIncome - $monthlyExpense, 0, ',', '.') }}</div>
        </div>
    </div>

    @if($expenses->count() > 0)
    <div class="expenses">
        <h2>Detail Pengeluaran</h2>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Deskripsi</th>
                    <th>Dibuat Oleh</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $expense)
                <tr>
                    <td>{{ $expense->expense_date->format('d/m/Y') }}</td>
                    <td>{{ $expense->category_label ?? $expense->category }}</td>
                    <td>Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                    <td>{{ $expense->description ?? '-' }}</td>
                    <td>{{ $expense->creator->name }}</td>
                </tr>
                @endforeach
                <tr class="total">
                    <td colspan="2"><strong>Total Pengeluaran</strong></td>
                    <td><strong>Rp {{ number_format($expenses->sum('amount'), 0, ',', '.') }}</strong></td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem manajemen kontrakan</p>
    </div>
</body>
</html>
