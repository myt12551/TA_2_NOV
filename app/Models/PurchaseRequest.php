<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * PurchaseRequest model
 *
 * Represents a single purchase request submitted by a user. A
 * request contains multiple items and tracks its lifecycle
 * through various statuses (pending, approved, rejected, cancelled).
 */
class PurchaseRequest extends Model
{
    protected $fillable = [
        'pr_number',
        'requested_by',
        'request_date',
        'status',
        'description',
    ];

    /**
     * The user who submitted the purchase request.
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Line items for this purchase request.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseRequestItem::class);
    }

    /**
     * Corresponding purchase orders (if any) generated from this PR.
     */
    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}