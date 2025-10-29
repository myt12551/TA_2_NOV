<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AbsenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('absence.index', [
            'absences' => Absence::all()->sortByDesc('created_at'),
            'type' => 'show'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (auth()->user()->role != 'owner') {
            if ($user->role == 'supervisor' || $user->role == 'owner') {
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
    public function update(Request $request, User $user)
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

        $user->name = $request->name;
        $user->username = $request->username;
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
    public function destroy(User $user)
    {
        if ($user->picture != 'profile.jpg') {
            File::delete('assets/images/users/' . $user->picture);
        }
        $user->delete();

        return redirect()->route('user.index')->with('status', 'User berhasil dihapus');
    }
}
