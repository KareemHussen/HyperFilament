@php
    // Save theme to session when clicked
    if (request('theme')) {
        session(['theme' => request('theme')]);
    }

    $currentTheme = session('theme', 'orange');
    $currentThemeData = match($currentTheme) {
        'blue' => ['icon' => '🎨', 'name' => 'Blue Theme', 'color' => 'bg-blue-500'],
        'green' => ['icon' => '🌿', 'name' => 'Green Theme', 'color' => 'bg-emerald-500'],
        'orange' => ['icon' => '🔥', 'name' => 'Orange Theme', 'color' => 'bg-orange-500'],
        'red' => ['icon' => '❤️', 'name' => 'Red Theme', 'color' => 'bg-red-500'],
        'purple' => ['icon' => '💜', 'name' => 'Purple Theme', 'color' => 'bg-purple-500'],
        default => ['icon' => '🔥', 'name' => 'Orange Theme', 'color' => 'bg-orange-500']
    };
@endphp

<x-filament::dropdown>
    <x-slot name="trigger">
        <x-filament::button
            color="gray"
            size="sm"
            outlined
        >
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded-full {{ $currentThemeData['color'] }}"></div>
                {{ $currentThemeData['icon'] }} Themes
            </div>
        </x-filament::button>
    </x-slot>

    <x-filament::dropdown.list>
        <x-filament::dropdown.list.item
            wire:click="$refresh"
            onclick="window.location.href = '{{ request()->fullUrlWithQuery(['theme' => 'blue']) }}'"
            icon="heroicon-o-swatch"
            :color="$currentTheme === 'blue' ? 'primary' : 'gray'"
        >
            <div class="flex items-center gap-3">
                <div class="w-4 h-4 rounded-full bg-blue-500"></div>
                🎨 Blue Theme
                @if($currentTheme === 'blue')
                    <x-filament::badge size="xs" color="primary">Active</x-filament::badge>
                @endif
            </div>
        </x-filament::dropdown.list.item>

        <x-filament::dropdown.list.item
            onclick="window.location.href = '{{ request()->fullUrlWithQuery(['theme' => 'green']) }}'"
            icon="heroicon-o-swatch"
            :color="$currentTheme === 'green' ? 'primary' : 'gray'"
        >
            <div class="flex items-center gap-3">
                <div class="w-4 h-4 rounded-full bg-emerald-500"></div>
                🌿 Green Theme
                @if($currentTheme === 'green')
                    <x-filament::badge size="xs" color="success">Active</x-filament::badge>
                @endif
            </div>
        </x-filament::dropdown.list.item>

        <x-filament::dropdown.list.item
            onclick="window.location.href = '{{ request()->fullUrlWithQuery(['theme' => 'orange']) }}'"
            icon="heroicon-o-swatch"
            :color="$currentTheme === 'orange' ? 'primary' : 'gray'"
        >
            <div class="flex items-center gap-3">
                <div class="w-4 h-4 rounded-full bg-orange-500"></div>
                🔥 Orange Theme
                @if($currentTheme === 'orange')
                    <x-filament::badge size="xs" color="warning">Active</x-filament::badge>
                @endif
            </div>
        </x-filament::dropdown.list.item>

        <x-filament::dropdown.list.item
            onclick="window.location.href = '{{ request()->fullUrlWithQuery(['theme' => 'red']) }}'"
            icon="heroicon-o-swatch"
            :color="$currentTheme === 'red' ? 'primary' : 'gray'"
        >
            <div class="flex items-center gap-3">
                <div class="w-4 h-4 rounded-full bg-red-500"></div>
                ❤️ Red Theme
                @if($currentTheme === 'red')
                    <x-filament::badge size="xs" color="danger">Active</x-filament::badge>
                @endif
            </div>
        </x-filament::dropdown.list.item>

        <x-filament::dropdown.list.item
            onclick="window.location.href = '{{ request()->fullUrlWithQuery(['theme' => 'purple']) }}'"
            icon="heroicon-o-swatch"
            :color="$currentTheme === 'purple' ? 'primary' : 'gray'"
        >
            <div class="flex items-center gap-3">
                <div class="w-4 h-4 rounded-full bg-purple-500"></div>
                💜 Purple Theme
                @if($currentTheme === 'purple')
                    <x-filament::badge size="xs" color="info">Active</x-filament::badge>
                @endif
            </div>
        </x-filament::dropdown.list.item>
    </x-filament::dropdown.list>
</x-filament::dropdown>
