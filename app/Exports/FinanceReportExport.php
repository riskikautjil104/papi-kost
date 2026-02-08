<?php

namespace App\Exports;

use App\Models\Transaction;
use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FinanceReportExport implements FromCollection, WithHeadings
{
    protected $year;
    protected $month;

    public function __construct($year, $month)
    {
        $this->year = $year;
        $this->month = $month;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $transactions = Transaction::with('paymentProof.userExtended.user')
            ->whereMonth('created_at', $this->month)
            ->whereYear('created_at', $this->year)
            ->get();

        $expenses = Expense::with('creator')
            ->whereMonth('expense_date', $this->month)
            ->whereYear('expense_date', $this->year)
            ->get();

        $data = [];

        // Add transactions
        foreach ($transactions as $transaction) {
            $data[] = [
                'Tanggal' => $transaction->created_at->format('Y-m-d'),
                'Tipe' => $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran',
                'Jumlah' => $transaction->amount,
                'Deskripsi' => $transaction->description,
                'User' => $transaction->paymentProof ? $transaction->paymentProof->userExtended->user->name : '-',
                'Bulan' => $transaction->month,
                'Tahun' => $transaction->year,
            ];
        }

        // Add expenses
        foreach ($expenses as $expense) {
            $data[] = [
                'Tanggal' => $expense->expense_date->format('Y-m-d'),
                'Tipe' => 'Pengeluaran',
                'Jumlah' => $expense->amount,
                'Deskripsi' => $expense->description,
                'User' => $expense->creator->name,
                'Bulan' => date('m', strtotime($expense->expense_date)),
                'Tahun' => date('Y', strtotime($expense->expense_date)),
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Tipe',
            'Jumlah',
            'Deskripsi',
            'User',
            'Bulan',
            'Tahun',
        ];
    }
}
