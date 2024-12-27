<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Transfer market') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Filtreringsformulär -->
            <div class="p-6 mb-6 bg-white rounded-lg shadow-sm">
                <form action="{{ route('transfer-market.index') }}" method="GET"
                    class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <x-input-label for="position" value="{{ __('Position') }}" />
                        <x-select-input id="position" name="position" class="block w-full mt-1">
                            <option value="">{{ __('All positions') }}</option>
                            <option value="GK" @selected(request('position') === 'GK')>{{ __('Goalkeeper') }}</option>
                            <option value="DF" @selected(request('position') === 'DF')>{{ __('Defender') }}</option>
                            <option value="MF" @selected(request('position') === 'MF')>{{ __('Midfielder') }}</option>
                            <option value="FW" @selected(request('position') === 'FW')>{{ __('Forward') }}</option>
                        </x-select-input>
                    </div>

                    <div>
                        <x-input-label for="max_price" value="{{ __('Max price') }}" />
                        <x-text-input type="number" id="max_price" name="max_price" value="{{ request('max_price') }}"
                            class="block w-full mt-1" />
                    </div>

                    <div class="flex items-end">
                        <x-primary-button type="submit">
                            {{ __('Filter') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Listade spelare -->
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        {{ __('Player') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        {{ __('Club') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        {{ __('Position') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                                        {{ __('Asking price') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($listings as $listing)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $listing->player->first_name }}
                                                        {{ $listing->player->last_name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $listing->player->age }} år
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $listing->club->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                                {{ $listing->player->position }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-right text-gray-500 whitespace-nowrap">
                                            {{ number_format($listing->asking_price) }} kr
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-center whitespace-nowrap">
                                            @if ($listing->club_id !== auth()->user()->club->id)
                                                <div>
                                                    @php
                                                        $myOffer = $listing->offers
                                                            ->where('bidding_club_id', auth()->user()->club->id)
                                                            ->where('status', 'pending')
                                                            ->first();
                                                    @endphp

                                                    @if ($myOffer)
                                                        <div class="text-sm">
                                                            <p class="mb-2">{{ __('Your bid') }}:
                                                                {{ number_format($myOffer->amount) }} kr</p>
                                                            <x-secondary-button
                                                                onclick="withdrawOffer({{ $myOffer->id }})"
                                                                class="text-xs">
                                                                {{ __('Withdraw bid') }}
                                                            </x-secondary-button>
                                                        </div>
                                                    @else
                                                        <x-primary-button
                                                            onclick="openBidModal({{ $listing->id }}, '{{ $listing->player->first_name }} {{ $listing->player->last_name }}', {{ $listing->asking_price }})"
                                                            class="text-xs">
                                                            {{ __('Place bid') }}
                                                        </x-primary-button>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-500">{{ __('Your player') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            {{ __('No players listed on the transfer market right now.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $listings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for placing bids -->
    <div id="bidModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100" id="playerName"></h3>
                <div class="mt-2">
                    <form id="bidForm" class="space-y-4">
                        @csrf
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Bid (kr)') }}</label>
                            <input type="number" name="amount" id="amount" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-sm text-gray-500" id="minPrice"></p>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="closeBidModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                {{ __('Place bid') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let currentListingId = null;
            const bidModal = document.getElementById('bidModal');
            const bidForm = document.getElementById('bidForm');
            const playerNameElement = document.getElementById('playerName');
            const minPriceElement = document.getElementById('minPrice');
            const amountInput = document.getElementById('amount');

            function openBidModal(listingId, playerName, askingPrice) {
                currentListingId = listingId;
                playerNameElement.textContent = `{{ __('Place bid on') }} ${playerName}`;
                minPriceElement.textContent = `{{ __('Asking price') }}: ${Number(askingPrice).toLocaleString()} kr`;
                amountInput.min = askingPrice;
                bidModal.classList.remove('hidden');
            }

            function closeBidModal() {
                bidModal.classList.add('hidden');
                bidForm.reset();
            }

            bidForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const amount = amountInput.value;

                try {
                    const response = await fetch(`/transfer-market/listings/${currentListingId}/offers`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            amount: amount
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        closeBidModal();
                        window.location.reload();
                    } else {
                        alert(data.message || '{{ __('An error occurred while placing the bid.') }}');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('{{ __('An error occurred while placing the bid.') }}');
                }
            });

            // Stäng modal om man klickar utanför
            bidModal.addEventListener('click', function(e) {
                if (e.target === bidModal) {
                    closeBidModal();
                }
            });

            async function withdrawOffer(offerId) {
                if (!confirm('{{ __('Are you sure you want to withdraw your bid?') }}')) return;

                try {
                    const response = await fetch(`/transfer-market/offers/${offerId}/withdraw`, {
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
                        alert(data.message || '{{ __('An error occurred while withdrawing the bid.') }}');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('{{ __('An error occurred while withdrawing the bid.') }}');
                }
            }
        </script>
    @endpush
</x-app-layout>
