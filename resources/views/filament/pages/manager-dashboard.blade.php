<x-filament-panels::page>
    <div class="space-y-6">

        <!-- Date Range Form Section -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-3">
                    📅 Select Time Range
                </div>
            </x-slot>

            <x-slot name="description">
                Choose date and time range to view available drivers and vehicles
            </x-slot>

            <div class="space-y-4">
                {{ $this->form }}

                <div class="flex justify-center pt-4">
                    {{-- {{ ($this->searchAction)(['size' => 'lg']) }} --}}
                </div>
            </div>
        </x-filament::section>

        @if($this->hasSearched)
            <!-- Stats Cards -->
            @php $stats = $this->getQuickStats(); @endphp
            {{-- <div class="grid grid-cols-4 gap-4">
                <!-- Drivers Card -->
                <x-filament::card class="p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Available Drivers</div>
                            <div class="text-2xl font-bold text-info-600 mt-1">{{ $stats['drivers'] }}</div>
                        </div>
                        <x-filament::icon icon="heroicon-o-users" class="w-8 h-8 text-info-500" />
                    </div>
                </x-filament::card>

                <!-- Vehicles Card -->
                <x-filament::card class="p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Available Vehicles</div>
                            <div class="text-2xl font-bold text-warning-600 mt-1">{{ $stats['vehicles'] }}</div>
                        </div>
                        <x-filament::icon icon="heroicon-o-truck" class="w-8 h-8 text-warning-500" />
                    </div>
                </x-filament::card>

                <!-- Total Card -->
                <x-filament::card class="p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Available</div>
                            <div class="text-2xl font-bold text-success-600 mt-1">{{ $stats['total'] }}</div>
                        </div>
                        <x-filament::icon icon="heroicon-o-check-circle" class="w-8 h-8 text-success-500" />
                    </div>
                </x-filament::card>

                <!-- Companies Card -->
                <x-filament::card class="p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Companies</div>
                            <div class="text-2xl font-bold text-gray-700 mt-1">{{ $stats['companies'] }}</div>
                        </div>
                        <x-filament::icon icon="heroicon-o-building-office" class="w-8 h-8 text-gray-500" />
                    </div>
                </x-filament::card>
            </div> --}}
        @else
            <!-- Empty State -->
            <div class="text-center py-20">
                <h3 class="mt-4 text-lg font-semibold text-gray-900">Select Time Range</h3>
                <p class="mt-2 text-sm text-gray-500">Choose a start and end date/time above and click search to view available resources.</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>
