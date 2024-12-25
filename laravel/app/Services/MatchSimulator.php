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
    private FormService $formService;

    public function __construct(InjuryService $injuryService, FormService $formService)
    {
        $this->injuryService = $injuryService;
        $this->formService = $formService;
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

        DB::transaction(function () use ($match) {
            // Simulera matchresultat
            $homeScore = random_int(0, 4);
            $awayScore = random_int(0, 3);

            // Uppdatera matchresultat
            $match->update([
                'home_score' => $homeScore,
                'away_score' => $awayScore,
                'status' => GameStatus::COMPLETED
            ]);

            // Simulera spelarnas prestationer och uppdatera form
            foreach ($match->homeClub->players as $player) {
                // Simulera ett matchbetyg (1-10)
                $rating = $this->calculatePlayerRating($player, $homeScore > $awayScore);
                $this->formService->calculateMatchImpact($player, $match, $rating);

                // Kontrollera skador för varje minut i matchen
                for ($minute = 1; $minute <= 90; $minute++) {
                    if ($this->shouldCreateInjury($player, $minute)) {
                        $this->injuryService->createMatchInjury($player, $match);
                        break; // Avbryt loop om spelaren blir skadad
                    }
                }
            }

            foreach ($match->awayClub->players as $player) {
                $rating = $this->calculatePlayerRating($player, $awayScore > $homeScore);
                $this->formService->calculateMatchImpact($player, $match, $rating);

                // Kontrollera skador för varje minut i matchen
                for ($minute = 1; $minute <= 90; $minute++) {
                    if ($this->shouldCreateInjury($player, $minute)) {
                        $this->injuryService->createMatchInjury($player, $match);
                        break; // Avbryt loop om spelaren blir skadad
                    }
                }
            }

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

    private function calculatePlayerRating(Player $player, bool $teamWon): float
    {
        // Basbetyg mellan 5.0 och 8.0
        $baseRating = 5.0 + (mt_rand(0, 30) / 10);

        // Justera baserat på spelarens form
        $formBonus = ($player->form - 50) / 20; // -2.5 till +2.5

        // Vinnande lag får en bonus
        $winBonus = $teamWon ? 0.5 : 0;

        // Beräkna slutligt betyg (min 1.0, max 10.0)
        return max(1.0, min(10.0, $baseRating + $formBonus + $winBonus));
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

    private function shouldCreateInjury(Player $player, int $minute): bool
    {
        // Öka grundrisken till 0.5% per minut
        $baseRisk = 0.001;

        // Öka risken om spelaren har väldigt låg stamina
        if ($player->stamina < 15) {
            $baseRisk *= 3.5;
        } elseif ($player->stamina > 30) {
            $baseRisk *= 1.25;
        } elseif ($player->stamina > 50) {
            $baseRisk *= 1.1;
        } elseif ($player->stamina > 75) {
            $baseRisk *= 0.8;
        } elseif ($player->stamina > 85) {
            $baseRisk *= 0.5;
        }

        // Öka risken i slutet av matchen
        if ($minute > 70) {
            $baseRisk *= 1.5;
        }

        // Slumpa om skada inträffar
        return rand(1, 1000) <= ($baseRisk * 1000);
    }
}
