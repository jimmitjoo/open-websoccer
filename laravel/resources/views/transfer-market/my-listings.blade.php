<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mina transferlistade spelare') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Spelare</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Utgångspris</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Bud</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Åtgärder</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($listings as $listing)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $listing->player->first_name }} {{ $listing->player->last_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ number_format($listing->asking_price) }} kr
                                    </td>
                                    <td class="px-6 py-4">
                                        @forelse($listing->offers->where('status', 'pending') as $offer)
                                            <div class="mb-2 p-2 border rounded dark:border-gray-700">
                                                <p>{{ number_format($offer->amount) }} kr från
                                                    {{ $offer->bidderClub->name }}</p>
                                                <p>Går ut om {{ $offer->deadline->diffForHumans() }}</p>
                                                <div class="mt-2 flex space-x-2">
                                                    <x-primary-button onclick="acceptOffer({{ $offer->id }})"
                                                        class="px-2 py-1 text-xs">
                                                        Acceptera
                                                    </x-primary-button>
                                                    <x-secondary-button onclick="rejectOffer({{ $offer->id }})"
                                                        class="px-2 py-1 text-xs">
                                                        Avböj
                                                    </x-secondary-button>
                                                </div>
                                            </div>
                                        @empty
                                            <span class="text-gray-500 dark:text-gray-400">Inga aktiva bud</span>
                                        @endforelse
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-secondary-button onclick="cancelListing({{ $listing->id }})"
                                            class="text-sm">
                                            Avbryt listing
                                        </x-secondary-button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Du har inga transferlistade spelare
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            async function acceptOffer(offerId) {
                if (!confirm('Är du säker på att du vill acceptera detta bud?')) return;

                try {
                    const response = await fetch(`/transfer-market/offers/${offerId}/accept`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Ett fel uppstod');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Ett fel uppstod');
                }
            }

            async function rejectOffer(offerId) {
                if (!confirm('Är du säker på att du vill avböja detta bud?')) return;

                try {
                    const response = await fetch(`/transfer-market/offers/${offerId}/reject`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Ett fel uppstod');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Ett fel uppstod');
                }
            }

            async function cancelListing(listingId) {
                if (!confirm('Är du säker på att du vill avbryta denna listing?')) return;

                try {
                    const response = await fetch(`/transfer-market/listings/${listingId}/cancel`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Ett fel uppstod');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Ett fel uppstod');
                }
            }
        </script>
    @endpush
</x-app-layout>
