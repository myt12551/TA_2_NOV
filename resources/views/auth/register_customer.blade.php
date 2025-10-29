@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6 bg-white rounded-xl shadow">
    <h1 class="text-xl font-semibold mb-4">Registrasi Customer</h1>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border p-3">
            <ul class="list-disc pl-5 text-sm text-red-600">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('customer.register.post') }}">
        @csrf

        <div class="mb-3">
            <label class="block text-sm mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full border rounded-lg px-3 py-2">
        </div>

        <div class="mb-3">
            <label class="block text-sm mb-1">Username</label>
            <input type="text" name="username" value="{{ old('username') }}" required
                   class="w-full border rounded-lg px-3 py-2">
        </div>

        <div class="mb-3">
            <label class="block text-sm mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full border rounded-lg px-3 py-2">
        </div>

        <div class="mb-3">
            <label class="block text-sm mb-1">No. HP (opsional)</label>
            <input type="text" name="phone" value="{{ old('phone') }}"
                   class="w-full border rounded-lg px-3 py-2">
        </div>

        <div class="mb-3">
            <label class="block text-sm mb-1">Alamat (opsional)</label>
            <textarea name="address" rows="2" class="w-full border rounded-lg px-3 py-2">{{ old('address') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="block text-sm mb-1">Kata Sandi</label>
            <input type="password" name="password" required
                   class="w-full border rounded-lg px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block text-sm mb-1">Konfirmasi Kata Sandi</label>
            <input type="password" name="password_confirmation" required
                   class="w-full border rounded-lg px-3 py-2">
        </div>

        <button type="submit" class="w-full rounded-lg px-4 py-2 bg-black text-white">
            Daftar
        </button>

        <p class="text-center text-sm mt-3">
            Sudah punya akun?
            <a href="{{ route('customer.login') }}" class="underline">Masuk</a>
        </p>
    </form>
</div>
@endsection
