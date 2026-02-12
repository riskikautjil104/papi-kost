<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kwitansi {{ $receipt->receipt_number }}</title>
    <style>
        @page {
            margin: 15mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .receipt-container {
            border: 2px solid #2c3e50;
            padding: 20px;
            position: relative;
        }
        .receipt-container::before {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            right: 3px;
            bottom: 3px;
            border: 1px solid #bdc3c7;
        }

        /* Header */
        .header {
            text-align: center;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 12px;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #2c3e50;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .header .subtitle {
            font-size: 13px;
            color: #7f8c8d;
            margin-top: 4px;
        }
        .receipt-number {
            position: absolute;
            right: 10px;
            top: 5px;
            font-size: 10px;
            color: #95a5a6;
            z-index: 2;
        }

        /* Info Grid */
        .info-grid {
            width: 100%;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }
        .info-grid td {
            padding: 4px 8px;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            width: 140px;
            color: #2c3e50;
        }
        .info-separator {
            width: 10px;
            text-align: center;
        }
        .info-value {
            color: #333;
        }

        /* Amount Box */
        .amount-box {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: #fff;
            padding: 15px 20px;
            margin: 15px 0;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        .amount-box .amount-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
        }
        .amount-box .amount-value {
            font-size: 24px;
            font-weight: bold;
            margin: 5px 0;
        }
        .amount-box .amount-terbilang {
            font-size: 10px;
            font-style: italic;
            opacity: 0.9;
        }

        /* Description */
        .description-box {
            border: 1px solid #ecf0f1;
            background-color: #f9f9f9;
            padding: 10px 15px;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }
        .description-box .desc-label {
            font-weight: bold;
            color: #2c3e50;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .description-box .desc-value {
            margin-top: 5px;
            color: #555;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            position: relative;
            z-index: 1;
        }
        .footer-grid {
            width: 100%;
        }
        .footer-grid td {
            vertical-align: top;
            padding: 5px;
        }
        .stamp-area {
            text-align: center;
            padding-top: 10px;
        }
        .stamp-box {
            display: inline-block;
            border: 2px solid #27ae60;
            color: #27ae60;
            padding: 8px 20px;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 2px;
            transform: rotate(-5deg);
            opacity: 0.8;
        }
        .signature-area {
            text-align: center;
            padding-top: 5px;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            width: 150px;
            margin: 40px auto 5px;
        }
        .signature-name {
            font-weight: bold;
            font-size: 11px;
        }
        .signature-title {
            font-size: 9px;
            color: #7f8c8d;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 60px;
            color: rgba(39, 174, 96, 0.06);
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 10px;
            z-index: 0;
            white-space: nowrap;
        }

        /* Print date */
        .print-info {
            text-align: center;
            font-size: 8px;
            color: #bdc3c7;
            margin-top: 10px;
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="watermark">LUNAS</div>

        <div class="receipt-number">{{ $receipt->receipt_number }}</div>

        <!-- Header -->
        <div class="header">
            <h1>KWITANSI</h1>
            <div class="subtitle">Kontrakan D-TECT — Bukti Pembayaran Resmi</div>
        </div>

        <!-- Info Grid -->
        <table class="info-grid">
            <tr>
                <td class="info-label">No. Kwitansi</td>
                <td class="info-separator">:</td>
                <td class="info-value"><strong>{{ $receipt->receipt_number }}</strong></td>
                <td class="info-label">Tanggal</td>
                <td class="info-separator">:</td>
                <td class="info-value">{{ $receipt->issued_at->format('d F Y') }}</td>
            </tr>
            <tr>
                <td class="info-label">Diterima Dari</td>
                <td class="info-separator">:</td>
                <td class="info-value"><strong>{{ $receipt->tenant_name }}</strong></td>
                <td class="info-label">Kamar</td>
                <td class="info-separator">:</td>
                <td class="info-value">{{ $receipt->room_number ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Periode</td>
                <td class="info-separator">:</td>
                <td class="info-value">{{ $receipt->period }}</td>
                <td class="info-label">Metode Bayar</td>
                <td class="info-separator">:</td>
                <td class="info-value">{{ $receipt->payment_method_label }}</td>
            </tr>
        </table>

        <!-- Amount -->
        <div class="amount-box">
            <div class="amount-label">Jumlah Pembayaran</div>
            <div class="amount-value">Rp {{ number_format($receipt->amount, 0, ',', '.') }}</div>
            <div class="amount-terbilang">( {{ $terbilang }} )</div>
        </div>

        <!-- Description -->
        <div class="description-box">
            <div class="desc-label">Untuk Pembayaran</div>
            <div class="desc-value">{{ $receipt->description ?? 'Pembayaran iuran bulanan kontrakan' }}</div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <table class="footer-grid">
                <tr>
                    <td style="width: 50%;">
                        <div class="stamp-area">
                            <div class="stamp-box">✓ LUNAS</div>
                        </div>
                    </td>
                    <td style="width: 50%;">
                        <div class="signature-area">
                            <div style="font-size: 10px; color: #7f8c8d;">{{ $receipt->issued_at->format('d F Y') }}</div>
                            <div class="signature-line"></div>
                            <div class="signature-name">{{ $receipt->approved_by_name }}</div>
                            <div class="signature-title">Pengelola Kontrakan</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="print-info">
            Dokumen ini di-generate secara otomatis oleh sistem D-TECT pada {{ now()->format('d/m/Y H:i') }} WIB
        </div>
    </div>
</body>
</html>
