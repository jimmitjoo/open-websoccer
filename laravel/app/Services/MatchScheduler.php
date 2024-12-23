<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\League;
use App\Models\Season;
use App\Models\Game;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class MatchScheduler
{
    public function generateSchedule(League $league, Season $season): void
    {
        $leagueSeason = $league->seasons()->where('season_id', $season->id)->firstOrFail();

        $clubs = $league->clubs()
            ->wherePivot('season_id', $season->id)
            ->get();

        if ($clubs->count() < 2) {
            throw new \RuntimeException("Liga {$league->name} har för få lag för att generera schema.");
        }

        // Skapa matchschema (alla möter alla, hem och borta)
        $rounds = $this->generateRoundRobinSchedule($clubs->pluck('id')->toArray());

        // Beräkna datum för matcherna
        $matchDates = $this->calculateMatchDates($season, count($rounds));

        // Skapa matcherna
        foreach ($rounds as $matchday => $fixtures) {
            foreach ($fixtures as $fixture) {
                Game::create([
                    'league_season_id' => $leagueSeason->id,
                    'home_club_id' => $fixture[0],
                    'away_club_id' => $fixture[1],
                    'stadium_id' => $this->getHomeStadium($fixture[0]),
                    'matchday' => $matchday + 1,
                    'scheduled_at' => $matchDates[$matchday],
                    'type' => 'league',
                    'status' => 'scheduled'
                ]);
            }
        }
    }

    private function generateRoundRobinSchedule(array $teamIds): array
    {
        $teams = $teamIds;
        if (count($teams) % 2 !== 0) {
            $teams[] = null; // Lägg till en "dummy" för ojämnt antal
        }

        $rounds = [];
        $teamCount = count($teams);
        $matchesPerRound = $teamCount / 2;

        // Första halvan av säsongen
        for ($round = 0; $round < $teamCount - 1; $round++) {
            $roundMatches = [];

            for ($match = 0; $match < $matchesPerRound; $match++) {
                $home = $teams[$match];
                $away = $teams[$teamCount - 1 - $match];

                if ($home !== null && $away !== null) {
                    $roundMatches[] = [$home, $away];
                }
            }

            $rounds[] = $roundMatches;

            // Rotera lagen (första laget står still)
            array_splice($teams, 1, 0, array_pop($teams));
        }

        // Andra halvan - vänd på hem/borta
        $roundCount = count($rounds);
        for ($i = 0; $i < $roundCount; $i++) {
            $reverseRound = [];
            foreach ($rounds[$i] as $match) {
                $reverseRound[] = [$match[1], $match[0]]; // Byt hem/borta
            }
            $rounds[] = $reverseRound;
        }

        return $rounds;
    }

    private function calculateMatchDates(Season $season, int $totalRounds): array
    {
        $period = CarbonPeriod::create(
            $season->start_date,
            $season->end_date
        );

        $availableDates = [];
        foreach ($period as $date) {
            // Lägg matcher på lördagar och söndagar
            if ($date->isWeekend()) {
                $availableDates[] = $date->copy()->setTime(15, 0);
            }
        }

        if (count($availableDates) < $totalRounds) {
            throw new \RuntimeException('För få tillgängliga speldagar i säsongen');
        }

        // Fördela jämnt över säsongen
        $stride = floor(count($availableDates) / $totalRounds);

        return array_slice($availableDates, 0, $totalRounds, true);
    }

    private function getHomeStadium(int $clubId): ?int
    {
        // Hämta klubbens hemmaarena
        return \App\Models\Club::find($clubId)->stadium_id;
    }
}
