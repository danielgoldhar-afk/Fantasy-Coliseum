<?php


function nba_fetch_scores_ajax() {
    $page  = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $date  = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : date('Y-m-d');
	$game =  isset($_POST['game']) ? sanitize_text_field($_POST['game']) : 'nba';
	
	$league = $game;
	
    $per_page = 12;

    $username = '8d3ac286-6e8d-4259-994d-c2e50e';
    $password = 'MYSPORTSFEEDS';
	
	$currentYear  = (int) date('Y');
	$currentMonth = (int) date('n'); // 1–12

	// If we're in Jan–Jun → season started last year
	if ($currentMonth <= 6) {
		$seasonStart = $currentYear - 1;
		$seasonEnd   = $currentYear;
	} 
	// If we're in Jul–Dec → season starts this year
	else {
		$seasonStart = $currentYear;
		$seasonEnd   = $currentYear + 1;
	}

	$season = "{$seasonStart}-{$seasonEnd}";

    $url = "https://api.mysportsfeeds.com/v2.1/pull/{$game}/{$season}-regular/date/{$date}/games.json";
// 	echo $url;
// 	print_r($_POST);
// 	die;
    $response = wp_remote_get($url, [
        'headers' => [
            'Authorization' => 'Basic ' . base64_encode("$username:$password"),
        ],
    ]);

    if (is_wp_error($response)) {
        wp_send_json_error(['message' => 'Error fetching scores.']);
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    $games = $data['games'] ?? [];
    $total_games = count($games);
    $paged_games = array_slice($games, ($page - 1) * $per_page, $per_page);

    ob_start();
    if (empty($paged_games)) {
        echo '<p style="color: #fff">No games found for this date.</p>';
    } else {
        echo '<div class="nba-scoreboard">';
        foreach ($paged_games as $game) {
            $schedule = $game['schedule'];
            $score    = $game['score'];
            $homeTeam = $schedule['homeTeam'];
            $awayTeam = $schedule['awayTeam'];
            $home = $homeTeam['abbreviation'];
            $away = $awayTeam['abbreviation'];
            $homeLogo = esc_url("https://a.espncdn.com/i/teamlogos/nba/500/{$home}.png");
            $awayLogo = esc_url("https://a.espncdn.com/i/teamlogos/nba/500/{$away}.png");

            $dateObj = new DateTime($schedule['startTime']);
            $formatted_time = $dateObj->format('g:i A') . ' WAT';
            $homeScore = $score['homeScoreTotal'] ?? '-';
            $awayScore = $score['awayScoreTotal'] ?? '-';
            ?>
            <div class="nba-game-card">
                <div class="game-time"><?php echo esc_html($formatted_time); ?></div>
                <div class="team-card away-team">
                    <img src="<?php echo $awayLogo; ?>" alt="<?php echo esc_attr($away); ?>">
                    <div>
                        <div class="team-name"><?php echo esc_html($away); ?></div>
                        <div class="team-type">Away</div>
                    </div>
                </div>
                <div class="team-card home-team">
                    <img src="<?php echo $homeLogo; ?>" alt="<?php echo esc_attr($home); ?>">
                    <div>
                        <div class="team-name"><?php echo esc_html($home); ?></div>
                        <div class="team-type">Home</div>
                    </div>
                </div>
                <a href="<?php echo esc_url(site_url('/dashboard/scoreboard/details/?league='.$league.'&id=' . $schedule['id'])); ?>" class="box-score-btn">
                    <i class="dashicons dashicons-calendar-alt"></i> Box Score
                </a>
            </div>
        <?php
        }
        echo '</div>';
    }

    wp_send_json_success([
        'html' => ob_get_clean(),
        'total' => $total_games,
        'per_page' => $per_page,
    ]);
}
add_action('wp_ajax_fetch_game_scores', 'nba_fetch_scores_ajax');
add_action('wp_ajax_nopriv_fetch_game_scores', 'nba_fetch_scores_ajax');