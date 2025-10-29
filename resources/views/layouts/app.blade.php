<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Tailwind & app assets (default) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ===== OPTIONAL: Bootstrap 5 (ONLY if this page asks for it) =====
         Aktifkan di view dengan: @section('use_bootstrap', true)
         Agar tidak bentrok, Bootstrap dimuat SETELAH Tailwind,
         dan dipakai hanya di markup yang memang menggunakan kelas Bootstrap. --}}
    @hasSection('use_bootstrap')
        {{-- Bootstrap 5 CSS --}}
        <link rel="stylesheet"
              href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
              integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
              crossorigin="anonymous">
        {{-- (Opsional) Bootstrap Icons --}}
        <link rel="stylesheet"
              href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
        {{-- Sedikit reset agar body terang saat pakai Bootstrap section --}}
        <style>
            body.bootstrap-light {
                background-color: #f8fafc; /* terang */
                color: #111827;            /* abu gelap */
            }
        </style>
    @endif

    {{-- Slot tambahan style per halaman --}}
    @stack('styles')
</head>
<body class="@hasSection('use_bootstrap') bootstrap-light @else font-sans antialiased @endif">

    {{-- ====== Navigation (hanya untuk user login, sama seperti sebelumnya) ====== --}}
    <div class="@hasSection('use_bootstrap') '' @else min-h-screen bg-gray-100 dark:bg-gray-900 @endif">

        @auth
            {{-- Include existing navigation (Tailwind-based) --}}
            @include('layouts.navigation')
            {{-- Tambahkan navigation khusus untuk halaman berbasis Bootstrap (modul procurement) --}}
            @hasSection('use_bootstrap')
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-3">
                    <div class="container">
                        <a class="navbar-brand" href="{{ route('procurement.index') }}">Dashboard Pengadaan</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarProcurement" aria-controls="navbarProcurement" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarProcurement">
                            <ul class="navbar-nav me-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('procurement.index') }}">Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('purchase-requests.create') }}">Buat PR</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            @endif
        @endauth

        {{-- ====== Page Heading (opsional) ====== --}}
        @if (isset($header))
            <header class="@hasSection('use_bootstrap') 'bg-white border-bottom' @else 'bg-white dark:bg-gray-800 shadow' @endif">
                <div class="@hasSection('use_bootstrap') 'container py-3' @else 'max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8' @endif">
                    {{ $header }}
                </div>
            </header>
        @elseif (View::hasSection('header'))
            <header class="@hasSection('use_bootstrap') 'bg-white border-bottom' @else 'bg-white dark:bg-gray-800 shadow' @endif">
                <div class="@hasSection('use_bootstrap') 'container py-3' @else 'max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8' @endif">
                    @yield('header')
                </div>
            </header>
        @endif

        {{-- ====== Page Content ====== --}}
        <main class="@hasSection('use_bootstrap') 'py-3' @else '' @endif">
            @isset($slot)
                {{-- Mode komponen (mis. <x-app-layout>) --}}
                @hasSection('use_bootstrap')
                    {{-- Jika halaman pakai Bootstrap, bungkus slot dalam .container agar rapi --}}
                    <div class="container">
                        {{ $slot }}
                    </div>
                @else
                    {{ $slot }}
                @endif
            @else
                {{-- Mode layout klasik (@extends + @section) --}}
                @hasSection('use_bootstrap')
                    <div class="container">
                        @yield('content')
                    </div>
                @else
                    @yield('content')
                @endif
            @endisset
        </main>
    </div>

    {{-- ===== OPTIONAL: Bootstrap 5 JS (ONLY if this page asks for it) ===== --}}
    @hasSection('use_bootstrap')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
                crossorigin="anonymous"></script>
    @endif

    {{-- Slot tambahan script per halaman --}}
    @stack('scripts')
</body>
</html>