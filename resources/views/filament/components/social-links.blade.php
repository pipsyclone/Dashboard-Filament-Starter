@php
    $links = [
        'youtube' => $setting?->youtube_link,
        'instagram' => $setting?->instagram_link,
        'tiktok' => $setting?->tiktok_link,
        'facebook' => $setting?->facebook_link,
        'x' => $setting?->x_twitter_link,
    ];

    $hasAny = collect($links)->filter()->isNotEmpty();
@endphp

@if ($hasAny)
    <div class="mt-4 text-center">
        <p class="text-sm text-gray-500 mb-2">Ikuti kami</p>

        <div class="flex items-center justify-center gap-2">
            @if ($links['youtube'])
                <a
                    href="{{ $links['youtube'] }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center justify-center rounded-full text-gray-500 hover:text-red-600 bg-transparent hover:bg-gray-200 h-10 w-10 transition duration-300 ease-in-out"
                >
                    <i class="fa-brands fa-youtube text-lg"></i>
                </a>
            @endif

            @if ($links['instagram'])
                <a href="{{ $links['instagram'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center rounded-full text-gray-500 hover:text-pink-600 bg-transparent hover:bg-gray-200 h-10 w-10 transition duration-300 ease-in-out">
                    <i class="fa-brands fa-instagram text-lg"></i>
                </a>
            @endif

            @if ($links['tiktok'])
                <a href="{{ $links['tiktok'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center rounded-full text-gray-500 hover:text-black bg-transparent hover:bg-gray-200 h-10 w-10 transition duration-300 ease-in-out">
                    <i class="fa-brands fa-tiktok text-lg"></i>
                </a>
            @endif

            @if ($links['facebook'])
                <a href="{{ $links['facebook'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center rounded-full text-gray-500 hover:text-blue-700 bg-transparent hover:bg-gray-200 h-10 w-10 transition duration-300 ease-in-out">
                    <i class="fa-brands fa-facebook text-lg"></i>
                </a>
            @endif

            @if ($links['x'])
                <a href="{{ $links['x'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center rounded-full text-gray-500 hover:text-black bg-transparent hover:bg-gray-200 h-10 w-10 transition duration-300 ease-in-out">
                    <i class="fa-brands fa-x-twitter text-lg"></i>
                </a>
            @endif
        </div>
    </div>
@endif