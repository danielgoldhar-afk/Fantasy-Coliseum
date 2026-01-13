<?php

if ( ! wp_next_scheduled('fantasy_calculate_contest_prizes_daily') ) {
    wp_schedule_event(time(), 'daily', 'fantasy_calculate_contest_prizes_daily');
}

add_action(
    'fantasy_calculate_contest_prizes_daily',
    'fantasy_calculate_contest_prizes'
);


function fantasy_calculate_contest_prizes() {

    $leagues = get_posts([
        'post_type'      => 'league',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_query'     => [
            [
                'key'     => 'contest_status',
                'compare' => 'NOT EXISTS'
            ]
        ]
    ]);

    foreach ($leagues as $league) {

        $end_date = get_post_meta($league->ID, 'end_date', true);
        if (!$end_date) continue;

        if ( strtotime($end_date) > current_time('timestamp') ) {
            continue;
        }

        // Already processed?
        if ( get_post_meta($league->ID, 'prizes_calculated', true) ) {
            continue;
        }

        fantasy_distribute_league_prizes($league);
    }
}


function fantasy_distribute_league_prizes( WP_Post $league ) {
    global $wpdb;

    $league_slug = $league->post_name;
    $league_id   = $league->ID;

    $sport       = get_post_meta($league_id, 'game_type', true);
    $prize_pool  = floatval(get_post_meta($league_id, 'prize', true));

    if ($prize_pool <= 0) return;

    $entries_table = $wpdb->prefix . 'fantasy_entries';
    $picks_table   = $wpdb->prefix . 'fantasy_picks';
    $stats_table   = $wpdb->prefix . 'fantasy_player_stats_' . $sport;

    // 1. Get entries
    $entries = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT entry_id, user_id FROM $entries_table WHERE league_id = %s",
            $league_slug
        )
    );

    if (empty($entries)) return;

    // 2. Calculate scores (same as shortcode)
    $results = [];

    foreach ($entries as $entry) {

        $player_ids = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT player_id FROM $picks_table WHERE entry_id = %d",
                $entry->entry_id
            )
        );

        if ($player_ids) {
            $placeholders = implode(',', array_fill(0, count($player_ids), '%d'));
            $scores = $wpdb->get_col(
                $wpdb->prepare(
                    "SELECT fantasy_score FROM $stats_table WHERE player_id IN ($placeholders)",
                    ...$player_ids
                )
            );
            $total = array_sum($scores);
        } else {
            $total = 0;
        }

        $results[] = [
            'user_id' => (int)$entry->user_id,
            'score'   => (float)$total
        ];
    }

    // 3. Rank
    usort($results, fn($a,$b) => $b['score'] <=> $a['score']);

    // 4. Prize structure
    $payouts = [
        1 => 0.70,
        2 => 0.125,
        3 => 0.05
    ];

    foreach ($payouts as $rank => $percent) {

        if (!isset($results[$rank-1])) continue;

        $user_id = $results[$rank-1]['user_id'];
        $amount  = round($prize_pool * $percent, 2);

        fantasy_wallet_credit(
            $user_id,
            $amount,
            $league_id,
            "Rank #{$rank} prize"
        );
    }

    // Platform share (4th – 12.5%)
    $platform_cut = round($prize_pool * 0.125, 2);
    update_option('fantasy_platform_earnings',
        floatval(get_option('fantasy_platform_earnings',0)) + $platform_cut
    );

    // Mark league ended
    update_post_meta($league_id, 'contest_status', 'ended');
    update_post_meta($league_id, 'prizes_calculated', 1);
}


function fantasy_wallet_credit(
    $user_id,
    $amount,
    $league_id = null,
    $reason = ''
) {
    global $wpdb;

    $wallets = $wpdb->prefix . 'fantasy_wallets';
    $logs    = $wpdb->prefix . 'fantasy_wallet_transactions';

    // Create wallet if not exists
    $wpdb->query(
        $wpdb->prepare(
            "INSERT IGNORE INTO $wallets (user_id, balance) VALUES (%d, 0)",
            $user_id
        )
    );

    // Update balance
    $wpdb->query(
        $wpdb->prepare(
            "UPDATE $wallets SET balance = balance + %f WHERE user_id = %d",
            $amount,
            $user_id
        )
    );

    // Transaction log
    $wpdb->insert($logs, [
        'user_id'   => $user_id,
        'league_id' => $league_id,
        'type'      => 'credit',
        'amount'    => $amount,
        'reason'    => $reason,
        'created_at'=> current_time('mysql')
    ]);
}