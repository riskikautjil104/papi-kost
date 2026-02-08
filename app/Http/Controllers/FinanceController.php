<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Exports\FinanceReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        // Income summary
        $monthlyIncome = Transaction::where('type', 'income')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('amount');

        // Expense summary
        $monthlyExpense = Transaction::where('type', 'expense')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('amount');

        $walletBalance = Wallet::getCurrentBalance();

        // Expenses list
        $expenses = Expense::with('creator')
            ->whereMonth('expense_date', $month)
            ->whereYear('expense_date', $year)
            ->orderBy('expense_date', 'desc')
            ->paginate(15);

        // Expense by category
        $expenseByCategory = Expense::select('category', DB::raw('SUM(amount) as total'))
            ->whereMonth('expense_date', $month)
            ->whereYear('expense_date', $year)
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        // Monthly comparison
        $monthlyComparison = Transaction::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income'),
            DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expense')
        )
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get()
            ->keyBy('month');

        return view('admin.finance.index', compact(
            'monthlyIncome',
            'monthlyExpense',
            'walletBalance',
            'expenses',
            'expenseByCategory',
            'monthlyComparison',
            'year',
            'month'
        ));
    }

    public function createExpense()
    {
        $categories = Expense::getCategories();
        return view('admin.finance.create-expense', compact('categories'));
    }

    public function storeExpense(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'category' => $request->category,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'description' => $request->description,
            'created_by' => Auth::id()
        ];

        if ($request->hasFile('receipt_image')) {
            $data['receipt_image'] = $request->file('receipt_image')->store('receipts', 'public');
        }

        DB::transaction(function () use ($request, $data) {
            // Create expense record
            $expense = Expense::create($data);

            // Create transaction record
            Transaction::create([
                'payment_proof_id' => null,
                'type' => 'expense',
                'amount' => $data['amount'],
                'month' => date('m', strtotime($request->expense_date)),
                'year' => date('Y', strtotime($request->expense_date)),
                'description' => "Pengeluaran - {$expense->category_label}: {$request->description}"
            ]);

            // Update wallet balance
            Wallet::updateBalance($data['amount'], 'expense');
        });

        return redirect()->route('admin.finance.index')
            ->with('success', 'Pengeluaran berhasil dicatat!');
    }

    public function destroyExpense(Expense $expense)
    {
        DB::transaction(function () use ($expense) {
            // Reverse wallet balance
            Wallet::updateBalance($expense->amount, 'income');

            // Delete related transactions
            Transaction::where('description', 'like', "%{$expense->category_label}:%")
                ->where('amount', $expense->amount)
                ->where('type', 'expense')
                ->delete();

            // Delete expense
            $expense->delete();
        });

        return redirect()->back()
            ->with('success', 'Pengeluaran berhasil dihapus!');
    }

    public function report(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        // Annual summary
        $annualIncome = Transaction::where('type', 'income')
            ->whereYear('created_at', $year)
            ->sum('amount');

        $annualExpense = Transaction::where('type', 'expense')
            ->whereYear('created_at', $year)
            ->sum('amount');

        // Monthly breakdown
        $monthlyBreakdown = Transaction::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income'),
            DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expense')
        )
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        // Top expenses by category
        $categoryBreakdown = Expense::select('category', DB::raw('SUM(amount) as total'))
            ->whereYear('expense_date', $year)
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();

        return view('admin.finance.report', compact(
            'annualIncome',
            'annualExpense',
            'monthlyBreakdown',
            'categoryBreakdown',
            'year',
            'month'
        ));
    }

    public function exportPdf(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $data = [
            'year' => $year,
            'month' => $month,
            'walletBalance' => Wallet::getCurrentBalance(),
            'monthlyIncome' => Transaction::where('type', 'income')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('amount'),
            'monthlyExpense' => Transaction::where('type', 'expense')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('amount'),
            'expenses' => Expense::with('creator')
                ->whereMonth('expense_date', $month)
                ->whereYear('expense_date', $year)
                ->get()
        ];

        return view('admin.finance.pdf', $data);
    }

    public function exportExcel(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        return Excel::download(
            new FinanceReportExport($year, $month),
            "laporan_keuangan_{$year}_{$month}.xlsx"
        );
    }
}
