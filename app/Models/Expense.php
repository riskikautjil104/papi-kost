<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'amount',
        'expense_date',
        'description',
        'receipt_image',
        'created_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function getCategories(): array
    {
        return [
            'listrik' => 'Listrik',
            'air' => 'Air',
            'internet' => 'Internet',
            'kebersihan' => 'Kebersihan',
            'perbaikan' => 'Perbaikan',
            'perlengkapan' => 'Perlengkapan',
            'lainnya' => 'Lainnya'
        ];
    }

    public function getCategoryLabelAttribute(): string
    {
        $categories = self::getCategories();
        return $categories[$this->category] ?? ucfirst($this->category);
    }
}
