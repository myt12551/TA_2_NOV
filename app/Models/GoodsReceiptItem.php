<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * GoodsReceiptItem model
 *
 * Represents a single item and quantity within a goods receipt.
 */
class GoodsReceiptItem extends Model
{
    protected $fillable = [
        'goods_receipt_id',
        'product_name',
        'quantity_received',
    ];

    /**
     * Parent goods receipt.
     */
    public function goodsReceipt(): BelongsTo
    {
        return $this->belongsTo(GoodsReceipt::class);
    }
}