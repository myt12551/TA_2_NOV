<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
     * Approve a purchase request (supervisor action).
     */
    public function approve($id)
    {
        $pr = PurchaseRequest::findOrFail($id);
        $pr->update(['status' => 'approved']);
        return redirect()->route('procurement.index')->with('success', 'Permintaan pembelian disetujui');
    }

    /**
     * Reject a purchase request (supervisor action).
     */
    public function reject($id)
    {
        $pr = PurchaseRequest::findOrFail($id);
        $pr->update(['status' => 'rejected']);
        return redirect()->route('procurement.index')->with('success', 'Permintaan pembelian ditolak');
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