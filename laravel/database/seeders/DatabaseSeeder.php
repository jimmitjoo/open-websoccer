<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\League;
use App\Models\Club;
use App\Models\Stadium;
use App\Models\Season;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            InjuryTypeSeeder::class,
            TrainingTypeSeeder::class,
            ManagerSeeder::class,
        ]);



        // Skapa säsong
        $season = Season::create([
            'name' => '2024-4',
            'start_date' => '2024-10-01',
            'end_date' => '2024-12-31',
            'is_active' => true,
        ]);

        // Definiera realistiska städer per land
        $cities = [
            'SE' => [
                // Större städer
                'Stockholm', 'Göteborg', 'Malmö', 'Uppsala', 'Linköping', 'Örebro', 'Helsingborg',
                'Norrköping', 'Västerås', 'Jönköping',
                // Mellanstora städer
                'Umeå', 'Lund', 'Borås', 'Sundsvall', 'Eskilstuna', 'Gävle', 'Halmstad',
                'Södertälje', 'Växjö', 'Karlstad',
                // Mindre städer
                'Kristianstad', 'Trollhättan', 'Kalmar', 'Falun', 'Skellefteå', 'Karlskrona',
                'Östersund', 'Varberg', 'Uddevalla', 'Skövde',
                // Mindre städer (31-50)
                'Nyköping', 'Borlänge', 'Motala', 'Luleå', 'Karlskoga',
                'Örnsköldsvik', 'Landskrona', 'Piteå', 'Trelleborg', 'Ängelholm',
                'Lidköping', 'Alingsås', 'Sandviken', 'Ystad', 'Katrineholm',
                'Värnamo', 'Falkenberg', 'Mariestad', 'Västervik', 'Lidingö',
                // Mindre städer (51-100)
                'Skara', 'Kiruna', 'Köping', 'Enköping', 'Mora', 'Nässjö',
                'Eslöv', 'Avesta', 'Mjölby', 'Härnösand', 'Oskarshamn', 'Kumla',
                'Sala', 'Vetlanda', 'Sollefteå', 'Huskvarna', 'Vänersborg', 'Åmål',
                'Simrishamn', 'Flen', 'Ulricehamn', 'Boden', 'Falköping', 'Nynäshamn',
                'Ronneby', 'Hudiksvall', 'Strängnäs', 'Höganäs', 'Kristinehamn', 'Ljungby',
                'Hässleholm', 'Bollnäs', 'Arvika', 'Ludvika', 'Gällivare', 'Lindesberg',
                'Söderhamn', 'Vimmerby', 'Hallstahammar', 'Tranås', 'Kalix', 'Arboga',
                'Kungsbacka', 'Mölndal', 'Solna', 'Nacka', 'Upplands Väsby', 'Täby',
                'Partille', 'Vallentuna'
            ],
            'EN' => [
                // Större städer
                'London', 'Birmingham', 'Manchester', 'Leeds', 'Liverpool',
                'Newcastle', 'Sheffield', 'Nottingham', 'Bristol', 'Leicester',
                // Mellanstora städer
                'Southampton', 'Portsmouth', 'Brighton', 'Plymouth', 'Stoke',
                'Wolverhampton', 'Derby', 'Coventry', 'Cardiff', 'Middlesbrough',
                // Mindre städer
                'Hull', 'Bradford', 'Blackpool', 'Reading', 'Preston', 'Blackburn',
                'Norwich', 'Luton', 'Swansea', 'Oxford', 'Cambridge', 'Ipswich',
                'Sunderland', 'Watford', 'Exeter', 'Peterborough', 'York', 'Doncaster',
                'Huddersfield', 'Burnley', 'Bolton', 'Bournemouth', 'Wigan', 'Swindon',
                'Northampton', 'Mansfield', 'Grimsby', 'Rochdale', 'Oldham', 'Carlisle',
                'Walsall', 'Scunthorpe', 'Hartlepool', 'Crewe', 'Barrow', 'Stevenage',
                'Harrogate', 'Crawley', 'Morecambe', 'Yeovil', 'Accrington', 'Cheltenham',
                'Forest Green', 'Newport', 'Sutton', 'Colchester', 'Gillingham', 'Maidstone',
                'Woking', 'Aldershot', 'Weymouth', 'Torquay', 'Dover', 'Bromley',
                'Eastleigh', 'Boreham Wood', 'Dagenham', 'Chesterfield', 'Wrexham', 'Stockport'
            ],
            'ES' => [
                // Större städer
                'Madrid', 'Barcelona', 'Valencia', 'Sevilla', 'Zaragoza', 'Málaga', 'Murcia',
                'Palma', 'Las Palmas', 'Bilbao', 'Alicante', 'Córdoba', 'Valladolid', 'Vigo',
                'Gijón', 'Hospitalet', 'Vitoria', 'La Coruña', 'Granada', 'Elche',
                // Mellanstora städer
                'Oviedo', 'Badalona', 'Cartagena', 'Terrassa', 'Jerez', 'Sabadell',
                'Móstoles', 'Santa Cruz', 'Pamplona', 'Almería', 'Alcalá', 'Fuenlabrada',
                'Leganés', 'Getafe', 'Burgos', 'Santander', 'Albacete', 'Castellón',
                'Alcorcón', 'San Sebastián',
                // Mindre städer
                'Logroño', 'Badajoz', 'Salamanca', 'Huelva', 'Lleida', 'Marbella',
                'León', 'Cádiz', 'Tarragona', 'Lorca', 'Jaén', 'Ourense', 'Algeciras',
                'Alcobendas', 'Reus', 'Torrelavega', 'Mataró', 'Linares', 'Gandía',
                'Santiago', 'Cáceres', 'Melilla', 'Ceuta', 'Pontevedra', 'Palencia',
                // Ännu mindre städer
                'Toledo', 'Mérida', 'Ávila', 'Segovia', 'Huesca', 'Guadalajara', 'Soria',
                'Cuenca', 'Zamora', 'Lugo', 'Torremolinos', 'Eivissa', 'Manresa', 'Telde',
                'Vélez-Málaga', 'Ponferrada', 'Torrevieja', 'El Ejido', 'Fuengirola',
                'Benidorm', 'Alcoy', 'Sanlúcar', 'Orihuela', 'Motril', 'Ciudad Real',
                // Mindre städer
                'Viladecans', 'Granollers', 'Arrecife', 'Coslada', 'Vilanova', 'Elda',
                'Rubí', 'Mollet', 'Gavà', 'Sant Adrià', 'Igualada', 'Figueres', 'Tortosa',
                'Vic', 'Mijas', 'Estepona', 'Calahorra', 'Olot', 'Antequera', 'Teruel'
            ],
            'IT' => [
                // Större städer
                'Roma', 'Milano', 'Napoli', 'Torino', 'Palermo', 'Genova', 'Bologna',
                'Firenze', 'Bari', 'Catania', 'Venezia', 'Verona', 'Messina', 'Padova',
                'Trieste', 'Brescia', 'Parma', 'Taranto', 'Prato', 'Modena',
                // Mellanstora städer
                'Reggio Calabria', 'Reggio Emilia', 'Perugia', 'Livorno', 'Ravenna',
                'Cagliari', 'Foggia', 'Rimini', 'Salerno', 'Ferrara', 'Sassari', 'Latina',
                'Giugliano', 'Monza', 'Siracusa', 'Pescara', 'Bergamo', 'Forlì', 'Vicenza',
                'Terni', 'Bolzano', 'Novara', 'Piacenza', 'Ancona', 'Andria',
                // Mindre städer
                'Arezzo', 'Udine', 'Cesena', 'Lecce', 'Pesaro', 'Alessandria', 'Barletta',
                'La Spezia', 'Pisa', 'Pistoia', 'Guidonia', 'Lucca', 'Catanzaro', 'Brindisi',
                'Torre del Greco', 'Treviso', 'Busto Arsizio', 'Como', 'Marsala', 'Grosseto',
                // Ännu mindre städer
                'Sesto San Giovanni', 'Pozzuoli', 'Varese', 'Fiumicino', 'Crotone', 'Carrara',
                'Casoria', 'Savona', 'Cosenza', 'Vittoria', 'Ragusa', 'Imola', 'Matera',
                'Legnano', 'Acerra', 'Marano', 'Benevento', 'Molfetta', 'Agrigento', 'Faenza',
                // Mindre städer
                'Cerignola', 'Moncalieri', 'Foligno', 'Manfredonia', 'Tivoli', 'Cuneo',
                'Trani', 'Bisceglie', 'Bitonto', 'Bagheria', 'Anzio', 'Portici', 'Modica',
                'Sanremo', 'Avellino', 'Teramo', 'Montesilvano', 'Siena', 'Gallarate',
                'Velletri', 'Cava de Tirreni', 'Aversa', 'Civitavecchia', 'Acireale', 'Mazara'
            ],
            'DE' => [
                // Större städer
                'Berlin', 'Hamburg', 'München', 'Köln', 'Frankfurt', 'Stuttgart', 'Düsseldorf',
                'Leipzig', 'Dortmund', 'Essen', 'Bremen', 'Dresden', 'Hannover', 'Nürnberg',
                'Duisburg', 'Bochum', 'Wuppertal', 'Bielefeld', 'Bonn', 'Münster',
                // Mellanstora städer
                'Karlsruhe', 'Mannheim', 'Augsburg', 'Wiesbaden', 'Gelsenkirchen', 'Mönchengladbach',
                'Braunschweig', 'Chemnitz', 'Kiel', 'Aachen', 'Halle', 'Magdeburg', 'Freiburg',
                'Krefeld', 'Lübeck', 'Mainz', 'Erfurt', 'Rostock', 'Kassel', 'Hagen',
                // Mindre städer
                'Hamm', 'Saarbrücken', 'Mülheim', 'Potsdam', 'Ludwigshafen', 'Oldenburg',
                'Leverkusen', 'Osnabrück', 'Solingen', 'Heidelberg', 'Herne', 'Neuss',
                'Darmstadt', 'Paderborn', 'Regensburg', 'Ingolstadt', 'Würzburg', 'Wolfsburg',
                'Fürth', 'Ulm',
                // Ännu mindre städer
                'Heilbronn', 'Pforzheim', 'Göttingen', 'Bottrop', 'Recklinghausen', 'Reutlingen',
                'Koblenz', 'Remscheid', 'Bergisch Gladbach', 'Bremerhaven', 'Jena', 'Trier',
                'Salzgitter', 'Moers', 'Siegen', 'Hildesheim', 'Cottbus', 'Gütersloh',
                'Erlangen', 'Kaiserslautern',
                // Mindre städer
                'Witten', 'Hanau', 'Schwerin', 'Gera', 'Esslingen', 'Ludwigsburg', 'Ratingen',
                'Lünen', 'Dessau', 'Marl', 'Tübingen', 'Flensburg', 'Villingen', 'Velbert',
                'Minden', 'Worms', 'Konstanz', 'Marburg', 'Wilhelmshaven', 'Bamberg'
            ],
        ];

        // Definiera länder och deras ligor
        $countries = [
            'SE' => [
                ['name' => 'Allsvenskan', 'rank' => 1, 'teams' => 8],
                ['name' => 'Superettan', 'rank' => 2, 'teams' => 8],
                ['name' => 'Division 1 Norra', 'rank' => 3, 'teams' => 8],
                ['name' => 'Division 1 Södra', 'rank' => 3, 'teams' => 8],
                ['name' => 'Division 2 Norra Norrland', 'rank' => 4, 'teams' => 8],
                ['name' => 'Division 2 Södra Norrland', 'rank' => 4, 'teams' => 8],
                ['name' => 'Division 2 Norra Svealand', 'rank' => 4, 'teams' => 8],
                ['name' => 'Division 2 Södra Svealand', 'rank' => 4, 'teams' => 8],
            ],
            'EN' => [
                ['name' => 'Premier League', 'rank' => 1, 'teams' => 8],
                ['name' => 'Championship', 'rank' => 2, 'teams' => 8],
                ['name' => 'League One North', 'rank' => 3, 'teams' => 8],
                ['name' => 'League One South', 'rank' => 3, 'teams' => 8],
                ['name' => 'League Two North East', 'rank' => 4, 'teams' => 8],
                ['name' => 'League Two North West', 'rank' => 4, 'teams' => 8],
                ['name' => 'League Two South East', 'rank' => 4, 'teams' => 8],
                ['name' => 'League Two South West', 'rank' => 4, 'teams' => 8],
            ],
            'ES' => [
                ['name' => 'La Liga', 'rank' => 1, 'teams' => 8],
                ['name' => 'Segunda División', 'rank' => 2, 'teams' => 8],
                ['name' => 'Primera RFEF Norte', 'rank' => 3, 'teams' => 8],
                ['name' => 'Primera RFEF Sur', 'rank' => 3, 'teams' => 8],
                ['name' => 'Segunda RFEF Grupo 1', 'rank' => 4, 'teams' => 8],
                ['name' => 'Segunda RFEF Grupo 2', 'rank' => 4, 'teams' => 8],
                ['name' => 'Segunda RFEF Grupo 3', 'rank' => 4, 'teams' => 8],
                ['name' => 'Segunda RFEF Grupo 4', 'rank' => 4, 'teams' => 8],
            ],
            'IT' => [
                ['name' => 'Serie A', 'rank' => 1, 'teams' => 8],
                ['name' => 'Serie B', 'rank' => 2, 'teams' => 8],
                ['name' => 'Serie C Nord', 'rank' => 3, 'teams' => 8],
                ['name' => 'Serie C Sud', 'rank' => 3, 'teams' => 8],
                ['name' => 'Serie D Girone A', 'rank' => 4, 'teams' => 8],
                ['name' => 'Serie D Girone B', 'rank' => 4, 'teams' => 8],
                ['name' => 'Serie D Girone C', 'rank' => 4, 'teams' => 8],
                ['name' => 'Serie D Girone D', 'rank' => 4, 'teams' => 8],
            ],
            'DE' => [
                ['name' => 'Bundesliga', 'rank' => 1, 'teams' => 8],
                ['name' => '2. Bundesliga', 'rank' => 2, 'teams' => 8],
                ['name' => '3. Liga Nord', 'rank' => 3, 'teams' => 8],
                ['name' => '3. Liga Süd', 'rank' => 3, 'teams' => 8],
                ['name' => 'Regionalliga Nord', 'rank' => 4, 'teams' => 8],
                ['name' => 'Regionalliga Süd', 'rank' => 4, 'teams' => 8],
                ['name' => 'Regionalliga West', 'rank' => 4, 'teams' => 8],
                ['name' => 'Regionalliga Ost', 'rank' => 4, 'teams' => 8],
            ],
        ];

        // Skapa ligor och klubbar för varje land
        $usedClubNames = [];
        foreach ($countries as $countryCode => $leagues) {
            foreach ($leagues as $leagueData) {
                $league = League::create([
                    'name' => $leagueData['name'],
                    'country_code' => $countryCode,
                    'level' => 'national',
                    'rank' => $leagueData['rank'],
                    'max_teams' => $leagueData['teams'],
                ]);

                // Antal klubbar att skapa per liga
                $numClubs = $leagueData['teams'];

                // Anpassa klubbnamn baserat på land
                $clubNameFormats = [
                    'SE' => [
                        '%s IF',
                        'IFK %s',
                        'IK %s',
                        '%s SK',
                        '%s FF',
                        'BK %s',
                    ],
                    'EN' => [
                        '%s FC',
                        '%s United',
                        '%s City',
                        '%s Town',
                        '%s Athletic',
                        '%s Rovers',
                    ],
                    'ES' => [
                        'Real %s',
                        'Atlético %s',
                        'CD %s',
                        'Racing %s',
                        'Deportivo %s',
                        'CF %s',
                    ],
                    'IT' => [
                        'AC %s',
                        'Inter %s',
                        '%s Calcio',
                        'US %s',
                        'SS %s',
                        'FC %s',
                    ],
                    'DE' => [
                        '%s FC',
                        'TSV %s',
                        'SV %s',
                        'SC %s',
                        'VfB %s',
                        'SpVgg %s',
                    ],
                ];

                // Skapa klubbar för denna liga
                $availableCities = $cities[$countryCode];
                shuffle($availableCities);

                for ($i = 1; $i <= $numClubs; $i++) {
                    $city = array_pop($availableCities);
                    if (!$city) {
                        // Om vi får slut på städer, återanvänd listan
                        $availableCities = $cities[$countryCode];
                        shuffle($availableCities);
                        $city = array_pop($availableCities);
                    }

                    // Försök skapa ett unikt klubbnamn
                    $clubName = null;
                    $attempts = 0;
                    $maxAttempts = 10; // Förhindra oändlig loop

                    while ($clubName === null && $attempts < $maxAttempts) {
                        $format = fake()->randomElement($clubNameFormats[$countryCode]);
                        $potentialName = sprintf($format, $city);

                        if (!isset($usedClubNames[$potentialName])) {
                            $clubName = $potentialName;
                            $usedClubNames[$potentialName] = true;
                        }

                        $attempts++;
                    }

                    // Om vi inte kunde skapa ett unikt namn efter alla försök
                    if ($clubName === null) {
                        $clubName = sprintf($format, $city) . ' ' . uniqid();
                    }

                    // Anpassa stadionkapacitet baserat på division och land
                    $capacityRanges = [
                        'SE' => [
                            1 => [15000, 35000],
                            2 => [8000, 15000],
                            3 => [3000, 8000],
                            4 => [1000, 3000],
                        ],
                        'EN' => [
                            1 => [30000, 75000],
                            2 => [20000, 35000],
                            3 => [15000, 25000],
                            4 => [10000, 20000],
                        ],
                        'ES' => [
                            1 => [25000, 85000],
                            2 => [15000, 30000],
                            3 => [8000, 20000],
                            4 => [5000, 15000],
                        ],
                        'IT' => [
                            1 => [25000, 80000],
                            2 => [15000, 30000],
                            3 => [8000, 20000],
                            4 => [5000, 15000],
                        ],
                        'DE' => [
                            1 => [30000, 80000],
                            2 => [18000, 35000],
                            3 => [10000, 25000],
                            4 => [5000, 15000],
                        ],
                    ];

                    $range = $capacityRanges[$countryCode][$leagueData['rank']];
                    $capacity = fake()->numberBetween($range[0], $range[1]);

                    // Skapa stadium
                    $stadium = Stadium::create([
                        'name' => fake()->randomElement([
                            "{$city} Arena",
                            "{$city} Stadium",
                            "{$city} Park",
                            "New {$city} Stadium",
                        ]),
                        'capacity_seats' => (int)($capacity * 0.7),
                        'capacity_stands' => (int)($capacity * 0.2),
                        'capacity_vip' => (int)($capacity * 0.1),
                        'level_pitch' => fake()->numberBetween(3, 5),
                        'level_seats' => fake()->numberBetween(3, 5),
                        'level_stands' => fake()->numberBetween(3, 5),
                        'level_vip' => fake()->numberBetween(2, 5),
                        'maintenance_pitch' => fake()->numberBetween(3, 5),
                        'maintenance_facilities' => fake()->numberBetween(3, 5),
                        'price_seats' => fake()->numberBetween(200, 500),
                        'price_stands' => fake()->numberBetween(100, 300),
                        'price_vip' => fake()->numberBetween(800, 2000),
                    ]);

                    // Skapa klubb
                    $club = Club::create([
                        'name' => $clubName,
                        'short_name' => strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $clubName), 0, 3)),
                        'stadium_id' => $stadium->id,
                        'is_active' => true,
                        'balance' => fake()->numberBetween(1000000, 10000000),
                    ]);

                    // Koppla klubben till ligan med statistik
                    $club->leagues()->attach($league->id, [
                        'season_id' => $season->id,
                        'matches_played' => 0,
                        'wins' => 0,
                        'draws' => 0,
                        'losses' => 0,
                        'goals_for' => 0,
                        'goals_against' => 0,
                        'points' => 0,
                        'current_position' => 0,
                        'clean_sheets' => 0,
                        'failed_to_score' => 0,
                    ]);
                }

                // Koppla ligan till säsongen
                $league->seasons()->attach($season->id, [
                    'start_date' => '2024-10-01',
                    'end_date' => '2024-12-31',
                ]);
            }
        }
    }
}
