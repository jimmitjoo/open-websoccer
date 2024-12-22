<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Club;
use Carbon\Carbon;

class ClubFinanceController extends Controller
{
    public function index(Request $request)
    {
        $club = $request->user()->club;
        $period = $request->get('period', 'all'); // all, month, year

        $baseQuery = $club->transactions();

        // Filtrera efter period
        if ($period === 'month') {
            $baseQuery->whereMonth('created_at', Carbon::now()->month);
        } elseif ($period === 'year') {
            $baseQuery->whereYear('created_at', Carbon::now()->year);
        }

        // Hämta transaktioner för listan
        $transactions = $club->transactions()
            ->when($period === 'month', fn($q) => $q->whereMonth('created_at', Carbon::now()->month))
            ->when($period === 'year', fn($q) => $q->whereYear('created_at', Carbon::now()->year))
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        // Beräkna summor för perioden
        $periodIncome = $baseQuery->clone()
            ->where('type', 'income')
            ->sum('amount');

        $periodExpenses = $baseQuery->clone()
            ->where('type', 'expense')
            ->sum('amount');

        // Gruppera utgifter efter typ
        $expensesByType = $club->transactions()
            ->selectRaw('description, SUM(amount) as total')
            ->where('type', 'expense')
            ->when($period === 'month', fn($q) => $q->whereMonth('created_at', Carbon::now()->month))
            ->when($period === 'year', fn($q) => $q->whereYear('created_at', Carbon::now()->year))
            ->groupBy('description')
            ->orderByRaw('SUM(amount) DESC')
            ->limit(5)
            ->get();

        return view('clubs.finance', [
            'club' => $club,
            'isOwnClub' => true,
            'transactions' => $transactions,
            'period' => $period,
            'periodIncome' => $periodIncome,
            'periodExpenses' => $periodExpenses,
            'expensesByType' => $expensesByType,
        ]);
    }
}
