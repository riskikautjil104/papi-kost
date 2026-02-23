<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsersExtended;
use Carbon\Carbon;

class AllDuesController extends Controller
{
    public function index(Request $request)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $users = UsersExtended::with('user')->get()->map(function ($user) use ($currentMonth, $currentYear, $months) {
            $monthlyFee = $user->monthly_fee;
            $paidMonths = $user->paymentProofs()
                ->where('status', 'approved')
                ->where('year', $currentYear)
                ->pluck('month')
                ->toArray();

            $paidMonthsList = collect($paidMonths)->unique()->sort()->map(fn($m) => $months[$m])->values()->all();

            $unpaidMonths = collect(range(1, $currentMonth))
                ->diff($paidMonths)
                ->sort()
                ->map(fn($m) => $months[$m])
                ->values()
                ->all();

            $totalPaid = $user->paymentProofs()
                ->where('status', 'approved')
                ->where('year', $currentYear)
                ->sum('amount');
            $expectedTotal = $monthlyFee * $currentMonth;
            $remaining = $expectedTotal - $totalPaid;
            $user->total_paid = $totalPaid;
            $user->remaining = $remaining;
            $user->paid_months = $paidMonthsList;
            $user->unpaid_months = $unpaidMonths;
            return $user;
        });

        return view('user.all-dues', compact('users', 'months', 'currentYear'));
    }
}
