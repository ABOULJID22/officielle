@php
    $current = app()->getLocale();
@endphp

@if (filament()->auth()->check())
    <div class="fi-topbar-lang flex items-center gap-1">
        <x-filament::button
            size="xs"
            color="{{ $current === 'fr' ? 'primary' : 'gray' }}"
            tag="a"
            href="/locale/fr"
        >FR</x-filament::button>

        <x-filament::button
            size="xs"
            color="{{ $current === 'en' ? 'primary' : 'gray' }}"
            tag="a"
            href="/locale/en"
        >EN</x-filament::button>
    </div>
@endif
