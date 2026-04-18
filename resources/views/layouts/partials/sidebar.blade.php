<div class="sidebar p-3" id="sidebar">

    <h4 class="mb-4">{{ ucfirst(Auth::user()->role) }}</h4>

    {{-- ADMIN --}}
    @if(Auth::user()->role === 'admin')

    <a href="{{ route('dashboard.admin') }}" class="{{ request()->is('dashboard/admin') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Dashboard">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('produk.index') }}" class="{{ request()->is('produk*') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Produk">
        <i class="bi bi-box-seam"></i>
        <span>Produk</span>
    </a>

    <a href="{{ route('supplier.index') }}" class="{{ request()->is('supplier*') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Supplier">
        <i class="bi bi-truck"></i>
        <span>Supplier</span>
    </a>

    <a href="{{ route('pembelian.index') }}" class="{{ request()->is('pembelian*') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Pembelian">
        <i class="bi bi-receipt"></i>
        <span>Pembelian</span>
    </a>

    <a href="{{ route('laporan.index') }}" class="{{ request()->is('laporan*') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Laporan">
        <i class="bi bi-bar-chart"></i>
        <span>Laporan</span>
    </a>

    {{-- KASIR --}}
    @elseif(Auth::user()->role === 'kasir')

    <a href="{{ route('kasir.transaksi') }}" class="{{ request()->is('kasir/transaksi*') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Transaksi">
        <i class="bi bi-cart"></i>
        <span>Transaksi</span>
    </a>

    <a href="{{ route('kasir.history') }}" class="{{ request()->is('kasir/history*') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Riwayat">
        <i class="bi bi-clock-history"></i>
        <span>Riwayat</span>
    </a>

    {{-- OWNER --}}
    @elseif(Auth::user()->role === 'owner')

    <a href="{{ route('dashboard.owner') }}" class="{{ request()->is('dashboard/owner') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Dashboard">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('owner.laporan') }}" class="{{ request()->is('owner/laporan*') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Laporan">
        <i class="bi bi-bar-chart"></i>
        <span>Laporan</span>
    </a>

    <a href="{{ route('owner.laba_rugi') }}" class="{{ request()->is('owner/laba-rugi*') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Laba Rugi">
        <i class="bi bi-cash-stack"></i>
        <span>Laba Rugi</span>
    </a>

    @endif

    {{-- LOGOUT --}}
    <div class="logout">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button data-bs-toggle="tooltip" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>

</div>