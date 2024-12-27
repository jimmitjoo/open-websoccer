<div>
    <div class="mb-4 space-y-4">
        <input wire:model.live="search" type="search" placeholder="{{ __('Search club...') }}" class="w-full rounded-lg">

        <select wire:model.live="league" class="w-full rounded-lg">
            <option value="">{{ __('All leagues') }}</option>
            @foreach ($leagues as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($clubs as $club)
            <div class="p-4 bg-white rounded-lg shadow">
                @if ($club->logo_path)
                    <img src="{{ Storage::url($club->logo_path) }}" alt="{{ $club->name }}" class="w-24 h-24 mx-auto">
                @endif

                <h3 class="text-lg font-bold">{{ $club->name }}</h3>
                <p class="text-sm text-gray-600">{{ $club->league->name }}</p>

                <div class="mt-2">
                    <p>{{ __('Budget') }}: {{ number_format($club->budget) }} {{ __('kr') }}</p>
                </div>

                <button wire:click="chooseClub({{ $club->id }})"
                    class="mt-4 w-full bg-blue-600 text-white px-4 py-2 rounded-lg">
                    {{ __('Select this club') }}
                </button>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $clubs->links() }}
    </div>
</div>
