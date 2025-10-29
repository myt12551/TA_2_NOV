<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('customer.index', [
            'customers' => Customer::with('transactions')->get(),
            'type' => 'show'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('customer.form', [
            'type' => 'create'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|max:255|string',
            'phone' => 'required|numeric|unique:customers,phone',
            'address' => 'required|string'
        ]);

        Customer::create($request->all());

        return redirect()->route('customer.index')->with('status', 'Pelanggan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer): View
    {
        return view('customer.show', [
            'customer' => $customer
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer): View
    {
        return view('customer.form', [
            'customer' => $customer,
            'type' => 'edit'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $request->validate([
            'name' => 'required|max:255|string',
            'phone' => 'required|numeric|unique:customers,phone,' . $customer->id,
            'address' => 'required|string'
        ]);

        $customer->update($request->all());

        return redirect()->route('customer.index')->with('status', 'Pelanggan berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('customer.index')->with('status', 'Pelanggan berhasil dihapus');
    }
}
