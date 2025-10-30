<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * PurchaseOrder model
 *
 * Represents a purchase order made to a supplier. A PO may originate
 * from a purchase request and has many PO items, goods receipts, and
 * invoices.
 */
class PurchaseOrder extends Model
{
    protected $fillable = [
        'po_number',
        'supplier_id',
        'po_date',
        'status',
        'invoice_image_path',
        'created_by',
        'purchase_request_id',
        'total_amount',
        'supplier_confirmed',
        'supplier_notes',
        'contact_person',
        'contact_phone',
        'estimated_delivery_date',
        'confirmation_date',
        'confirmed_delivery_date',
        'sent_at'
    ];

    protected $casts = [
        'po_date' => 'datetime',
        'estimated_delivery_date' => 'datetime',
        'confirmation_date' => 'datetime',
        'confirmed_delivery_date' => 'datetime',
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Supplier for this PO.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Items associated with this PO.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    /**
     * User who created the PO.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * The purchase request that originated this PO (optional).
     */
    public function purchaseRequest(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    /**
     * Goods receipts associated with this PO.
     */
    public function goodsReceipts(): HasMany
    {
        return $this->hasMany(GoodsReceipt::class);
    }

    /**
     * Invoices associated with this PO.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}