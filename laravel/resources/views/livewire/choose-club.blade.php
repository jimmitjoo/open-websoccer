<div class="p-6">
    <div class="mb-6 space-y-4">
        <input wire:model.live="search" type="search" placeholder="Sök klubb..."
            class="w-full rounded-lg border-gray-300 dark:border-gray-700">

        <select wire:model.live="league" class="w-full rounded-lg border-gray-300 dark:border-gray-700">
            <option value="">Alla ligor</option>
            @foreach ($leagues as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>
    </div>

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($clubs as $club)
            <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg overflow-hidden">
                <div class="p-4">
                    <h3 class="text-lg font-bold">{{ $club->name }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $club->leagues->first()?->name ?? 'Ingen liga' }}
                    </p>
                    <div class="mt-2 space-y-1">
                        <p>Budget: {{ number_format($club->budget) }} kr</p>
                        <p>Kapacitet:
                            {{ number_format($club->stadium->capacity_seats + $club->stadium->capacity_stands + $club->stadium->capacity_vip) }}
                        </p>
                    </div>
                    <button wire:click="chooseClub({{ $club->id }})"
                        class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Välj denna klubb
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $clubs->links() }}
    </div>
</div>
