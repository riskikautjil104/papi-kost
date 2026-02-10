<?php

namespace App\Http\Controllers;

use App\Models\UsersExtended;
use App\Models\PaymentProof;
use App\Models\Expense;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page with dashboard statistics.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        // Get statistics from database (or defaults if empty)
        $totalUsers = UsersExtended::where('status', 'active')->count() ?? 0;
        $totalRooms = UsersExtended::where('status', 'active')->distinct('room_number')->count('room_number') ?? 0;

        // Get filtered income and expenses
        $totalCollected = Transaction::where('type', 'income')
            ->when($month, function ($query) use ($month) {
                return $query->whereMonth('created_at', $month);
            })
            ->when($year, function ($query) use ($year) {
                return $query->whereYear('created_at', $year);
            })
            ->sum('amount') ?? 0;

        $totalExpenses = Transaction::where('type', 'expense')
            ->when($month, function ($query) use ($month) {
                return $query->whereMonth('created_at', $month);
            })
            ->when($year, function ($query) use ($year) {
                return $query->whereYear('created_at', $year);
            })
            ->sum('amount') ?? 0;

        // Get wallet balance
        $walletBalance = Wallet::getCurrentBalance() ?? 0;

        // Get pending payments count
        $pendingPayments = PaymentProof::where('status', 'pending')->count() ?? 0;

        // Get room occupants
        $rooms = UsersExtended::with('user')
            ->where('status', 'active')
            ->whereNotNull('room_number')
            ->orderBy('room_number')
            ->get()
            ->groupBy('room_number')
            ->map(function ($occupants, $roomNumber) {
                return [
                    'room_number' => $roomNumber,
                    'occupants' => $occupants->map(function ($user) {
                        return [
                            'name' => $user->user->name,
                            'phone' => $user->phone,
                            'join_date' => $user->join_date->format('d/m/Y'),
                            'profile_photo_url' => $user->profile_photo_url
                        ];
                    })
                ];
            })
            ->values();

        // Features data - Sistem Manajemen Pembayaran & Pengeluaran Kost
        $features = [
            [
                'icon' => '💰',
                'title' => 'Tracking Pembayaran',
                'description' => 'Pantau semua pembayaran iuran kost secara real-time dengan status approval dan history lengkap.'
            ],
            [
                'icon' => '📊',
                'title' => 'Laporan Keuangan',
                'description' => 'Dashboard analitik untuk monitoring pemasukan bulanan, pengeluaran, dan saldo wallet secara detail.'
            ],
            [
                'icon' => '📸',
                'title' => 'Upload Bukti Bayar',
                'description' => 'Penghuni dapat upload bukti pembayaran dengan mudah melalui sistem yang aman dan terverifikasi.'
            ],
            [
                'icon' => '✅',
                'title' => 'Approval Sistem',
                'description' => 'Admin dapat menyetujui atau menolak pembayaran dengan sistem approval yang transparan.'
            ],
            [
                'icon' => '📈',
                'title' => 'Statistik Bulanan',
                'description' => 'Laporan statistik lengkap per bulan termasuk tunggakan, pembayaran tepat waktu, dan analisis keuangan.'
            ],
            [
                'icon' => '🔔',
                'title' => 'Reminder Otomatis',
                'description' => 'Sistem reminder otomatis untuk pembayaran jatuh tempo dan notifikasi status pembayaran.'
            ]
        ];

        // Payment methods data - Kontrakan System
        $paymentMethods = [
            ['icon' => '🏦', 'name' => 'Transfer Bank'],
            ['icon' => '💳', 'name' => 'Kartu Debit/Kredit'],
            ['icon' => '📱', 'name' => 'E-Wallet'],
            ['icon' => '🔗', 'name' => 'QRIS'],
            ['icon' => '💰', 'name' => 'Tunai'],
            ['icon' => '📸', 'name' => 'Upload Bukti']
        ];

        // Available years for filter
        $availableYears = range(now()->year - 2, now()->year + 1);

        return view('home', compact(
            'totalUsers',
            'totalRooms',
            'totalCollected',
            'totalExpenses',
            'walletBalance',
            'pendingPayments',
            'rooms',
            'features',
            'paymentMethods',
            'year',
            'month',
            'availableYears'
        ));
    }
}
