<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PaymentReceiptController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('/{user}/payment-history', [UserController::class, 'getPaymentHistory'])->name('payment-history');
    });

    // Payments Management
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/pending', [PaymentController::class, 'pending'])->name('pending');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
        Route::post('/{payment}/approve', [PaymentController::class, 'approve'])->name('approve');
        Route::post('/{payment}/reject', [PaymentController::class, 'reject'])->name('reject');
    });

    // Finance Management
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [FinanceController::class, 'index'])->name('index');
        Route::get('/expense/create', [FinanceController::class, 'createExpense'])->name('expense.create');
        Route::post('/expense', [FinanceController::class, 'storeExpense'])->name('expense.store');
        Route::delete('/expense/{expense}', [FinanceController::class, 'destroyExpense'])->name('expense.destroy');
        Route::get('/report', [FinanceController::class, 'report'])->name('report');
        Route::get('/report/pdf', [FinanceController::class, 'exportPdf'])->name('report.pdf');
        Route::get('/report/excel', [FinanceController::class, 'exportExcel'])->name('report.excel');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        // Annual Report
        Route::get('/annual', [ReportController::class, 'annual'])->name('annual');
        Route::get('/annual/pdf', [ReportController::class, 'exportAnnualPdf'])->name('annual.pdf');
        Route::get('/annual/excel', [ReportController::class, 'exportAnnualExcel'])->name('annual.excel');

        // User Report
        Route::get('/user', [ReportController::class, 'userHistory'])->name('user');
        Route::get('/user/{user}', [ReportController::class, 'userHistory'])->name('user.history');
        Route::get('/user/{user}/pdf', [ReportController::class, 'exportUserPdf'])->name('user.pdf');
        Route::get('/user/{user}/excel', [ReportController::class, 'exportUserExcel'])->name('user.excel');
    });

    // Settings
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');

    // Kwitansi Digital
    Route::prefix('receipts')->name('receipts.')->group(function () {
        Route::get('/', [PaymentReceiptController::class, 'index'])->name('index');
        Route::get('/{receipt}', [PaymentReceiptController::class, 'show'])->name('show');
        Route::get('/{receipt}/download', [PaymentReceiptController::class, 'downloadPdf'])->name('download');
        Route::get('/{receipt}/preview', [PaymentReceiptController::class, 'previewPdf'])->name('preview');
        Route::post('/regenerate/{payment}', [PaymentReceiptController::class, 'regenerate'])->name('regenerate');
    });
});
