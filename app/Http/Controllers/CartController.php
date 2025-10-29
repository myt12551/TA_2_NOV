<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = User::find(Auth::user()->id);
        return view('transaction.cart', [
            'carts' => $user->carts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): string
    {
        $item = Item::find($request->item_id);
        $user = User::find(Auth::user()->id);
        $cart = $user->carts()->where('item_id', $request->item_id)->first();
        if ($cart) {
            $qty = $cart->qty + $request->qty;
            $cart->qty = $qty;
            $cart->subtotal = $qty * calculate_price($item, $qty);
        } else {
            $cart = new Cart();
            $cart->user_id = $user->id;
            $cart->item_id = $request->item_id;
            $cart->qty = $request->qty;
            $cart->subtotal = $request->qty * calculate_price($item, $request->qty);
        }
        $cart->save();

        return json_encode(['status' => 'success']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart): string|bool
    {
        if ($request->qty < 1) return false;
        $cart->qty = $request->qty;
        $cart->subtotal = $request->qty * calculate_price($cart->item, $request->qty);
        $cart->save();

        return json_encode(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart): string
    {
        $cart->delete();
        return json_encode(['status' => 'success']);
    }

    public function clear(): string
    {
        $user = User::find(Auth::user()->id);
        $carts = $user->carts;
        foreach ($carts as $cart) {
            $cart->delete();
        }

        return json_encode(['status' => 'success']);
    }

    public function count_stock(Item $item): string
    {
        $current_stock = $item->stock;
        $carts = Cart::all();
        foreach ($carts as $cart) {
            if ($cart->item_id == $item->id) {
                $current_stock -= $cart->qty;
            }
        }
        return json_encode(['stock' => $current_stock]);
    }
}
