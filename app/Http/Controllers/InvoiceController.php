<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Controller for handling supplier invoices.
 *
 * Users can upload and review invoices for a purchase order. Once
 * verified, invoices can be marked as paid. Uploads are stored in
 * the public disk.
 */
class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices.
     */
    public function index()
    {
        $invoices = Invoice::with('purchaseOrder')->latest()->get();
        return view('invoice.index', compact('invoices'));
    }

    /**
     * Show the form for uploading an invoice for a purchase order.
     */
    public function create($purchaseOrderId)
    {
        $po = PurchaseOrder::findOrFail($purchaseOrderId);
        return view('invoice.form', compact('po'));
    }

    /**
     * Store a new invoice.
     */
    public function store(Request $request, $purchaseOrderId)
    {
        $request->validate([
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'invoice_date'   => 'required|date',
            'due_date'       => 'nullable|date',
            'amount'         => 'required|numeric|min:0',
            'invoice_file'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes'          => 'nullable|string',
        ]);

        $po = PurchaseOrder::findOrFail($purchaseOrderId);
        DB::beginTransaction();
        try {
            $filePath = null;
            if ($request->hasFile('invoice_file')) {
                $filePath = $request->file('invoice_file')->store('invoices', 'public');
            }

            $invoice = Invoice::create([
                'purchase_order_id' => $po->id,
                'invoice_number'    => $request->invoice_number,
                'invoice_date'      => $request->invoice_date,
                'due_date'          => $request->due_date,
                'amount'            => $request->amount,
                'status'            => 'unpaid',
                'invoice_file'      => $filePath,
                'notes'             => $request->notes,
            ]);

            // Optionally update PO status to indicate invoice received
            $po->update(['status' => 'invoice_received']);

            DB::commit();
            // Redirect back to procurement dashboard after adding an invoice
            return redirect()->route('procurement.index')->with('success', 'Invoice berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan invoice: ' . $e->getMessage()]);
        }
    }

    /**
     * Mark an invoice as paid.
     */
    public function markPaid($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update(['status' => 'paid']);
        // Optionally update PO status to closed/paid
        $invoice->purchaseOrder->update(['status' => 'paid']);
        return redirect()->route('procurement.index')->with('success', 'Invoice ditandai sebagai dibayar');
    }
}