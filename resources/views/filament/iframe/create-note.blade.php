@php
// Minimal wrapper that mounts the Filament CreateNote page class inside an iframe.
@endphp

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter note</title>
    @livewireStyles
    <style>
        .filament-app-layout, .filament-sidebar, .filament-topbar, .filament-footer, .filament-main-navigation, .filament-header, .filament-global-search, .filament-breadcrumbs { display: none !important; }
        .filament-main, .filament-main-content, .filament-content, .filament-page { padding: 0 !important; margin: 0 !important; }
        body { background: transparent; }
    </style>
</head>
<body style="padding:16px; background:#f3f4f6; font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;">
    @livewire(\App\Filament\Resources\Calendar\Pages\CreateNote::class)
    @livewireScripts
</body>
</html>