<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'total_items' => Item::all()->count(),
            'total_transactions' => Transaction::all()->count(),
            'total_income' => Transaction::sum('total'),
            'income_today' => Transaction::whereDate('updated_at', now())->sum('total'),
            'income_this_month' => Transaction::whereMonth('updated_at', now())->sum('total'),
        ]);
    }
}
