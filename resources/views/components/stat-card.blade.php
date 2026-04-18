<div class="card card-stat h-100">
    <div class="card-body d-flex justify-content-between align-items-center">

        <div>
            <p class="text-muted small mb-1">{{ $label }}</p>
            <h4 class="fw-bold mb-0">{{ $value }}</h4>

            @if(isset($desc))
                <small class="text-muted">{{ $desc }}</small>
            @endif
        </div>

        <div class="icon-stat {{ $color }}">
            <i class="bi {{ $icon }}"></i>
        </div>

    </div>
</div>