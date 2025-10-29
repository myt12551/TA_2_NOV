<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * InventoryRecord model
 *
 * Represents the process of inspection and recording goods into inventory after
 * a goods receipt (GR). Each record links to a goods receipt and the user who
 * recorded it, along with date and notes.
 */
class InventoryRecord extends Model
{
    protected $fillable = [
        'goods_receipt_id',
        'record_date',
        'recorded_by',
        'notes',
    ];

    /**
     * Associated goods receipt.
     */
    public function goodsReceipt(): BelongsTo
    {
        return $this->belongsTo(GoodsReceipt::class);
    }

    /**
     * User who recorded the inventory entry.
     */
    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}