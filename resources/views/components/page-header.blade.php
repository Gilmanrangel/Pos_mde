<div class="card mb-4">
    <div class="card-body d-flex justify-content-between align-items-center">

        {{-- LEFT --}}
        <div class="d-flex align-items-center gap-3">

            <div class="icon-box">
                <i class="bi {{ $icon }}"></i>
            </div>

            <div>
                <h5 class="fw-bold mb-0">{{ $title }}</h5>
                <small class="text-muted">{{ $subtitle }}</small>
            </div>

        </div>

        {{-- RIGHT (SLOT) --}}
        <div>
            {{ $slot }}
        </div>

    </div>
</div>