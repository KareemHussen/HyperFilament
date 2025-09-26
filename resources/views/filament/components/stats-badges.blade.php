<div class="flex items-start gap-1.5 ml-auto">
    <!-- Active Trips -->
    <div class="flex flex-col items-center">
        <x-filament::badge
            color="success"
            size="xs"
            class="flex items-center justify-center gap-1 px-3 py-1.5"
            style="width: 75px; height: 32px;"
        >
            <x-heroicon-s-bolt class="h-3 w-3" />
            <span class="font-semibold text-xs">{{ \App\Models\Trip::count() }}</span>
        </x-filament::badge>
        <span class="text-xs text-gray-600 dark:text-gray-300 mt-1 font-medium">Active Trips</span>
    </div>

    <!-- Available Drivers -->
    <div class="flex flex-col items-center">
        <x-filament::badge
            color="info"
            size="xs"
            class="flex items-center justify-center gap-1 px-3 py-1.5"
            style="width: 75px; height: 32px;"
        >
            <x-heroicon-s-users class="h-3 w-3" />
            <span class="font-semibold text-xs">{{ \App\Models\Driver::count() }}</span>
        </x-filament::badge>
        <span class="text-xs text-gray-600 dark:text-gray-300 mt-1 font-medium">Drivers</span>
    </div>

    <!-- Available Vehicles -->
    <div class="flex flex-col items-center">
        <x-filament::badge
            color="warning"
            size="xs"
            class="flex items-center justify-center gap-1 px-3 py-1.5"
            style="width: 75px; height: 32px;"
        >
            <x-heroicon-s-truck class="h-3 w-3" />
            <span class="font-semibold text-xs">{{ \App\Models\Vehicle::count() }}</span>
        </x-filament::badge>
        <span class="text-xs text-gray-600 dark:text-gray-300 mt-1 font-medium">Vehicles</span>
    </div>
</div>
