<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;

/**
 * Controller for the procurement dashboard.
 *
 * This controller aggregates data across the procurement workflow
 * (purchase requests, purchase orders, goods receipts, invoices) and
 * passes it to a single view. The dashboard presents each PR and its
 * related PO, GR, and invoice, along with actions to advance through
 * the workflow.
 */
class ProcurementController extends Controller
{
    /**
     * Show the procurement dashboard.
     *
     * Eager load relationships so the view can access related models
     * efficiently. The dashboard lists purchase requests in reverse
     * chronological order with their associated purchase order(s),
     * goods receipts, and invoices.
     */
    public function index()
    {
        // Load purchase requests with their related orders, receipts, invoices, supplier, items, and requester
        $purchaseRequests = PurchaseRequest::with([
            // Load related models for efficient dashboard queries
            'purchaseOrders.goodsReceipts.inventoryRecords',
            'purchaseOrders.invoices',
            'purchaseOrders.supplier',
            'items',
            'requester',
        ])->latest()->get();

        return view('procurement.index', compact('purchaseRequests'));
    }
}