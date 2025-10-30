<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Controller for managing Purchase Requests (PR).
 *
 * Team members use this controller to submit new requests for goods or
 * services. Supervisors can review and approve/reject those requests.
 */
class PurchaseRequestController extends Controller
{
    /**
     * Display a list of purchase requests.
     */
    public function index()
    {
        $requests = PurchaseRequest::with('requester')->latest()->get();
        return view('purchase-request.index', compact('requests'));
    }

    /**
     * Show the form for creating a new purchase request.
     */
    public function create()
    {
        return view('purchase-request.form');
    }

    /**
     * Store a new purchase request and its items.
     */
    public function store(Request $request)
    {
        $request->validate([
            'request_date'          => 'required|date',
            'items'                 => 'required|array|min:1',
            'items.*.product_name'  => 'required|string',
            'items.*.quantity'      => 'required|integer|min:1',
            'description'           => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $pr = PurchaseRequest::create([
                'pr_number'    => $this->generatePRNumber(),
                'requested_by' => Auth::id(),
                'request_date' => $request->request_date,
                'status'       => 'pending',
                'description'  => $request->description,
            ]);

            foreach ($request->items as $item) {
                PurchaseRequestItem::create([
                    'purchase_request_id' => $pr->id,
                    'product_name'        => $item['product_name'],
                    'quantity'            => $item['quantity'],
                    'unit'                => $item['unit'] ?? 'pcs',
                    'notes'               => $item['notes'] ?? null,
                ]);
            }

            DB::commit();
            // Redirect to procurement dashboard after creating PR
            return redirect()->route('procurement.index')->with('success', 'Permintaan pembelian berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan permintaan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified purchase request.
     */
    public function show($id)
    {
        $pr = PurchaseRequest::with('items', 'requester')->findOrFail($id);
        return view('purchase-request.show', compact('pr'));
    }

    /**
     * Validate and approve a purchase request (supervisor action).
     */
    public function approve(Request $request, $id)
    {
        // Check if user is supervisor
        if (Auth::user()->role !== 'supervisor') {
            return back()->withErrors(['error' => 'Hanya supervisor yang dapat menyetujui PR']);
        }

        $request->validate([
            'validation_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'approval_notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();
            
            $pr = PurchaseRequest::findOrFail($id);
            
            // Store validation document
            $path = $request->file('validation_document')->store('pr-validation-docs', 'public');
            
            $pr->update([
                'status' => 'approved',
                'approval_status' => 'approved',
                'validation_document_path' => $path,
                'is_validated' => true,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'approval_notes' => $request->approval_notes
            ]);

            DB::commit();
            return redirect()->route('new-purchase-orders.index')
                ->with('success', 'Permintaan pembelian berhasil divalidasi dan disetujui');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal menyimpan validasi: ' . $e->getMessage()]);
        }
    }

    /**
     * Reject a purchase request with reason (supervisor action).
     */
    public function reject(Request $request, $id)
    {
        // Check if user is supervisor
        if (Auth::user()->role !== 'supervisor') {
            return back()->withErrors(['error' => 'Hanya supervisor yang dapat menolak PR']);
        }

        $request->validate([
            'rejection_reason' => 'required|string|min:10'
        ]);

        try {
            DB::beginTransaction();
            
            $pr = PurchaseRequest::findOrFail($id);
            
            $pr->update([
                'status' => 'rejected',
                'approval_status' => 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'rejection_reason' => $request->rejection_reason
            ]);

            DB::commit();
            return redirect()->route('new-purchase-orders.index')
                ->with('success', 'Permintaan pembelian ditolak');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal menyimpan penolakan: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper: generate sequential PR numbers (format: PR-0001/MM/YYYY).
     */
    private function generatePRNumber(): string
    {
        $count = PurchaseRequest::count() + 1;
        $month = date('m');
        $year  = date('Y');
        return sprintf("PR-%04d/%s/%s", $count, $month, $year);
    }
}