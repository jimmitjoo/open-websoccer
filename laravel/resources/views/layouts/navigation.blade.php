<!-- Klubbhus Navigation -->
@if (auth()->user()->club)
    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
        <x-nav-link href="{{ route('clubhouse') }}" :active="request()->routeIs('clubhouse')">
            {{ __('Klubbhus') }}
        </x-nav-link>

        <x-nav-link href="{{ route('clubs.squad', auth()->user()->club) }}" :active="request()->routeIs('clubs.squad')">
            {{ __('Trupp') }}
        </x-nav-link>

        <x-nav-link href="{{ route('training.index') }}" :active="request()->routeIs('training.*')">
            {{ __('Träning') }}
        </x-nav-link>

        @if (auth()->user()->club->id === request()->route('club')?->id)
            <x-nav-link href="{{ route('club.finance') }}" :active="request()->routeIs('club.finance')">
                {{ __('Ekonomi') }}
            </x-nav-link>
        @endif
    </div>
@endif
