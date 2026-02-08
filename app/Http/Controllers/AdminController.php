<?php

namespace App\Http\Controllers;

use App\Models\UsersExtended;
use App\Models\PaymentProof;
use App\Models\Expense;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Stats utama
        $totalUsers = UsersExtended::where('status', 'active')->count();
        $totalIncome = Transaction::where('type', 'income')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('amount');
        $walletBalance = Wallet::getCurrentBalance();
        $pendingPayments = PaymentProof::where('status', 'pending')->count();

        // Tunggakan per orang
        $usersWithDebt = UsersExtended::with('user')
            ->where('status', 'active')
            ->get()
            ->map(function ($user) use ($currentMonth, $currentYear) {
                $paidAmount = PaymentProof::where('users_extended_id', $user->id)
                    ->where('status', 'approved')
                    ->whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear)
                    ->sum('amount');

                $user->paid_amount = $paidAmount;
                $user->remaining = $user->monthly_fee - $paidAmount;
                $user->is_overdue = $paidAmount < $user->monthly_fee;

                return $user;
            })
            ->sortByDesc('remaining');

        // Recent payments
        $recentPayments = PaymentProof::with(['userExtended' => function($query) {
            $query->with('user');
        }])
            ->latest()
            ->take(10)
            ->get();

        // Monthly stats untuk chart (12 bulan)
        $monthlyStats = Transaction::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income'),
            DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expense')
        )
            ->whereYear('created_at', $currentYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get()
            ->keyBy('month');

        // Expense by category untuk chart
        $expenseByCategory = Expense::select('category', DB::raw('SUM(amount) as total'))
            ->whereMonth('expense_date', $currentMonth)
            ->whereYear('expense_date', $currentYear)
            ->groupBy('category')
            ->get();

        // Prepare labels untuk chart
        $categoryLabels = [
            'listrik' => 'Listrik',
            'air' => 'Air',
            'internet' => 'Internet',
            'kebersihan' => 'Kebersihan',
            'perbaikan' => 'Perbaikan',
            'perlengkapan' => 'Perlengkapan',
            'lainnya' => 'Lainnya'
        ];

        $expenseLabels = $expenseByCategory->pluck('category')->map(function ($cat) use ($categoryLabels) {
            return $categoryLabels[$cat] ?? ucfirst($cat);
        })->toArray();

        $expenseValues = $expenseByCategory->pluck('total')->toArray();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalIncome',
            'totalExpense',
            'walletBalance',
            'pendingPayments',
            'usersWithDebt',
            'recentPayments',
            'monthlyStats',
            'expenseByCategory',
            'expenseLabels',
            'expenseValues',
            'currentMonth',
            'currentYear'
        ));
    }
}
