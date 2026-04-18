<div class="topbar d-flex justify-content-between align-items-center">

    {{-- LEFT (TOGGLE ONLY) --}}
    <div>
        <button onclick="toggleSidebar()" class="btn-soft">
            <i class="bi bi-list"></i>
        </button>
    </div>

    {{-- RIGHT (USER) --}}
    <div class="d-flex align-items-center gap-3">

        {{-- OPTIONAL: NOTIFICATION --}}
        <button class="btn-soft position-relative">
            <i class="bi bi-bell"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                3
            </span>
        </button>

        {{-- USER --}}
        <div class="user-box d-flex align-items-center gap-2">
            <i class="bi bi-person-circle"></i>
            <span>{{ Auth::user()->name }}</span>
        </div>

    </div>

</div>