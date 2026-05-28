{{-- Logo untuk auth (halaman login, register, dll) --}}
@if (request()->routeIs('filament.dashboard.auth.*'))
    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 5rem;">
        @foreach (safe_image_urls($setting?->app_logo) as $logo)
            <img src="{{ $logo }}" alt="Logo" style="height: 32px;">
        @endforeach
    </div>
@else
    <div style="display: flex; align-items: center; gap: 8px;">
        @foreach (safe_image_urls($setting?->app_logo) as $logo)
            <img src="{{ $logo }}" alt="Logo" style="height: 32px;">
        @endforeach
    </div>
@endif