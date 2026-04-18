<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Owner Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

<div class="flex">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-gray-900 text-white min-h-screen shadow-xl">
        <div class="px-6 py-6 text-2xl font-bold border-b border-gray-700">
            Owner Panel
        </div>

        <nav class="px-4 mt-4 space-y-2">
            <a href="{{ route('dashboard.owner') }}"
                class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800
                {{ request()->routeIs('dashboard.owner') ? 'bg-gray-800' : '' }}">
                🏠 <span class="ml-3">Dashboard</span>
            </a>

            <a href="{{ route('owner.laporan') }}"
                class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800
                {{ request()->routeIs('owner.laporan') ? 'bg-gray-800' : '' }}">
                📊 <span class="ml-3">Laporan Penjualan</span>
            </a>

            <a href="{{ route('owner.laba_rugi') }}"
                class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800
                {{ request()->routeIs('owner.laba_rugi') ? 'bg-gray-800' : '' }}">
                💰 <span class="ml-3">Laba Rugi</span>
            </a>

            <form action="/logout" method="POST" class="mt-6">
                @csrf
                <button class="w-full px-4 py-3 text-left bg-red-600 rounded-lg hover:bg-red-700">
                    Logout
                </button>
            </form>
        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 p-8">
        @yield('content')
    </main>

</div>

</body>
</html>
