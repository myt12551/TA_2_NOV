<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * GoodsReceipt model
 *
 * Represents the receipt of goods/services against a purchase order.
 */
class GoodsReceipt extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'gr_number',
        'receipt_date',
        'received_by',
        'status',
        'notes',
        'delivery_order_file',
        'bbm_file',
    ];

    /**
     * Associated purchase order.
     */
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    /**
     * User who received the goods.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Items received.
     */
    public function items(): HasMany
    {
        return $this->hasMany(GoodsReceiptItem::class);
    }

    /**
     * Inventory records created from this goods receipt.
     */
    public function inventoryRecords(): HasMany
    {
        return $this->hasMany(InventoryRecord::class);
    }
}