<?php

namespace App\Http\Controllers;

use App\Models\UsersExtended;
use App\Models\PaymentProof;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = UsersExtended::with('user');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            })->orWhere('phone', 'like', "%$search%")
                ->orWhere('room_number', 'like', "%$search%");
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20|unique:users_extended',
            'room_number' => 'nullable|string|max:10',
            'monthly_fee' => 'required|numeric|min:0',
            'join_date' => 'required|date',
            'contract_end_date' => 'required|date|after:join_date',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:20',
            'notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            // Create user account
            $user = \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            // Create extended profile
            UsersExtended::create([
                'user_id' => $user->id,
                'phone' => $request->phone,
                'room_number' => $request->room_number,
                'monthly_fee' => $request->monthly_fee,
                'join_date' => $request->join_date,
                'contract_end_date' => $request->contract_end_date,
                'status' => 'active',
                'address' => $request->address,
                'emergency_contact' => $request->emergency_contact,
                'notes' => $request->notes
            ]);
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    public function show(UsersExtended $user)
    {
        $user->load('user', 'paymentProofs');

        // Payment history
        $paymentHistory = $user->paymentProofs()
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Calculate totals
        $totalPaid = $user->paymentProofs()
            ->where('status', 'approved')
            ->sum('amount');

        return view('admin.users.show', compact('user', 'paymentHistory', 'totalPaid'));
    }

    public function edit(UsersExtended $user)
    {
        $user->load('user');
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, UsersExtended $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->user_id)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users_extended')->ignore($user->id)],
            'room_number' => 'nullable|string|max:10',
            'monthly_fee' => 'required|numeric|min:0',
            'join_date' => 'required|date',
            'contract_end_date' => 'required|date|after:join_date',
            'status' => 'required|in:active,inactive,pending,blocked',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:20',
            'notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request, $user, $validated) {
            // Update user
            $user->user->update([
                'name' => $request->name,
                'email' => $request->email
            ]);

            // Update extended profile
            $user->update($validated);
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate!');
    }

    public function destroy(UsersExtended $user)
    {
        DB::transaction(function () use ($user) {
            // Delete extended profile (cascade to payments)
            $user->delete();
            // Delete user account
            $user->user->delete();
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    public function getPaymentHistory(UsersExtended $user, Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $payments = $user->paymentProofs()
            ->where('month', $month)
            ->where('year', $year)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'user' => $user->user->name,
            'month' => $month,
            'year' => $year,
            'monthly_fee' => $user->monthly_fee,
            'payments' => $payments
        ]);
    }
}
