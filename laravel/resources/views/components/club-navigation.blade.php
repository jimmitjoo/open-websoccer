@props(['club', 'isOwnClub', 'currentPage'])

<div class="border-b border-gray-200 dark:border-gray-700">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $club->name }}
        </h2>

        @if ($club->leagues->isNotEmpty())
            <a href="{{ route('leagues.show', [$club->leagues->first(), $club->leagues->first()->pivot->season_id]) }}"
                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                {{ $club->leagues->first()->name }}
            </a>
        @endif
    </div>

    <nav class="space-y-2 space-x-2">
        <x-nav-link :href="route('clubs.show', $club)" :active="request()->routeIs('clubs.show')">
            {{ __('Overview') }}
        </x-nav-link>

        <x-nav-link :href="route('clubs.squad', $club)" :active="request()->routeIs('clubs.squad')">
            {{ __('Squad') }}
        </x-nav-link>

        <x-nav-link :href="route('youth-academy.overview', $club)" :active="request()->routeIs('youth-academy.*')">
            {{ __('Youth academy') }}
            @if ($club->youthAcademy?->hasAvailableYouthPlayer())
                <span
                    class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    {{ __('New player') }}
                </span>
            @endif
        </x-nav-link>

        @if ($isOwnClub)
            <x-nav-link :href="route('training.index')" :active="request()->routeIs('training.index')">
                {{ __('Training') }}
            </x-nav-link>
        @endif

        <x-nav-link :href="route('clubs.matches', $club)" :active="request()->routeIs('clubs.matches')">
            {{ __('Matches') }}
        </x-nav-link>

        @if ($isOwnClub)
            <x-nav-link :href="route('clubhouse')" :active="request()->routeIs('clubhouse')">
                {{ __('Clubhouse') }}
            </x-nav-link>

            <x-nav-link :href="route('club.finance')" :active="request()->routeIs('club.finance')">
                {{ __('Finance') }}
            </x-nav-link>
        @endif
    </nav>
</div>
