<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    use HasFactory;

    public const TYPE_CREDIT = 'credit';
    public const TYPE_DEBIT  = 'debit';

    protected $fillable = [
        'wallet_id',
        'order_id',
        'type',
        'amount',
        'description',
        'meta',
    ];

    protected $casts = [
        'amount'         => 'decimal:2',
        'meta'           => 'array',
    ];

    /* ===================== Relations ===================== */

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /* ===================== Scopes ===================== */

    public function scopeCredit($query)
    {
        return $query->where('type', self::TYPE_CREDIT);
    }

    public function scopeDebit($query)
    {
        return $query->where('type', self::TYPE_DEBIT);
    }

    /* ===================== Helpers ===================== */

    public function isCredit(): bool
    {
        return $this->type === self::TYPE_CREDIT;
    }

    public function isDebit(): bool
    {
        return $this->type === self::TYPE_DEBIT;
    }
}
