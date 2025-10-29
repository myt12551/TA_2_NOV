<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Invoice model
 *
 * Represents a supplier invoice tied to a purchase order. Invoices
 * track amounts due and their payment status.
 */
class Invoice extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'amount',
        'status',
        'invoice_file',
        'notes',
    ];

    /**
     * Associated purchase order.
     */
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}