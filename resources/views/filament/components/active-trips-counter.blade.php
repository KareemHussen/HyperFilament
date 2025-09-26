<div class="flex items-center space-x-6">
    <!-- Active Trips Counter -->
    <div class="flex items-center space-x-2 px-3 py-1.5 bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-lg">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        <span class="text-sm font-medium">{{ \App\Models\Trip::count() }}</span>
        <span class="text-xs opacity-75 hidden sm:inline">Active</span>
    </div>

    <!-- Available Drivers -->
    <div class="hidden md:flex items-center space-x-2 px-3 py-1.5 bg-green-500/10 text-green-600 dark:text-green-400 rounded-lg">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
        </svg>
        <span class="text-sm font-medium">{{ \App\Models\Driver::count() }}</span>
        <span class="text-xs opacity-75 hidden lg:inline">Drivers</span>
    </div>

    <!-- Available Vehicles -->
    <div class="hidden lg:flex items-center space-x-2 px-3 py-1.5 bg-orange-500/10 text-orange-600 dark:text-orange-400 rounded-lg">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m8.25 4.5V16.5a1.5 1.5 0 011.5-1.5h5.25m-10.5 0h10.5m-10.5 0V11.25a1.125 1.125 0 011.125-1.125h1.875c.621 0 1.125.504 1.125 1.125v3.375M9 7.5V3.375c0-.621.504-1.125 1.125-1.125H11.25c.621 0 1.125.504 1.125 1.125V7.5m-9 4.5h16.5"/>
        </svg>
        <span class="text-sm font-medium">{{ \App\Models\Vehicle::count() }}</span>
        <span class="text-xs opacity-75 hidden xl:inline">Vehicles</span>
    </div>
</div>
