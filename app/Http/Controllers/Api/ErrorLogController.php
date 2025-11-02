<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ErrorLogController extends Controller
{
    /**
     * Store error log from frontend
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'error' => 'required|string',
            'context' => 'required|array'
        ]);

        Log::error('Frontend Error: ' . $validated['error'], $validated['context']);

        return response()->json([
            'success' => true,
            'message' => 'Error logged successfully'
        ]);
    }
}