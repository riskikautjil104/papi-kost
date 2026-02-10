<?php

namespace App\Http\Controllers;

use App\Models\UsersExtended;
use App\Models\PaymentProof;
use App\Models\Transaction;
use App\Models\Expense;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentHistoryExport;
use App\Exports\AnnualReportExport;

class ReportController extends Controller
{
    /**
     * Laporan Tahunan
     */
    public function annual(Request $request)
    {
        $year = $request->get('year', now()->year);
        
        // Data per bulan
        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $income = Transaction::where('type', 'income')
                ->where('month', $month)
                ->where('year', $year)
                ->sum('amount');
            
            $expense = Transaction::where('type', 'expense')
                ->where('month', $month)
                ->where('year', $year)
                ->sum('amount');
            
            $monthlyData[$month] = [
                'income' => $income,
                'expense' => $expense,
                'balance' => $income - $expense
            ];
        }

        // Total tahunan
        $totalIncome = array_sum(array_column($monthlyData, 'income'));
        $totalExpense = array_sum(array_column($monthlyData, 'expense'));
        $totalBalance = $totalIncome - $totalExpense;

        // Expense by category
        $expenseByCategory = Expense::select('category', DB::raw('SUM(amount) as total'))
            ->whereYear('expense_date', $year)
            ->groupBy('category')
            ->get();

        // User payment summary
        $userPayments = UsersExtended::with('user')
            ->where('status', 'active')
            ->get()
            ->map(function ($user) use ($year) {
                $totalPaid = PaymentProof::where('users_extended_id', $user->id)
                    ->where('status', 'approved')
                    ->where('year', $year)
                    ->sum('amount');
                
                $expected = $user->monthly_fee * 12;
                
                $user->total_paid = $totalPaid;
                $user->expected = $expected;
                $user->remaining = max(0, $expected - $totalPaid);
                
                return $user;
            });

        return view('admin.reports.annual', compact(
            'year',
            'monthlyData',
            'totalIncome',
            'totalExpense',
            'totalBalance',
            'expenseByCategory',
            'userPayments'
        ));
    }

    /**
     * Laporan Per User - Riwayat Pembayaran
     */
    public function userHistory(Request $request, UsersExtended $user = null)
    {
        // If no user specified, show list
        if (!$user) {
            $users = UsersExtended::with('user')
                ->where('status', 'active')
                ->get();
            return view('admin.reports.user-list', compact('users'));
        }

        $user->load('user');
        
        $year = $request->get('year', now()->year);
        
        // Get all payments for this user
        $payments = PaymentProof::where('users_extended_id', $user->id)
            ->where('year', $year)
            ->with('approver')
            ->orderBy('month', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate summary
        $totalPaid = $payments->where('status', 'approved')->sum('amount');
        $totalPending = $payments->where('status', 'pending')->sum('amount');
        $monthsPaid = $payments->where('status', 'approved')->count();
        $expectedTotal = $user->monthly_fee * 12;
        $remaining = max(0, $expectedTotal - $totalPaid);

        // Monthly status
        $monthlyStatus = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthPayment = $payments->firstWhere('month', $month);
            $monthlyStatus[$month] = [
                'status' => $monthPayment ? $monthPayment->status : 'unpaid',
                'amount' => $monthPayment ? $monthPayment->amount : 0,
                'date' => $monthPayment ? $monthPayment->created_at : null
            ];
        }

        return view('admin.reports.user-history', compact(
            'user',
            'year',
            'payments',
            'totalPaid',
            'totalPending',
            'monthsPaid',
            'expectedTotal',
            'remaining',
            'monthlyStatus'
        ));
    }

    /**
     * Export Laporan Tahunan - PDF
     */
    public function exportAnnualPdf(Request $request)
    {
        $year = $request->get('year', now()->year);
        
        // Get data (same as annual method)
        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $income = Transaction::where('type', 'income')
                ->where('month', $month)
                ->where('year', $year)
                ->sum('amount');
            
            $expense = Transaction::where('type', 'expense')
                ->where('month', $month)
                ->where('year', $year)
                ->sum('amount');
            
            $monthlyData[$month] = [
                'income' => $income,
                'expense' => $expense,
                'balance' => $income - $expense
            ];
        }

        $totalIncome = array_sum(array_column($monthlyData, 'income'));
        $totalExpense = array_sum(array_column($monthlyData, 'expense'));
        $totalBalance = $totalIncome - $totalExpense;

        $expenseByCategory = Expense::select('category', DB::raw('SUM(amount) as total'))
            ->whereYear('expense_date', $year)
            ->groupBy('category')
            ->get();

        $pdf = Pdf::loadView('admin.reports.annual-pdf', compact(
            'year',
            'monthlyData',
            'totalIncome',
            'totalExpense',
            'totalBalance',
            'expenseByCategory'
        ));

        return $pdf->download("laporan-tahunan-{$year}.pdf");
    }

    /**
     * Export Laporan Per User - PDF
     */
    public function exportUserPdf(Request $request, UsersExtended $user)
    {
        $user->load('user');
        $year = $request->get('year', now()->year);
        
        $payments = PaymentProof::where('users_extended_id', $user->id)
            ->where('year', $year)
            ->with('approver')
            ->orderBy('month', 'asc')
            ->get();

        $totalPaid = $payments->where('status', 'approved')->sum('amount');
        $monthsPaid = $payments->where('status', 'approved')->count();
        $expectedTotal = $user->monthly_fee * 12;
        $remaining = max(0, $expectedTotal - $totalPaid);

        $pdf = Pdf::loadView('admin.reports.user-pdf', compact(
            'user',
            'year',
            'payments',
            'totalPaid',
            'monthsPaid',
            'expectedTotal',
            'remaining'
        ));

        return $pdf->download("laporan-{$user->user->name}-{$year}.pdf");
    }

    /**
     * Export Laporan Tahunan - Excel
     */
    public function exportAnnualExcel(Request $request)
    {
        $year = $request->get('year', now()->year);
        return Excel::download(new AnnualReportExport($year), "laporan-tahunan-{$year}.xlsx");
    }

    /**
     * Export Laporan Per User - Excel
     */
    public function exportUserExcel(Request $request, UsersExtended $user)
    {
        $year = $request->get('year', now()->year);
        return Excel::download(new PaymentHistoryExport($user, $year), "riwayat-{$user->user->name}-{$year}.xlsx");
    }
}
