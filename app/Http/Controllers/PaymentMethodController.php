<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentMethodController extends Controller
{
  public function index(): View
  {
    return view('payment-method.index', [
      'payment_methods' => PaymentMethod::orderBy('name')->get(),
      'type' => 'show'
    ]);
  }

  public function create(): View
  {
    return view('payment-method.form', [
      'type' => 'create'
    ]);
  }

  public function store(Request $request): RedirectResponse
  {
    $request->validate([
      'name' => 'required|string|max:255|unique:payment_methods,name',
    ]);

    $payment_method = PaymentMethod::create($request->all());

    return redirect()->route('payment-method.index')->with('status', 'Metode pembayaran berhasil ditambahkan');
  }

  public function edit(PaymentMethod $payment_method): View
  {
    return view('payment-method.form', [
      'payment_method' => $payment_method,
      'type' => 'edit'
    ]);
  }

  public function update(Request $request, PaymentMethod $payment_method): RedirectResponse
  {
    $request->validate([
      'name' => 'required|string|max:255|unique:payment_methods,name,' . $payment_method->id
    ]);

    $payment_method->update($request->all());

    return redirect()->route('payment-method.index')->with('status', 'Metode pembayaran berhasil diubah');
  }

  public function destroy(PaymentMethod $payment_method): RedirectResponse
  {
    $payment_method->delete();

    return redirect()->route('payment-method.index')->with('status', 'Metode pembayaran berhasil dihapus');
  }
}
