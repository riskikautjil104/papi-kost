<?php

namespace App\Http\Controllers;

use App\Models\PaymentReceipt;
use App\Models\PaymentProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentReceiptController extends Controller
{
    /**
     * Daftar semua kwitansi (Admin)
     */
    public function index(Request $request)
    {
        $query = PaymentReceipt::with('paymentProof.userExtended.user')
            ->orderBy('issued_at', 'desc');

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('receipt_number', 'like', "%{$search}%")
                    ->orWhere('tenant_name', 'like', "%{$search}%")
                    ->orWhere('room_number', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan bulan
        if ($request->has('month') && $request->month && $request->month !== 'all') {
            $query->where('month', $request->month);
        }

        // Filter berdasarkan tahun
        if ($request->has('year') && $request->year && $request->year !== 'all') {
            $query->where('year', $request->year);
        }

        $receipts = $query->paginate(15)->withQueryString();

        $stats = [
            'total_receipts' => PaymentReceipt::count(),
            'total_amount' => PaymentReceipt::sum('amount'),
            'this_month' => PaymentReceipt::where('month', now()->month)
                ->where('year', now()->year)
                ->count(),
            'this_month_amount' => PaymentReceipt::where('month', now()->month)
                ->where('year', now()->year)
                ->sum('amount'),
        ];

        return view('admin.receipts.index', compact('receipts', 'stats'));
    }

    /**
     * Detail kwitansi (Admin)
     */
    public function show(PaymentReceipt $receipt)
    {
        $receipt->load('paymentProof.userExtended.user');
        return view('admin.receipts.show', compact('receipt'));
    }

    /**
     * Download kwitansi sebagai PDF (Admin & User)
     */
    public function downloadPdf(PaymentReceipt $receipt)
    {
        $receipt->load('paymentProof.userExtended.user');

        // Jika user biasa, cek apakah kwitansi miliknya
        if (!Auth::user()->is_admin) {
            $userExtended = Auth::user()->usersExtended;
            if (!$userExtended || $receipt->paymentProof->users_extended_id !== $userExtended->id) {
                abort(403, 'Unauthorized');
            }
        }

        $terbilang = PaymentReceipt::terbilang((float) $receipt->amount) . ' Rupiah';

        $pdf = Pdf::loadView('admin.receipts.receipt-pdf', compact('receipt', 'terbilang'));
        $pdf->setPaper('A5', 'landscape');

        return $pdf->download("Kwitansi-{$receipt->receipt_number}.pdf");
    }

    /**
     * Preview kwitansi di browser (Admin & User)
     */
    public function previewPdf(PaymentReceipt $receipt)
    {
        $receipt->load('paymentProof.userExtended.user');

        // Jika user biasa, cek apakah kwitansi miliknya
        if (!Auth::user()->is_admin) {
            $userExtended = Auth::user()->usersExtended;
            if (!$userExtended || $receipt->paymentProof->users_extended_id !== $userExtended->id) {
                abort(403, 'Unauthorized');
            }
        }

        $terbilang = PaymentReceipt::terbilang((float) $receipt->amount) . ' Rupiah';

        $pdf = Pdf::loadView('admin.receipts.receipt-pdf', compact('receipt', 'terbilang'));
        $pdf->setPaper('A5', 'landscape');

        return $pdf->stream("Kwitansi-{$receipt->receipt_number}.pdf");
    }

    /**
     * Generate ulang kwitansi dari payment proof (Admin)
     */
    public function regenerate(PaymentProof $payment)
    {
        if (!$payment->isApproved()) {
            return redirect()->back()
                ->with('error', 'Hanya pembayaran yang sudah disetujui yang bisa dibuatkan kwitansi.');
        }

        // Cek apakah sudah ada kwitansi
        $existingReceipt = PaymentReceipt::where('payment_proof_id', $payment->id)->first();
        if ($existingReceipt) {
            // Hapus kwitansi lama dan buat ulang
            $existingReceipt->delete();
        }

        $receipt = PaymentReceipt::generateFromPayment($payment);

        return redirect()->route('admin.receipts.show', $receipt)
            ->with('success', 'Kwitansi berhasil di-generate ulang!');
    }

    /**
     * Kwitansi untuk user (daftar kwitansi milik user)
     */
    public function userReceipts()
    {
        $user = Auth::user();
        $userExtended = $user->usersExtended;

        if (!$userExtended) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Profil pengguna tidak ditemukan.');
        }

        // Ambil semua payment proof IDs milik user ini
        $paymentProofIds = PaymentProof::where('users_extended_id', $userExtended->id)
            ->pluck('id');

        $receipts = PaymentReceipt::whereIn('payment_proof_id', $paymentProofIds)
            ->orderBy('issued_at', 'desc')
            ->paginate(10);

        return view('user.receipts.index', compact('receipts', 'userExtended'));
    }
}
