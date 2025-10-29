<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    public function createCustomer()
    {
        return view('auth.register_customer');
    }

    public function storeCustomer(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required','string','max:255'],
            'username' => ['required','string','max:50', Rule::unique('users','username')],
            'email'    => ['required','string','email','max:255', Rule::unique('users','email')],
            'password' => ['required','string','confirmed','min:8'],
            'phone'    => ['nullable','string','max:30'],
            'address'  => ['nullable','string','max:255'],
        ]);

        User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'customer',
            'phone'    => $validated['phone'] ?? null,
            'address'  => $validated['address'] ?? null,
        ]);

        // ⬇️ Ubah: jangan Auth::login($user)
        return redirect()
            ->route('customer.login')
            ->with('success', 'Registrasi berhasil. Silakan login untuk melanjutkan.');
    }
}
