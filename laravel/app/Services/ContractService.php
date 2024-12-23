<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Contract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ContractService
{
    /**
     * Förhandla om ett nytt kontrakt med en spelare
     */
    public function negotiateNewContract(Player $player, array $data): bool
    {
        try {
            // Validera kontraktslängd om spelaren har ett aktivt kontrakt
            if ($player->hasActiveContract()) {
                $currentRemainingMonths = ceil(Carbon::now()->diffInMonths($player->activeContract->end_date));

                if ($data['duration'] < $currentRemainingMonths) {
                    throw new \Exception(
                        "Du kan inte erbjuda ett kortare kontrakt än spelarens nuvarande kontrakt. " .
                        "Minst {$currentRemainingMonths} månader krävs."
                    );
                }
            }

            // Beräkna sannolikheten för att spelaren accepterar
            $acceptanceProbability = $this->calculateAcceptanceProbability($player, $data);

            \Log::info('Contract negotiation probability', [
                'player_id' => $player->id,
                'club_id' => $player->club_id,
                'offered_salary' => $data['salary'],
                'offered_duration' => $data['duration'],
                'current_salary' => $player->activeContract?->salary,
                'current_remaining_months' => $player->hasActiveContract() ? Carbon::now()->diffInMonths($player->activeContract->end_date) : 0,
                'probability' => $acceptanceProbability
            ]);

            // Slumpa om spelaren accepterar baserat på sannolikheten
            $accepted = rand(1, 100) <= ($acceptanceProbability * 100);

            if ($accepted) {
                DB::beginTransaction();

                // Spara spelarens klubbtillhörighet först
                $player->save();

                \Log::info('Player club_id after save', [
                    'player_id' => $player->id,
                    'club_id' => $player->club_id
                ]);

                // Avsluta eventuellt aktivt kontrakt
                if ($player->hasActiveContract()) {
                    $player->activeContract->update([
                        'end_date' => Carbon::now()
                    ]);
                }

                // Skapa det nya kontraktet genom relationen
                $contract = $player->contracts()->create([
                    'club_id' => $player->club_id,
                    'salary' => $data['salary'],
                    'start_date' => Carbon::now(),
                    'end_date' => Carbon::now()->addMonths($data['duration'])
                ]);

                \Log::info('Contract created', [
                    'contract_id' => $contract->id,
                    'player_id' => $player->id,
                    'club_id' => $contract->club_id,
                    'salary' => $contract->salary
                ]);

                // Skapa transaktion för signeringsbonus
                $signingBonus = $data['salary'] * 0.1;
                $player->club->addTransaction(
                    description: "Signeringsbonus för " . $player->first_name . " " . $player->last_name,
                    amount: $signingBonus,
                    type: 'expense'
                );

                DB::commit();
            }

            return $accepted;
        } catch (\Exception $e) {
            \Log::error('Contract negotiation error', [
                'error' => $e->getMessage(),
                'player_id' => $player->id,
                'club_id' => $player->club_id ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            DB::rollBack();
            throw $e;
        }
    }

    private function calculateAcceptanceProbability(Player $player, array $offer): float
    {
        $currentSalary = $player->activeContract?->salary ?? 0;
        $baseProbability = 0.5; // 50% grundsannolikhet

        // Faktorer som påverkar sannolikheten
        $factors = [
            // Löneökning ger högre sannolikhet
            'salary' => $this->calculateSalaryFactor($currentSalary, $offer['salary']),

            // Kontraktslängd påverkar (kortare kontrakt = högre sannolikhet)
            'duration' => $this->calculateDurationFactor($offer['duration']),

            // Spelarens form påverkar (bättre form = lägre sannolikhet)
            'form' => $this->calculateFormFactor($player->form)
        ];

        // Beräkna total sannolikhet
        $probability = $baseProbability;
        foreach ($factors as $factor => $value) {
            $probability *= $value;
        }

        // Begränsa till mellan 0.1 (10%) och 0.9 (90%)
        return max(0.1, min(0.9, $probability));
    }

    private function calculateSalaryFactor(int $currentSalary, int $offeredSalary): float
    {
        if ($currentSalary === 0) {
            return 1.2; // Högre sannolikhet för spelare utan kontrakt
        }

        $increase = ($offeredSalary - $currentSalary) / $currentSalary;

        // Större löne��kning = högre sannolikhet
        if ($increase >= 0.5) return 1.3;    // 50%+ ökning
        if ($increase >= 0.2) return 1.2;    // 20%+ ökning
        if ($increase >= 0.1) return 1.1;    // 10%+ ökning
        if ($increase >= 0) return 1.0;      // Någon ökning

        // Mycket lägre sannolikhet vid lönesänkning
        if ($increase >= -0.05) return 0.5;  // Upp till 5% minskning
        if ($increase >= -0.1) return 0.3;   // Upp till 10% minskning
        if ($increase >= -0.2) return 0.2;   // Upp till 20% minskning
        return 0.1;                          // Mer än 20% minskning
    }

    private function calculateDurationFactor(int $months): float
    {
        // Kortare kontrakt är mer attraktiva
        if ($months <= 12) return 1.2;     // 1 år eller mindre
        if ($months <= 24) return 1.1;     // 2 år eller mindre
        if ($months <= 36) return 1.0;     // 3 år eller mindre
        if ($months <= 48) return 0.9;     // 4 år eller mindre
        return 0.8;                        // Mer än 4 år
    }

    private function calculateFormFactor(int $form): float
    {
        // Bättre form = svårare att övertyga
        if ($form >= 90) return 0.7;      // Toppform
        if ($form >= 80) return 0.8;
        if ($form >= 70) return 0.9;
        if ($form >= 60) return 1.0;
        if ($form >= 50) return 1.1;
        return 1.2;                       // Dålig form
    }

    /**
     * Avsluta ett kontrakt
     */
    public function terminateContract(Contract $contract): void
    {
        try {
            DB::beginTransaction();

            // Beräkna återstående kontraktsvärde
            $remainingMonths = Carbon::now()->diffInMonths($contract->end_date);
            $terminationCost = $this->calculateTerminationCost($contract, $remainingMonths);

            \Log::info('Contract termination cost calculation', [
                'contract_id' => $contract->id,
                'remaining_months' => $remainingMonths,
                'monthly_salary' => $contract->salary,
                'termination_cost' => $terminationCost
            ]);

            // Skapa transaktion för uppsägningskostnaden
            $contract->club->addTransaction(
                description: "Uppsägning av kontrakt för " . $contract->player->first_name . " " . $contract->player->last_name,
                amount: $terminationCost,
                type: 'expense'
            );

            // Avsluta kontraktet
            $contract->update([
                'end_date' => Carbon::now(),
                'termination_fee' => $terminationCost
            ]);

            // Ta bort spelarens klubbtillhörighet
            $contract->player->update([
                'club_id' => null
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function calculateTerminationCost(Contract $contract, int $remainingMonths): int
    {
        // Grundkostnad: 50% av återstående kontraktsvärde
        $baseCost = ($contract->salary * $remainingMonths) * 0.5;

        // Minimikostnad: 3 månaders lön
        $minimumCost = $contract->salary * 3;

        // Returnera det högsta värdet av minimikostnaden och grundkostnaden
        return max($minimumCost, $baseCost);
    }
}
