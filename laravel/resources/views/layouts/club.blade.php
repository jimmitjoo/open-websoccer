<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $club->name }}
            </h2>
            @if (auth()->check() && auth()->user()->club?->id === $club->id)
                <span class="text-sm text-gray-500">
                    {{ __('Manager') }}
                </span>
            @endif
        </div>
    </x-slot>

    @include('club.partials.navigation')

    <main>
        {{ $slot }}
    </main>
</x-app-layout>
