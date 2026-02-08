<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentProof extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_extended_id',
        'amount',
        'month',
        'year',
        'payment_method',
        'bank_name',
        'account_number',
        'proof_image',
        'description',
        'status',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime'
    ];

    public function userExtended(): BelongsTo
    {
        return $this->belongsTo(UsersExtended::class, 'users_extended_id');
    }

    public function user(): ?User
    {
        return $this->userExtended?->user;
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function transaction(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function approve(int $adminId): bool
    {
        $this->status = 'approved';
        $this->approved_by = $adminId;
        $this->approved_at = now();
        return $this->save();
    }

    public function reject(): bool
    {
        $this->status = 'rejected';
        return $this->save();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function getMonthNameAttribute(): string
    {
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

        return $months[$this->month] ?? '';
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger'
        ];

        return $badges[$this->status] ?? 'secondary';
    }
}
