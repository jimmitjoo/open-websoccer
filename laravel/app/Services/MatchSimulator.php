<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Game;
use App\Models\Club;
use Illuminate\Support\Facades\DB;

class MatchSimulator
{
    public function simulate(Game $match): void
    {
        // Enkel simuleringslogik för början
        $homeScore = random_int(0, 4);
        $awayScore = random_int(0, 3);

        DB::transaction(function () use ($match, $homeScore, $awayScore) {
            // Uppdatera matchresultat
            $match->update([
                'home_score' => $homeScore,
                'away_score' => $awayScore,
                'status' => 'completed'
            ]);

            // Uppdatera ligastatistik
            $this->updateLeagueStats(
                $match->homeClub,
                $match->awayClub,
                $match->league_id,
                $match->season_id,
                $homeScore,
                $awayScore
            );
        });
    }

    private function updateLeagueStats(
        Club $homeClub,
        Club $awayClub,
        int $leagueId,
        int $seasonId,
        int $homeScore,
        int $awayScore
    ): void {
        // Uppdatera hemmalaget
        $homeStats = [
            'matches_played' => DB::raw('matches_played + 1'),
            'goals_for' => DB::raw('goals_for + ' . $homeScore),
            'goals_against' => DB::raw('goals_against + ' . $awayScore),
        ];

        if ($homeScore > $awayScore) {
            $homeStats['wins'] = DB::raw('wins + 1');
            $homeStats['points'] = DB::raw('points + 3');
        } elseif ($homeScore === $awayScore) {
            $homeStats['draws'] = DB::raw('draws + 1');
            $homeStats['points'] = DB::raw('points + 1');
        } else {
            $homeStats['losses'] = DB::raw('losses + 1');
        }

        // Lägg till clean sheets och failed to score
        if ($awayScore === 0) {
            $homeStats['clean_sheets'] = DB::raw('clean_sheets + 1');
        }
        if ($homeScore === 0) {
            $homeStats['failed_to_score'] = DB::raw('failed_to_score + 1');
        }

        // Uppdatera bortalaget
        $awayStats = [
            'matches_played' => DB::raw('matches_played + 1'),
            'goals_for' => DB::raw('goals_for + ' . $awayScore),
            'goals_against' => DB::raw('goals_against + ' . $homeScore),
        ];

        if ($awayScore > $homeScore) {
            $awayStats['wins'] = DB::raw('wins + 1');
            $awayStats['points'] = DB::raw('points + 3');
        } elseif ($homeScore === $awayScore) {
            $awayStats['draws'] = DB::raw('draws + 1');
            $awayStats['points'] = DB::raw('points + 1');
        } else {
            $awayStats['losses'] = DB::raw('losses + 1');
        }

        // Lägg till clean sheets och failed to score för bortalaget
        if ($homeScore === 0) {
            $awayStats['clean_sheets'] = DB::raw('clean_sheets + 1');
        }
        if ($awayScore === 0) {
            $awayStats['failed_to_score'] = DB::raw('failed_to_score + 1');
        }

        // Uppdatera statistiken i league_club_statistics tabellen
        DB::table('league_club_statistics')
            ->where('club_id', $homeClub->id)
            ->where('league_id', $leagueId)
            ->where('season_id', $seasonId)
            ->update($homeStats);

        DB::table('league_club_statistics')
            ->where('club_id', $awayClub->id)
            ->where('league_id', $leagueId)
            ->where('season_id', $seasonId)
            ->update($awayStats);
    }
}
