<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceipt;
use App\Models\InventoryRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controller for handling inspection and recording of goods into inventory.
 *
 * After goods are received (GR), warehouse or inventory staff can record
 * the receipt into the inventory system. This process is represented by
 * an InventoryRecord.
 */
class InventoryRecordController extends Controller
{
    /**
     * Display a listing of the inventory records.
     */
    public function index()
    {
        $records = InventoryRecord::with('goodsReceipt.purchaseOrder', 'recorder')->latest()->get();
        return view('inventory-record.index', compact('records'));
    }

    /**
     * Show the form for creating a new inventory record for a goods receipt.
     */
    public function create($goodsReceiptId)
    {
        $gr = GoodsReceipt::with('purchaseOrder')->findOrFail($goodsReceiptId);
        return view('inventory-record.form', compact('gr'));
    }

    /**
     * Store a newly created inventory record in storage.
     */
    public function store(Request $request, $goodsReceiptId)
    {
        $request->validate([
            'record_date' => 'required|date',
            'notes'       => 'nullable|string',
        ]);

        $gr = GoodsReceipt::findOrFail($goodsReceiptId);

        DB::beginTransaction();
        try {
            $record = InventoryRecord::create([
                'goods_receipt_id' => $gr->id,
                'record_date'      => $request->record_date,
                'recorded_by'      => auth()->id(),
                'notes'            => $request->notes,
            ]);

            // Optionally update goods receipt status to indicate inspection completed
            $gr->update(['status' => 'inspected']);

            DB::commit();
            return redirect()->route('procurement.index')->with('success', 'Pemeriksaan barang berhasil dicatat');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mencatat pemeriksaan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified inventory record.
     */
    public function show($id)
    {
        $record = InventoryRecord::with('goodsReceipt.purchaseOrder', 'recorder')->findOrFail($id);
        return view('inventory-record.show', compact('record'));
    }
}