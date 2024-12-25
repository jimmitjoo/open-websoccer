<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Game;
use App\Models\Club;
use App\Models\Player;
use App\Enums\GameStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MatchSimulator
{
    private InjuryService $injuryService;

    public function __construct(InjuryService $injuryService)
    {
        $this->injuryService = $injuryService;
    }

    public function simulate(Game $match): void
    {
        Log::info("Startar simulering av match", [
            'match_id' => $match->id,
            'home_club' => $match->homeClub->name,
            'away_club' => $match->awayClub->name,
            'scheduled_at' => $match->scheduled_at
        ]);

        // Ladda relationerna explicit
        $match->load(['homeClub.players', 'awayClub.players']);

        Log::debug("Antal spelare", [
            'home_players' => $match->homeClub->players->count(),
            'away_players' => $match->awayClub->players->count()
        ]);

        // Enkel simuleringslogik för början
        $homeScore = random_int(0, 4);
        $awayScore = random_int(0, 3);

        DB::transaction(function () use ($match, $homeScore, $awayScore) {
            Log::info("Uppdaterar matchresultat", [
                'match_id' => $match->id,
                'home_score' => $homeScore,
                'away_score' => $awayScore
            ]);

            // Uppdatera matchresultat
            $match->update([
                'home_score' => $homeScore,
                'away_score' => $awayScore,
                'status' => GameStatus::COMPLETED
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

            $injuryCount = 0;
            // För varje minut i matchen
            for ($minute = 1; $minute <= 90; $minute++) {
                // Kontrollera skaderisk för spelare
                foreach ($match->homeClub->players as $player) {
                    if ($this->checkForInjury($player, $match, $minute)) {
                        $injuryCount++;
                    }
                }
                foreach ($match->awayClub->players as $player) {
                    if ($this->checkForInjury($player, $match, $minute)) {
                        $injuryCount++;
                    }
                }
            }

            Log::info("Match avslutad", [
                'match_id' => $match->id,
                'final_score' => "{$homeScore}-{$awayScore}",
                'injuries' => $injuryCount
            ]);
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

    private function checkForInjury(Player $player, Game $game, int $minute): bool
    {
        // Öka grundrisken till 0.5% per minut
        $baseRisk = 0.001;

        // Öka risken om spelaren har låg stamina
        if ($player->stamina < 50) {
            $baseRisk *= (1 + ((50 - $player->stamina) / 50));
        }

        // Öka risken i slutet av matchen
        if ($minute > 70) {
            $baseRisk *= 1.5;
        }

        // Slumpa om skada inträffar (nu 5 av 1000 per minut som grund)
        if (rand(1, 1000) <= ($baseRisk * 1000)) {
            $injury = $this->injuryService->createMatchInjury($player, $game);

            Log::info("Spelare skadad", [
                'player_id' => $player->id,
                'player_name' => $player->name,
                'match_id' => $game->id,
                'minute' => $minute,
                'injury_type' => $injury->injuryType->name,
                'expected_return' => $injury->expected_return_at->format('Y-m-d')
            ]);

            return true;
        }

        return false;
    }
}
