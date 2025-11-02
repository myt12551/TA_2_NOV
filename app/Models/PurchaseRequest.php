<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

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
        'approved_by',
        'request_date',
        'supplier_id',
        'approved_at',
        'status',
        'description',
        'approval_notes',
        'approval_status',
        'rejection_reason',
        'validation_document_path',
        'is_validated',
    ];

        protected $casts = [
            'request_date' => 'datetime',
            'approved_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime'
        ];

    /**
     * The user who submitted the purchase request.
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * The supervisor who approved/rejected the request
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * The supplier associated with this purchase request
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
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

    /**
     * Approve the purchase request
     */
    public function approve($supervisorId)
    {
        $this->update([
            'approval_status' => 'approved',
            'approved_by' => $supervisorId,
            'approved_at' => Carbon::now(),
            'status' => 'approved'
        ]);
    }

    /**
     * Reject the purchase request
     */
    public function reject($supervisorId, $reason)
    {
        $this->update([
            'approval_status' => 'rejected',
            'approved_by' => $supervisorId,
            'approved_at' => Carbon::now(),
            'rejection_reason' => $reason,
            'status' => 'rejected'
        ]);
    }

    /**
     * Check if PR can be converted to PO
     */
    public function canConvertToPO(): bool
    {
        return $this->approval_status === 'approved' && 
               $this->status === 'approved' &&
               !$this->purchaseOrders()->exists();
    }

    /**
     * Convert approved PR to PO
     */
    public function convertToPO($userId)
    {
        if (!$this->canConvertToPO()) {
            throw new \Exception('This PR cannot be converted to PO');
        }

        $po = PurchaseOrder::create([
            'po_number' => 'PO-' . date('Ymd') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT),
            'purchase_request_id' => $this->id,
            'po_date' => Carbon::now(),
            'status' => 'draft',
            'created_by' => $userId
        ]);

        // Copy items from PR to PO
        foreach ($this->items as $item) {
            $po->items()->create([
                'item_id' => $item->item_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->estimated_unit_price,
                'notes' => $item->notes
            ]);
        }

        return $po;
    }
}