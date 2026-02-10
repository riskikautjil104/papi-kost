<?php

namespace App\Exports;

use App\Models\Transaction;
use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AnnualReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    protected $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    public function collection()
    {
        $data = collect();
        
        // Monthly data
        for ($month = 1; $month <= 12; $month++) {
            $income = Transaction::where('type', 'income')
                ->where('month', $month)
                ->where('year', $this->year)
                ->sum('amount');
            
            $expense = Transaction::where('type', 'expense')
                ->where('month', $month)
                ->where('year', $this->year)
                ->sum('amount');
            
            $monthNames = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            
            $data->push([
                'month_name' => $monthNames[$month],
                'income' => $income,
                'expense' => $expense,
                'balance' => $income - $expense
            ]);
        }
        
        // Total row
        $totalIncome = $data->sum('income');
        $totalExpense = $data->sum('expense');
        
        $data->push([
            'month_name' => 'TOTAL',
            'income' => $totalIncome,
            'expense' => $totalExpense,
            'balance' => $totalIncome - $totalExpense
        ]);
        
        return $data;
    }

    public function headings(): array
    {
        return [
            'Bulan',
            'Pemasukan (Rp)',
            'Pengeluaran (Rp)',
            'Saldo (Rp)'
        ];
    }

    public function map($row): array
    {
        return [
            $row['month_name'],
            number_format($row['income'], 0, ',', '.'),
            number_format($row['expense'], 0, ',', '.'),
            number_format($row['balance'], 0, ',', '.')
        ];
    }

    public function title(): string
    {
        return 'Laporan Tahun ' . $this->year;
    }
}
