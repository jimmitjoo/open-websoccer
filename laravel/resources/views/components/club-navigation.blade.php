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

    <nav class="-mb-px flex space-x-8">
        <a href="{{ route('clubs.show', $club) }}" @class([
            'border-b-2 py-4 px-1 text-sm font-medium',
            'border-indigo-500 text-indigo-600 dark:text-indigo-400' =>
                $currentPage === 'overview',
            'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' =>
                $currentPage !== 'overview',
        ])>
            Översikt
        </a>

        <a href="{{ route('clubs.squad', $club) }}" @class([
            'border-b-2 py-4 px-1 text-sm font-medium',
            'border-indigo-500 text-indigo-600 dark:text-indigo-400' =>
                $currentPage === 'squad',
            'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' =>
                $currentPage !== 'squad',
        ])>
            Trupp
        </a>

        @if ($isOwnClub)
            <a href="{{ route('clubhouse') }}" @class([
                'border-b-2 py-4 px-1 text-sm font-medium',
                'border-indigo-500 text-indigo-600 dark:text-indigo-400' =>
                    $currentPage === 'clubhouse',
                'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' =>
                    $currentPage !== 'clubhouse',
            ])>
                Klubbhus
            </a>

            <a href="{{ route('club.finance') }}" @class([
                'border-b-2 py-4 px-1 text-sm font-medium',
                'border-indigo-500 text-indigo-600 dark:text-indigo-400' =>
                    $currentPage === 'finance',
                'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' =>
                    $currentPage !== 'finance',
            ])>
                Ekonomi
            </a>
        @endif
    </nav>
</div>
