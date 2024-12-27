<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My listed players') }}
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
                                    {{ __('Player') }}</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Asking price') }}</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Bid') }}</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Actions') }}</th>
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
                                                        {{ __('Accept') }}
                                                    </x-primary-button>
                                                    <x-secondary-button onclick="rejectOffer({{ $offer->id }})"
                                                        class="px-2 py-1 text-xs">
                                                        {{ __('Reject') }}
                                                    </x-secondary-button>
                                                </div>
                                            </div>
                                        @empty
                                            <span
                                                class="text-gray-500 dark:text-gray-400">{{ __('No active bids') }}</span>
                                        @endforelse
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-secondary-button onclick="cancelListing({{ $listing->id }})"
                                            class="text-sm">
                                            {{ __('Cancel listing') }}
                                        </x-secondary-button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        {{ __('You have no listed players') }}
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
                if (!confirm('{{ __('Are you sure you want to accept this bid?') }}')) return;

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
                        alert(data.message || '{{ __('An error occurred while accepting the bid.') }}');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('{{ __('An error occurred while accepting the bid.') }}');
                }
            }

            async function rejectOffer(offerId) {
                if (!confirm('{{ __('Are you sure you want to reject this bid?') }}')) return;

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
                        alert(data.message || '{{ __('An error occurred while rejecting the bid.') }}');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('{{ __('An error occurred while rejecting the bid.') }}');
                }
            }

            async function cancelListing(listingId) {
                if (!confirm('{{ __('Are you sure you want to cancel this listing?') }}')) return;

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
                        alert(data.message || '{{ __('An error occurred while canceling the listing.') }}');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('{{ __('An error occurred while canceling the listing.') }}');
                }
            }
        </script>
    @endpush
</x-app-layout>
