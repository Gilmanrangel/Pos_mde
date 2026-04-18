<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <!-- 🔥 WAJIB UNTUK AJAX & FORM -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} | @yield('title')</title>

    {{-- ================= BOOTSTRAP ================= --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- ================= ICON ================= --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- ================= SELECT2 ================= --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- ================= VITE ================= --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ================= EXTRA CSS ================= --}}
    @stack('styles')

</head>

<body>

<div class="d-flex">

    {{-- SIDEBAR --}}
    @include('layouts.partials.sidebar')

    {{-- MAIN --}}
    <div class="content flex-grow-1">

        {{-- TOPBAR --}}
        @include('layouts.partials.topbar')

        {{-- CONTENT --}}
        <div class="fade-in">
            @yield('content')
        </div>

    </div>

</div>

{{-- ================= JS ================= --}}

{{-- BOOTSTRAP --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- JQUERY (WAJIB UNTUK SELECT2) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- SELECT2 --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

{{-- EXTRA SCRIPT --}}
@stack('scripts')

</body>
</html>