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
        'emergency_contact_name',
        'notes',
        'profile_photo',
        'email_notification',
        'payment_due_date',
        'last_notification_sent'
    ];

    protected $casts = [
        'monthly_fee' => 'decimal:2',
        'join_date' => 'date',
        'contract_end_date' => 'date',
        'email_notification' => 'boolean',
        'payment_due_date' => 'integer',
        'last_notification_sent' => 'datetime'
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

    public function getProfilePhotoUrlAttribute(): ?string
    {
        return $this->profile_photo ? asset('storage/' . $this->profile_photo) : null;
    }

    /**
     * Get total paid amount for a specific month and year
     */
    public function getTotalPaidForMonth(int $month, int $year): float
    {
        return $this->paymentProofs()
            ->where('month', $month)
            ->where('year', $year)
            ->where('status', 'approved')
            ->sum('amount');
    }

    /**
     * Get remaining balance for a specific month and year
     */
    public function getRemainingForMonth(int $month, int $year): float
    {
        $totalPaid = $this->getTotalPaidForMonth($month, $year);
        return max(0, $this->monthly_fee - $totalPaid);
    }

    /**
     * Check if monthly fee is fully paid for a specific month and year
     */
    public function isFullyPaidForMonth(int $month, int $year): bool
    {
        return $this->getTotalPaidForMonth($month, $year) >= $this->monthly_fee;
    }

    /**
     * Get all payments for a specific month and year
     */
    public function getPaymentsForMonth(int $month, int $year)
    {
        return $this->paymentProofs()
            ->where('month', $month)
            ->where('year', $year)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get payment status for a specific month and year
     */
    public function getPaymentStatusForMonth(int $month, int $year): string
    {
        $totalPaid = $this->getTotalPaidForMonth($month, $year);
        
        if ($totalPaid >= $this->monthly_fee) {
            return 'paid';
        } elseif ($totalPaid > 0) {
            return 'partial';
        } else {
            return 'unpaid';
        }
    }
}
