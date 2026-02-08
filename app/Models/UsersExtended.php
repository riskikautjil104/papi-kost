<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsersExtended extends Model
{
    use HasFactory;

    protected $table = 'users_extended';

    protected $fillable = [
        'user_id',
        'phone',
        'room_number',
        'monthly_fee',
        'join_date',
        'contract_end_date',
        'status',
        'address',
        'emergency_contact',
        'notes'
    ];

    protected $casts = [
        'monthly_fee' => 'decimal:2',
        'join_date' => 'date',
        'contract_end_date' => 'date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentProofs()
    {
        return $this->hasMany(PaymentProof::class, 'users_extended_id');
    }

    public function getPendingPaymentsAttribute()
    {
        return $this->paymentProofs()->where('status', 'pending')->get();
    }

    public function getApprovedPaymentsAttribute()
    {
        return $this->paymentProofs()->where('status', 'approved')->get();
    }

    public function getTotalPaidThisMonthAttribute()
    {
        return $this->paymentProofs()
            ->where('status', 'approved')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'active' => 'success',
            'inactive' => 'secondary',
            'pending' => 'warning',
            'blocked' => 'danger'
        ];

        return $badges[$this->status] ?? 'secondary';
    }
}
