<x-app-layout>
    <x-slot name="header">
        <x-club-navigation :club="$club" :isOwnClub="$isOwnClub" currentPage="finance" />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Ekonomiöversikt -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Aktuell ekonomi -->
                        <div class="bg-gray-50 dark:bg-gray-900 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Current finances') }}</h3>
                            <div class="space-y-3">
                                <p>
                                    <span class="font-medium">{{ __('Balance') }}:</span>
                                    <span class="{{ $club->balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($club->balance) }} kr
                                    </span>
                                </p>
                                <p>
                                    <span class="font-medium">{{ __('Period income') }}:</span>
                                    <span class="text-green-600">{{ number_format($periodIncome) }} kr</span>
                                </p>
                                <p>
                                    <span class="font-medium">{{ __('Period expenses') }}:</span>
                                    <span class="text-red-600">{{ number_format($periodExpenses) }} kr</span>
                                </p>
                            </div>
                        </div>

                        <!-- Filter -->
                        <div class="bg-gray-50 dark:bg-gray-900 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Visa period') }}</h3>
                            <form method="GET" class="space-y-4">
                                <select name="period" class="w-full rounded-md border-gray-300"
                                    onchange="this.form.submit()">
                                    <option value="all" {{ $period === 'all' ? 'selected' : '' }}>
                                        {{ __('All transactions') }}
                                    </option>
                                    <option value="month" {{ $period === 'month' ? 'selected' : '' }}>
                                        {{ __('This month') }}
                                    </option>
                                    <option value="year" {{ $period === 'year' ? 'selected' : '' }}>
                                        {{ __('This year') }}
                                    </option>
                                </select>
                            </form>
                        </div>

                        <!-- Största utgifter -->
                        @if ($expensesByType->isNotEmpty())
                            <div class="bg-gray-50 dark:bg-gray-900 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold mb-4">{{ __('Largest expenses') }}</h3>
                                <div class="space-y-3">
                                    @foreach ($expensesByType as $expense)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">{{ $expense->description }}</span>
                                            <span class="text-red-600">{{ number_format($expense->total) }} kr</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Transaktioner -->
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Transactions') }}</h3>
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Date') }}
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Description') }}
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Amount') }}
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Type') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800">
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $transaction->created_at->format('Y-m-d H:i') }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $transaction->description }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $transaction->type === 'income' ? '+' : '-' }}{{ number_format($transaction->amount) }}
                                                kr
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $transaction->type === 'income' ? __('Income') : __('Expense') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="p-4">
                                {{ $transactions->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
