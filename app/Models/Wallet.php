<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'balance',
        'balance_date'
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'balance_date' => 'date'
    ];

    public static function getCurrentBalance(): float
    {
        $wallet = self::latest()->first();
        return $wallet ? $wallet->balance : 0;
    }

    public static function updateBalance(float $amount, string $type): void
    {
        $wallet = self::latest()->first() ?? new self();

        if ($type === 'income') {
            $wallet->balance += $amount;
        } elseif ($type === 'expense') {
            $wallet->balance -= $amount;
        }

        $wallet->balance_date = now();
        $wallet->save();
    }
}
