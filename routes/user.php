<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentReceiptController;

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

Route::prefix('user')->name('user.')->middleware(['auth', 'verified', 'user'])->group(function () {
    // Dashboard user
    Route::get('/dashboard', function () {
        $user = \App\Models\User::with('usersExtended')->where('id', auth()->id())->first();

        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Get filter parameters
        $filterMonth = request('month', $currentMonth);
        $filterYear = request('year', $currentYear);

        // Get payments for current user
        $payments = collect([]);
        $totalPaid = 0;
        $remaining = 0;
        $paidMonths = [];
        $totalIncome = 0;
        $totalExpense = 0;
        $walletBalance = 0;

        if ($user && $user->usersExtended) {
            // Get all payments for this user
            $paymentsQuery = \App\Models\PaymentProof::where('users_extended_id', $user->usersExtended->id)
                ->orderBy('created_at', 'desc');

            // Apply filters if provided
            if (request('month') && request('month') !== 'all') {
                $paymentsQuery->where('month', request('month'));
            }
            if (request('year') && request('year') !== 'all') {
                $paymentsQuery->where('year', request('year'));
            }

            $payments = $paymentsQuery->take(10)->get();

            // Calculate total paid for filtered period
            $totalPaidQuery = \App\Models\PaymentProof::where('users_extended_id', $user->usersExtended->id)
                ->where('status', 'approved');

            if (request('month') && request('month') !== 'all') {
                $totalPaidQuery->where('month', request('month'));
            }
            if (request('year') && request('year') !== 'all') {
                $totalPaidQuery->where('year', request('year'));
            } else {
                $totalPaidQuery->whereYear('created_at', $filterYear);
            }

            $totalPaid = $totalPaidQuery->sum('amount');

            // Calculate remaining/tunggakan
            $monthlyFee = $user->usersExtended->monthly_fee;
            if (request('month') && request('month') !== 'all') {
                $remaining = $monthlyFee - $totalPaid;
            } else {
                // Calculate for all months in year
                $expectedTotal = $monthlyFee * $currentMonth;
                $totalPaidYear = \App\Models\PaymentProof::where('users_extended_id', $user->usersExtended->id)
                    ->where('status', 'approved')
                    ->whereYear('created_at', $filterYear)
                    ->sum('amount');
                $remaining = $expectedTotal - $totalPaidYear;
            }

            // Get list of months already paid
            $paidMonths = \App\Models\PaymentProof::where('users_extended_id', $user->usersExtended->id)
                ->where('status', 'approved')
                ->whereYear('created_at', $filterYear)
                ->pluck('month')
                ->toArray();

            // Get total income and expense for display
            $totalIncome = \App\Models\Transaction::where('type', 'income')
                ->whereYear('created_at', $filterYear)
                ->sum('amount');

            $totalExpense = \App\Models\Transaction::where('type', 'expense')
                ->whereYear('created_at', $filterYear)
                ->sum('amount');
        }

        // Get total wallet balance (available to all users)
        $walletBalance = \App\Models\Wallet::getCurrentBalance();

        // Available months for filter
        $availableMonths = [
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

        // Available years for filter (current year and 2 years back)
        $availableYears = range($currentYear - 2, $currentYear + 1);

        return view('user.dashboard', compact(
            'user',
            'payments',
            'totalPaid',
            'remaining',
            'paidMonths',
            'totalIncome',
            'totalExpense',
            'walletBalance',
            'filterMonth',
            'filterYear',
            'availableMonths',
            'availableYears',
            'currentMonth',
            'currentYear'
        ));
    })->name('dashboard');


    // Payment routes
    Route::get('/{userExtendedId}/payment/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/{userExtendedId}/payment', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/{userExtendedId}/payment/history', [PaymentController::class, 'userHistory'])->name('payments.history');

    // Profile
    Route::get('/profile', function () {
        $user = \App\Models\User::with('usersExtended')->where('id', auth()->id())->first();
        return view('user.profile', compact('user'));
    })->name('profile');

    // Kwitansi Digital (User)
    Route::get('/receipts', [PaymentReceiptController::class, 'userReceipts'])->name('receipts.index');
    Route::get('/receipts/{receipt}/download', [PaymentReceiptController::class, 'downloadPdf'])->name('receipts.download');
    Route::get('/receipts/{receipt}/preview', [PaymentReceiptController::class, 'previewPdf'])->name('receipts.preview');
});
