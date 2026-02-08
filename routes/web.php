<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Redirect dashboard based on user role
// Route::get('/dashboard', function () {
//     if (auth()->check()) {
//         if (auth()->user()->is_admin) {
//             return redirect()->route('admin.dashboard');
//         }
//         return redirect()->route('user.dashboard');
//     }
//     return redirect()->route('login');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Auth routes (login, register, etc.)
require __DIR__ . '/auth.php';

// Admin routes
require __DIR__ . '/admin.php';

// User routes
require __DIR__ . '/user.php';

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
