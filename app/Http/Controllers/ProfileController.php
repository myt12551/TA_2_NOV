<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show(): View
    {
        return view('profile.show', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(): View
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|string',
            'username' => 'required|alpha_dash|string|max:255|unique:users,username,' . Auth::user()->id,
            'phone' => 'required|numeric|unique:users,phone,' . Auth::user()->id,
            'email' => 'required|email|unique:users,email,' . Auth::user()->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'position' => 'nullable|string|max:255',
            'picture' => 'nullable|image|mimes:jpg,jpeg,png|max:5120'
        ]);

        $user = User::find(Auth::user()->id);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->phone = $request->phone;
        $user->email = $request->email;
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

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui');
    }
}
