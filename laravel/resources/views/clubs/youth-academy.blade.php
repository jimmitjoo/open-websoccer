<x-app-layout>
    <x-slot name="header">
        <x-club-navigation :club="$club" :isOwnClub="$isOwnClub" currentPage="youth-academy" />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <livewire:youth-academy.overview :club="$club" />
        </div>
    </div>
</x-app-layout>
