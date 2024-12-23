<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $league->name }}
            </h2>
            <div>
                <a href="{{ route('admin.leagues.edit', $league) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                    {{ __('Redigera') }}
                </a>
                <a href="{{ route('admin.leagues.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Tillbaka') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('Ligadetaljer') }}
                            </h3>
                            <dl>
                                <div
                                    class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                                        {{ __('Namn') }}
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2">
                                        {{ $league->name }}
                                    </dd>
                                </div>

                                <div
                                    class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                                        {{ __('Land') }}
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2">
                                        {{ $league->country_code }}
                                    </dd>
                                </div>

                                <div
                                    class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                                        {{ __('Nivå') }}
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2">
                                        {{ __($league->level === 'national' ? 'Nationell' : 'Kontinental') }}
                                    </dd>
                                </div>

                                <div
                                    class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                                        {{ __('Rang') }}
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2">
                                        {{ $league->rank }}
                                    </dd>
                                </div>

                                <div
                                    class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                                        {{ __('Max antal lag') }}
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2">
                                        {{ $league->max_teams }}
                                    </dd>
                                </div>

                                <div
                                    class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                                        {{ __('Status') }}
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2">
                                        @if ($league->is_active)
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ __('Aktiv') }}
                                            </span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ __('Inaktiv') }}
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('Säsonger i denna liga') }}
                            </h3>
                            @if ($league->seasons->isNotEmpty())
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($league->seasons as $season)
                                        <li class="py-4">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                        {{ $season->name }}
                                                    </p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $season->start_date->format('Y-m-d') }} -
                                                        {{ $season->end_date->format('Y-m-d') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Inga säsonger kopplade till denna liga.') }}
                                </p>
                            @endif

                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 mt-8">
                                {{ __('Lag i denna liga') }}
                            </h3>
                            @if ($league->clubs->isNotEmpty())
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($league->clubs as $club)
                                        <li class="py-4">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                        {{ $club->name }}
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Inga lag kopplade till denna liga.') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
