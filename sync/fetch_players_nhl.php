<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
function fantasy_fetch_players_nhl() {
    global $wpdb;

    $players_table = $wpdb->prefix . 'fantasy_players';
    $stats_table   = $wpdb->prefix . 'fantasy_player_stats_nhl';

    $username = '8d3ac286-6e8d-4259-994d-c2e50e';
    $password = 'MYSPORTSFEEDS';
    $season   = "2025-2026";
    $api_url  = "https://api.mysportsfeeds.com/v2.1/pull/nhl/{$season}-regular/player_stats_totals.json";

    // Scheduler initiated
    log_custom_error('NHL Scheduler Initiated', 'schedule', 'SCH-NHL-001', 'low', 'NHL player stats sync started');

    // API request started
    log_custom_error('NHL API Request Started', 'schedule', 'SCH-NHL-002', 'low', "Fetching data from API: $api_url");

    $response = wp_remote_get($api_url, [
        'timeout' => 60,
        'headers' => [
            'Authorization' => 'Basic ' . base64_encode("$username:$password")
        ]
    ]);

    if (is_wp_error($response)) {
        log_custom_error('NHL API Error', 'schedule', 'SCH-NHL-003', 'high', $response->get_error_message());
        return;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (empty($data['playerStatsTotals'])) {
        log_custom_error('NHL API Data Empty', 'schedule', 'SCH-NHL-004', 'medium', 'No player stats found in API response.');
        return;
    }

    log_custom_error('NHL API Data Retrieved', 'schedule', 'SCH-NHL-005', 'low', count($data['playerStatsTotals']) . ' player stats received');

    // Loop players
    foreach ($data['playerStatsTotals'] as $row) {
        try {
            $player = $row['player'];
            $team   = $row['team']['abbreviation'] ?? null;
            $stats  = $row['stats'];

            $player_api_id = intval($player['id']);
            $player_name   = trim(($player['firstName'] ?? '') . ' ' . ($player['lastName'] ?? ''));

            // Save/update players table
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
					'nhl',
					current_time('mysql')
				)
			);

//             log_custom_error("Player saved: $player_name", 'schedule', 'SCH-NHL-006', 'low', "Player ID: $player_api_id, Team: $team");

            // Extract stats
            $games      = $stats['gamesPlayed'] ?? 0;
            $goals      = $stats['scoring']['goals'] ?? 0;
            $assists    = $stats['scoring']['assists'] ?? 0;
            $points     = $stats['scoring']['points'] ?? 0;
            $pp_goals   = $stats['scoring']['powerplayGoals'] ?? 0;
            $sh_goals   = $stats['scoring']['shorthandedGoals'] ?? 0;
            $gw_goals   = $stats['scoring']['gameWinningGoals'] ?? 0;
            $shots      = $stats['skating']['shots'] ?? 0;
            $hits       = $stats['skating']['hits'] ?? 0;
            $takeaways  = $stats['skating']['takeaways'] ?? 0;
            $plus_minus = $stats['skating']['plusMinus'] ?? 0;
            $faceoff_pct = $stats['skating']['faceoffPercent'] ?? 0;
            $shot_pct    = $stats['skating']['shotPercentage'] ?? 0;
            $penalty_min = $stats['penalties']['penaltyMinutes'] ?? 0;
            $fights      = $stats['penalties']['fights'] ?? 0;
            $toi         = $stats['shifts']['timeOnIceSeconds'] ?? 0;

            // Fantasy score calculation
            $fantasy_score =
                ($goals * 3) +
                ($assists * 2) +
                ($points * 1) +
                ($shots * 0.4) +
                ($hits * 0.4) +
                ($takeaways * 0.6) +
                ($pp_goals * 1.5) +
                ($sh_goals * 2) +
                ($gw_goals * 1.5) +
                ($plus_minus * 0.5) -
                ($penalty_min * 0.1) +
                ($fights * 1.5);

           $wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO {$stats_table}
					(
						player_id, season,
						games_played, goals, assists, points,
						powerplay_goals, shorthanded_goals, game_winning_goals,
						plus_minus, shots, shot_percentage,
						hits, takeaways, faceoff_percent,
						penalty_minutes, fights, time_on_ice_seconds,
						fantasy_score, updated_at
					)
					VALUES
					(
						%d, %s,
						%d, %d, %d, %d,
						%d, %d, %d,
						%d, %d, %f,
						%d, %d, %f,
						%d, %d, %d,
						%f, %s
					)
					ON DUPLICATE KEY UPDATE
						games_played        = VALUES(games_played),
						goals               = VALUES(goals),
						assists             = VALUES(assists),
						points              = VALUES(points),
						powerplay_goals     = VALUES(powerplay_goals),
						shorthanded_goals   = VALUES(shorthanded_goals),
						game_winning_goals  = VALUES(game_winning_goals),
						plus_minus          = VALUES(plus_minus),
						shots               = VALUES(shots),
						shot_percentage     = VALUES(shot_percentage),
						hits                = VALUES(hits),
						takeaways           = VALUES(takeaways),
						faceoff_percent     = VALUES(faceoff_percent),
						penalty_minutes     = VALUES(penalty_minutes),
						fights              = VALUES(fights),
						time_on_ice_seconds = VALUES(time_on_ice_seconds),
						fantasy_score       = VALUES(fantasy_score),
						updated_at          = VALUES(updated_at)
					",
					$player_api_id,
					$season,
					$games,
					$goals,
					$assists,
					$points,
					$pp_goals,
					$sh_goals,
					$gw_goals,
					$plus_minus,
					$shots,
					$shot_pct,
					$hits,
					$takeaways,
					$faceoff_pct,
					$penalty_min,
					$fights,
					$toi,
					round($fantasy_score, 2),
					current_time('mysql')
				)
			);


			
			
			
//             log_custom_error("Stats saved for: $player_name", 'schedule', 'SCH-NHL-007', 'low', "Fantasy score: " . round($fantasy_score, 2));

        } catch (Exception $e) {
            log_custom_error("Player processing failed: $player_name", 'schedule', 'SCH-NHL-008', 'high', $e->getMessage());
        }
    }

    // GLOBAL RANKING
    $players = $wpdb->get_results(
        $wpdb->prepare("SELECT id, fantasy_score FROM $stats_table WHERE season = %s ORDER BY fantasy_score DESC", $season),
        ARRAY_A
    );

    $rank = 1;
    foreach ($players as $p) {
        $wpdb->update($stats_table, ['rank_global' => $rank], ['id' => $p['id']]);
        $rank++;
    }

    log_custom_error("NHL fantasy players sync completed successfully", 'schedule', 'SCH-NHL-009', 'low', "Processed " . count($data['playerStatsTotals']) . " players for season $season.");
}