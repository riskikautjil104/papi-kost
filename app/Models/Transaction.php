<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_proof_id',
        'type',
        'amount',
        'month',
        'year',
        'description'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function paymentProof(): BelongsTo
    {
        return $this->belongsTo(PaymentProof::class);
    }

    public function isIncome(): bool
    {
        return $this->type === 'income';
    }

    public function isExpense(): bool
    {
        return $this->type === 'expense';
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->isIncome() ? 'Pemasukan' : 'Pengeluaran';
    }

    public function getTypeBadgeAttribute(): string
    {
        return $this->isIncome() ? 'success' : 'danger';
    }
}
