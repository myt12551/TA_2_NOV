<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CustomerAuthController extends Controller
{
    // --- LOGIN ---
    public function showLoginForm()
    {
        // gunakan view login yang sudah ada (akan kita update tombolnya di langkah 5)
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            // sesuaikan dengan field form login-mu (email/username)
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember', false);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Arahkan berdasarkan role
            $user = Auth::user();
            if ($user->hasRole(['customer'])) {
                return redirect()->route('customer.marketplace');
            }

            // Sesuaikan dengan dashboard role lain yang sudah ada
            if ($user->hasRole(['admin', 'supervisor', 'kasir'])) {
                return redirect()->route('dashboard'); // ganti sesuai rute dashboard internalmu
            }

            // default fallback
            return redirect()->route('customer.marketplace');
        }

        return back()->withErrors([
            'email' => 'Kredensial tidak cocok.',
        ])->onlyInput('email');
    }

    // --- REGISTER CUSTOMER ---
    public function showRegisterForm()
    {
        return view('auth.register_customer');
    }

    public function register(Request $request)
    {
        // Sesuaikan kebutuhan data customer: name, email, password, phone, address, dll.
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'string', 'email', 'max:150', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'phone'    => ['nullable', 'string', 'max:30'],
            'address'  => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role'     => 'customer',
            'phone'    => $validated['phone'] ?? null,
            'address'  => $validated['address'] ?? null,
        ]);

        // Auto login setelah registrasi
        Auth::login($user);

        return redirect()->route('customer.marketplace')->with('success', 'Registrasi berhasil. Selamat datang di Marketplace!');
    }

    // --- LOGOUT ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // --- DASHBOARD MARKETPLACE ---
    public function marketplace()
    {
        // Halaman kosong dulu (siap diisi catalog/keranjang dsb)
        return view('customer.marketplace');
    }
}
