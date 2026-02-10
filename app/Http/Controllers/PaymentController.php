<?php

namespace App\Http\Controllers;

use App\Models\PaymentProof;
use App\Models\UsersExtended;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentProof::with(['userExtended' => function ($q) {
            $q->with('user');
        }]);

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('month') && $request->month) {
            $query->where('month', $request->month);
        }

        if ($request->has('year') && $request->year) {
            $query->where('year', $request->year);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('userExtended.user', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'pending' => PaymentProof::where('status', 'pending')->count(),
            'approved' => PaymentProof::where('status', 'approved')->count(),
            'rejected' => PaymentProof::where('status', 'rejected')->count(),
            'total_pending' => PaymentProof::where('status', 'pending')->sum('amount'),
            'total_approved' => PaymentProof::where('status', 'approved')->sum('amount')
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    public function pending()
    {
        $pendingPayments = PaymentProof::with(['userExtended' => function ($q) {
            $q->with('user');
        }])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->paginate(15);

        return view('admin.payments.pending', compact('pendingPayments'));
    }

    public function approve(Request $request, PaymentProof $payment)
    {
        $request->validate([
            'notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request, $payment) {
            // Approve payment
            $payment->approve(Auth::id());

            // Add transaction record
            $userName = $payment->userExtended?->user?->name ?? 'Unknown';
            Transaction::create([
                'payment_proof_id' => $payment->id,
                'type' => 'income',
                'amount' => $payment->amount,
                'month' => $payment->month,
                'year' => $payment->year,
                'description' => "Pembayaran iuran {$payment->month_name} {$payment->year} - {$userName}"
            ]);

            // Update wallet balance
            Wallet::updateBalance($payment->amount, 'income');
        });

        return redirect()->back()
            ->with('success', 'Pembayaran berhasil disetujui!');
    }

    public function reject(Request $request, PaymentProof $payment)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10'
        ]);

        $payment->update([
            'status' => 'rejected',
            'description' => $payment->description . "\n\nAlasan Penolakan: " . $request->rejection_reason
        ]);

        return redirect()->back()
            ->with('success', 'Pembayaran ditolak.');
    }

    public function show(PaymentProof $payment)
    {
        $payment->load(['userExtended' => function ($query) {
            $query->with('user');
        }, 'approver']);

        return view('admin.payments.show', compact('payment'));
    }

    // User-facing payment submission
    public function create($userExtendedId)
    {
        $user = UsersExtended::with('user')->findOrFail($userExtendedId);

        // Check if current user owns this profile or is admin
        if (Auth::id() !== $user->user_id && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

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

        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Get existing payments for multi-payment tracking
        $existingPayments = PaymentProof::where('users_extended_id', $user->id)
            ->where('status', '!=', 'rejected')
            ->select('id', 'amount', 'month', 'year', 'status')
            ->get();

        return view('user.payments.create', compact('user', 'months', 'currentMonth', 'currentYear', 'existingPayments'));
    }

    public function store(Request $request, $userExtendedId)
    {
        $user = UsersExtended::with('user')->findOrFail($userExtendedId);

        if (Auth::id() !== $user->user_id && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
            'payment_method' => 'required|string',
            'bank_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string'
        ]);

        // Check if total payments would exceed monthly fee
        $totalPaid = $user->getTotalPaidForMonth($request->month, $request->year);
        $remaining = $user->monthly_fee - $totalPaid;

        if ($request->amount > $remaining) {
            return back()->withErrors(['amount' => "Jumlah pembayaran melebihi sisa yang harus dibayar (Rp " . number_format($remaining, 0, ',', '.') . ")"]);
        }

        $data = [
            'users_extended_id' => $user->id,
            'amount' => $request->amount,
            'month' => $request->month,
            'year' => $request->year,
            'payment_method' => $request->payment_method,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'description' => $request->description,
            'status' => 'pending'
        ];

        if ($request->hasFile('proof_image')) {
            $data['proof_image'] = $request->file('proof_image')->store('payment_proofs', 'public');
        }

        PaymentProof::create($data);

        return redirect()->route('user.dashboard')
            ->with('success', 'Bukti pembayaran berhasil dikirim! Menunggu persetujuan admin.');
    }

    public function userHistory($userExtendedId)
    {
        $user = UsersExtended::with('user')->findOrFail($userExtendedId);

        if (Auth::id() !== $user->user_id && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $payments = PaymentProof::where('users_extended_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.payments.history', compact('user', 'payments'));
    }
}
