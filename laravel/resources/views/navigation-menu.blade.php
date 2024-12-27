<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @auth
                        @if (auth()->user()?->club)
                            <x-nav-link href="{{ route('clubhouse') }}" :active="request()->routeIs('clubhouse')">
                                {{ __('My club') }}
                            </x-nav-link>
                        @else
                            <x-nav-link href="{{ route('choose-club') }}" :active="request()->routeIs('choose-club')">
                                {{ __('Select a club') }}
                            </x-nav-link>
                        @endif
                    @endauth

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="left" width="38">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                        {{ __('Transfers') }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Transfer section') }}
                                </div>



                                @auth
                                    @if (auth()->user()?->club)
                                        <x-dropdown-link href="{{ route('transfer-market.my-listings') }}">
                                            {{ __('My listings') }}
                                        </x-dropdown-link>
                                    @endif
                                @endauth

                                <x-dropdown-link href="{{ route('transfer-market.index') }}">
                                    {{ __('Transfer market') }}
                                </x-dropdown-link>

                                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Free agents') }}
                                </div>

                                <x-dropdown-link href="{{ route('free-agents.index') }}">
                                    {{ __('Search free agents') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>



                    @if (auth()->user()?->role === 'admin')
                        <x-nav-link href="{{ route('admin.leagues.index') }}" :active="request()->routeIs('admin.leagues.*')">
                            {{ __('Manage leagues') }}
                        </x-nav-link>

                        <x-nav-link href="{{ route('admin.seasons.index') }}" :active="request()->routeIs('admin.seasons.*')">
                            {{ __('Manage seasons') }}
                        </x-nav-link>
                    @endif
                </div>

                <div class="flex items-center">
                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos() && Auth::user())
                                    <button
                                        class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                        <img class="size-8 rounded-full object-cover"
                                            src="{{ Auth::user()->profile_photo_url }}"
                                            alt="{{ Auth::user()->name }}" />
                                    </button>
                                @elseif (Auth::user())
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                            {{ Auth::user()?->name }}

                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                    </span>
                                @endif
                            </x-slot>

                            <x-slot name="content">
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Manage Account') }}
                                </div>

                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                        {{ __('API Tokens') }}
                                    </x-dropdown-link>
                                @endif

                                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf

                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="open = ! open"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>

                @auth
                    @if (auth()->user()?->club)
                        <x-responsive-nav-link href="{{ route('clubhouse') }}" :active="request()->routeIs('clubhouse')">
                            {{ __('Min klubb') }}
                        </x-responsive-nav-link>
                    @else
                        <x-responsive-nav-link href="{{ route('choose-club') }}" :active="request()->routeIs('choose-club')">
                            {{ __('Välj en klubb') }}
                        </x-responsive-nav-link>
                    @endif
                @endauth

                <div class="pt-4 pb-1 border-y border-gray-200">
                    <div class="flex items-center px-4">
                        <div>
                            <div class="font-medium text-sm text-gray-500">{{ __('Transfer section') }}</div>
                        </div>
                    </div>
                    @auth
                        @if (auth()->user()?->club)
                            <x-responsive-nav-link href="{{ route('transfer-market.index') }}" :active="request()->routeIs('transfer-market.index')">
                                {{ __('Transfer market') }}
                            </x-responsive-nav-link>
                            <x-responsive-nav-link href="{{ route('transfer-market.my-listings') }}" :active="request()->routeIs('transfer-market.my-listings')">
                                {{ __('My listings') }}
                            </x-responsive-nav-link>
                        @endif
                    @endauth

                    <div class="flex items-center px-4 pt-4 ">
                        <div>
                            <div class="font-medium text-sm text-gray-500">{{ __('Free agents') }}</div>
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">

                        <x-responsive-nav-link href="{{ route('free-agents.index') }}" :active="request()->routeIs('free-agents.index')">
                            {{ __('Free Agents') }}
                        </x-responsive-nav-link>
                    </div>
                </div>



                @if (auth()->user()?->role === 'admin')
                    <x-responsive-nav-link href="{{ route('admin.leagues.index') }}" :active="request()->routeIs('admin.leagues.*')">
                        {{ __('Manage leagues') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link href="{{ route('admin.seasons.index') }}" :active="request()->routeIs('admin.seasons.*')">
                        {{ __('Manage seasons') }}
                    </x-responsive-nav-link>
                @endif
            </div>

            <!-- Responsive Settings Options -->
            @auth
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="flex items-center px-4">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <div class="shrink-0 me-3">
                                <img class="h-10 w-10 rounded-full object-cover"
                                    src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            </div>
                        @endif

                        <div>
                            <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                            {{ __('Profile') }}
                        </x-responsive-nav-link>

                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                            <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                                {{ __('API Tokens') }}
                            </x-responsive-nav-link>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf

                            <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
</nav>
