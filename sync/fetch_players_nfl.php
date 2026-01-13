<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function fantasy_fetch_players_nfl() {
	
	
	
    global $wpdb;

    $players_table = $wpdb->prefix . 'fantasy_players';
    $stats_table   = $wpdb->prefix . 'fantasy_player_stats_nfl';

    $username = '8d3ac286-6e8d-4259-994d-c2e50e';
    $password = 'MYSPORTSFEEDS';
    $season   = '2025-2026';
    $api_url  = "https://api.mysportsfeeds.com/v2.1/pull/nfl/{$season}-regular/player_stats_totals.json";

    // Scheduler initiated
    log_custom_error('NFL Scheduler Initiated', 'schedule', 'SCH-NFL-001', 'low', 'NFL player stats sync started');

    // API REQUEST
    log_custom_error('NFL API Request Started', 'schedule', 'SCH-NFL-002', 'low', "Fetching data from API: $api_url");

    $response = wp_remote_get($api_url, [
        'timeout' => 60,
        'headers' => [
            'Authorization' => 'Basic ' . base64_encode("$username:$password")
        ]
    ]);

    if (is_wp_error($response)) {
        log_custom_error('NFL API Error', 'schedule', 'SCH-NFL-003', 'high', $response->get_error_message());
        return;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (empty($data['playerStatsTotals'])) {
        log_custom_error('NFL API Data Empty', 'schedule', 'SCH-NFL-004', 'medium', 'No player stats found in API response.');
        return;
    }

    log_custom_error('NFL API Data Retrieved', 'schedule', 'SCH-NFL-005', 'low', count($data['playerStatsTotals']) . ' player stats received');

    // LOOP PLAYERS
    foreach ($data['playerStatsTotals'] as $row) {
        try {
			
			
			
			
            $player = $row['player'];
            $team   = $row['team']['abbreviation'] ?? null;
            $stats  = $row['stats'];

            $player_api_id = intval($player['id']);
            $player_name   = trim(($player['firstName'] ?? '') . ' ' . ($player['lastName'] ?? ''));

            

			
			$wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO {$players_table}
					(
						player_api_id,
						first_name,
						last_name,
						player_name,
						jersey_number,
						team,
						position,
						height,
						weight,
						birth_date,
						age,
						rookie,
						image_url,
						league,
						updated_at
					)
					VALUES
					(
						%d, %s, %s, %s,
						%s, %s, %s,
						%s, %s, %s,
						%d, %d, %s,
						%s, %s
					)
					ON DUPLICATE KEY UPDATE
						first_name    = VALUES(first_name),
						last_name     = VALUES(last_name),
						player_name   = VALUES(player_name),
						jersey_number = VALUES(jersey_number),
						team          = VALUES(team),
						position      = VALUES(position),
						height        = VALUES(height),
						weight        = VALUES(weight),
						birth_date    = VALUES(birth_date),
						age           = VALUES(age),
						rookie        = VALUES(rookie),
						image_url     = VALUES(image_url),
						league        = VALUES(league),
						updated_at    = VALUES(updated_at)
					",
					$player_api_id,
					$player['firstName'] ?? null,
					$player['lastName'] ?? null,
					$player_name,
					$player['jerseyNumber'] ?? null,
					$team,
					$player['primaryPosition'] ?? null,
					$player['height'] ?? null,
					$player['weight'] ?? null,
					$player['birthDate'] ?? null,
					$player['age'] ?? 0,
					$player['rookie'] ?? 0,
					$player['officialImageSrc'] ?? null,
					'nfl',
					current_time('mysql')
				)
			);

					
					
//             log_custom_error("Player saved: $player_name", 'schedule', 'SCH-NFL-006', 'low', "Player ID: $player_api_id, Team: $team");

            // Calculate fantasy score
            $pass_yards     = $stats['passing']['passYards'] ?? 0;
            $pass_td        = $stats['passing']['passTD'] ?? 0;
            $pass_int       = $stats['passing']['passInterceptions'] ?? 0;
            $rush_yards     = $stats['rushing']['rushYards'] ?? 0;
            $rush_td        = $stats['rushing']['rushTD'] ?? 0;
            $rec_yards      = $stats['receiving']['recYards'] ?? 0;
            $rec_td         = $stats['receiving']['recTD'] ?? 0;
            $receptions     = $stats['receiving']['receptions'] ?? 0;
            $tackle_total   = $stats['defense']['tacklesTotal'] ?? 0;
            $sacks          = $stats['defense']['sacks'] ?? 0;
            $interceptions  = $stats['defense']['interceptions'] ?? 0;
            $fumbles_lost   = $stats['fumbles']['fumblesLost'] ?? 0;

            $fantasy_score =
                ($pass_yards * 0.04) + ($pass_td * 4) - ($pass_int * 2) +
                ($rush_yards * 0.1) + ($rush_td * 6) +
                ($rec_yards * 0.1) + ($rec_td * 6) + ($receptions * 1) +
                ($tackle_total * 1) + ($sacks * 2) + ($interceptions * 3) -
                ($fumbles_lost * 2);

           $wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO {$stats_table}
					(
						player_id, season,
						games_played, pass_attempts, pass_completions,
						pass_yards, pass_td, pass_interceptions,
						rush_attempts, rush_yards, rush_td,
						targets, receptions, rec_yards, rec_td,
						tackle_total, sacks, interceptions,
						fumbles_lost, fantasy_score, updated_at
					)
					VALUES
					(
						%d, %s,
						%d, %d, %d,
						%d, %d, %d,
						%d, %d, %d,
						%d, %d, %d, %d,
						%d, %d, %d,
						%d, %f, %s
					)
					ON DUPLICATE KEY UPDATE
						games_played       = VALUES(games_played),
						pass_attempts      = VALUES(pass_attempts),
						pass_completions   = VALUES(pass_completions),
						pass_yards         = VALUES(pass_yards),
						pass_td            = VALUES(pass_td),
						pass_interceptions = VALUES(pass_interceptions),
						rush_attempts      = VALUES(rush_attempts),
						rush_yards         = VALUES(rush_yards),
						rush_td            = VALUES(rush_td),
						targets            = VALUES(targets),
						receptions         = VALUES(receptions),
						rec_yards          = VALUES(rec_yards),
						rec_td             = VALUES(rec_td),
						tackle_total       = VALUES(tackle_total),
						sacks              = VALUES(sacks),
						interceptions      = VALUES(interceptions),
						fumbles_lost       = VALUES(fumbles_lost),
						fantasy_score      = VALUES(fantasy_score),
						updated_at         = VALUES(updated_at)
					",
					$player_api_id,
					$season,
					$stats['gamesPlayed'] ?? 0,
					$stats['passing']['passAttempts'] ?? 0,
					$stats['passing']['passCompletions'] ?? 0,
					$pass_yards,
					$pass_td,
					$pass_int,
					$stats['rushing']['rushAttempts'] ?? 0,
					$rush_yards,
					$rush_td,
					$stats['receiving']['targets'] ?? 0,
					$receptions,
					$rec_yards,
					$rec_td,
					$tackle_total,
					$sacks,
					$interceptions,
					$fumbles_lost,
					round($fantasy_score, 2),
					current_time('mysql')
				)
			);


//             log_custom_error("Stats saved for: $player_name", 'schedule', 'SCH-NFL-007', 'low', "Fantasy score: " . round($fantasy_score, 2));

        } catch (Exception $e) {
            log_custom_error("Player processing failed", 'schedule', 'SCH-NFL-008', 'high', $e->getMessage());
        }
    }

    // GLOBAL RANKING
    $players = $wpdb->get_results(
        $wpdb->prepare("SELECT id FROM $stats_table WHERE season = %s ORDER BY fantasy_score DESC", $season),
        ARRAY_A
    );

    $rank = 1;
    foreach ($players as $p) {
        $wpdb->update($stats_table, ['rank_global' => $rank], ['id' => $p['id']]);
        $rank++;
    }

    log_custom_error("NFL fantasy players sync completed successfully", 'schedule', 'SCH-NFL-009', 'low', "Processed " . count($data['playerStatsTotals']) . " players for season $season.");
}