<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('user.index', [
            'users' => User::where('id', '!=', Auth::user()->id)->orderBy('name')->get(),
            'type' => 'show'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('user.form', [
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
            'username' => 'required|alpha_dash|string|max:255|unique:users,username',
            'role' => 'required|string|in:supervisor,admin,cashier',
            'phone' => 'required|numeric|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'position' => 'nullable|string|max:255',
            'picture' => 'nullable|image|mimes:jpg,jpeg,png|max:5120'
        ]);

        $user = new User();

        $user->name = Str::lcfirst($request->name);
        $user->username = Str::lower($request->username);
        $user->role = $request->role;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->position = $request->position;

        if ($request->hasFile('picture')) {
            $filename = time() . '.' . $request->picture->extension();
            $request->picture->move(public_path('assets/images/users'), $filename);
            $user->picture = $filename;
        }

        $user->save();

        return redirect()->route('user.index')->with('status', 'User berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        if (auth()->user()->role != 'owner') {
            if ($user->role == 'supervisor' && $user->role == 'owner') {
                return redirect()->route('user.index')->with('error', 'Anda tidak memiliki akses');
            }
        }

        return view('user.form', [
            'user' => $user,
            'type' => 'edit'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => 'required|max:255|string',
            'username' => 'required|alpha_dash|string|max:255|unique:users,username,' . $user->id,
            'role' => 'required|string|in:supervisor,admin,cashier',
            'phone' => 'required|numeric|unique:users,phone,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'position' => 'nullable|string|max:255',
            'picture' => 'nullable|image|mimes:jpg,jpeg,png|max:5120'
        ]);

        $user->name = Str::lcfirst($request->name);
        $user->username = Str::lower($request->username);
        $user->role = $request->role;
        $user->phone = $request->phone;
        $user->email = $request->email;

        if ($request->password != null) {
            $user->password = Hash::make($request->password);
        }

        $user->position = $request->position;

        if ($request->hasFile('picture')) {
            if ($user->picture != 'profile.jpg') {
                File::delete('assets/images/users/' . $user->picture);
            }
            $filename = time() . '.' . $request->picture->extension();
            $request->picture->move(public_path('assets/images/users'), $filename);
            $user->picture = $filename;
        }

        $user->save();

        return redirect()->route('user.index')->with('status', 'User berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->picture != 'profile.jpg') {
            File::delete('assets/images/users/' . $user->picture);
        }
        $user->delete();

        return redirect()->route('user.index')->with('status', 'User berhasil dihapus');
    }
}
