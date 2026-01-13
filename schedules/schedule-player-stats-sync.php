<?php


/**
 * Dispatch player sync based on league slug
 *
 * @param string $league
 */
function fantasy_sync_players_by_league( $league ) {
    $league = strtolower(trim($league));
    $sync_dir = get_template_directory() . '/sync/';

    log_custom_error("Player sync initiated", 'schedule', "SYNC-{$league}-001", 'low', "Starting player sync for league: {$league}");

    switch ( $league ) {

        case 'nhl':
            $file = $sync_dir . 'fetch_players_nhl.php';
            if ( file_exists($file) ) {
                require_once $file;

                if ( function_exists('fantasy_fetch_players_nhl') ) {
                    log_custom_error("Calling NHL player fetcher", 'schedule', "SYNC-NHL-002", 'low', "Executing fantasy_fetch_players_nhl()");
                    fantasy_fetch_players_nhl();
                } else {
                    log_custom_error("NHL fetch function missing", 'schedule', "SYNC-NHL-003", 'high', "Function fantasy_fetch_players_nhl() not found.");
                }
            } else {
                log_custom_error("NHL sync file missing", 'schedule', "SYNC-NHL-004", 'high', "File not found: {$file}");
            }
            break;

        case 'nfl':
            $file = $sync_dir . 'fetch_players_nfl.php';
            if ( file_exists($file) ) {
                require_once $file;

                if ( function_exists('fantasy_fetch_players_nfl') ) {
                    log_custom_error("Calling NFL player fetcher", 'schedule', "SYNC-NFL-002", 'low', "Executing fantasy_fetch_players_nfl()");
                    fantasy_fetch_players_nfl();
                } else {
                    log_custom_error("NFL fetch function missing", 'schedule', "SYNC-NFL-003", 'high', "Function fantasy_fetch_players_nfl() not found.");
                }
            } else {
                log_custom_error("NFL sync file missing", 'schedule', "SYNC-NFL-004", 'high', "File not found: {$file}");
            }
            break;

        // case 'nba':
        //     $file = $sync_dir . 'fetch_players_nba.php';
        //     if ( file_exists($file) && function_exists('fantasy_fetch_players_nba') ) {
        //         fantasy_fetch_players_nba();
        //     }
        //     break;

        default:
            log_custom_error("Unsupported league", 'schedule', "SYNC-UNKNOWN-001", 'medium', "League slug '{$league}' is not supported.");
            break;
    }

    log_custom_error("Player sync completed for league", 'schedule', "SYNC-{$league}-005", 'low', "Finished player sync for league: {$league}");
}


/**
 * Schedule daily sync if not already scheduled
 */
if ( ! wp_next_scheduled( 'fantasy_sync_all_leagues_daily' ) ) {
    wp_schedule_event( time(), 'daily', 'fantasy_sync_all_leagues_daily' );
    log_custom_error("Scheduled daily sync", 'schedule', "SYNC-SCHED-001", 'low', "Scheduled 'fantasy_sync_all_leagues_daily' event.");
}

add_action( 'fantasy_sync_all_leagues_daily', 'fantasy_sync_all_leagues' );


/**
 * Sync all published sports/leagues
 */
function fantasy_sync_all_leagues() {
    log_custom_error("Starting full league sync", 'schedule', "SYNC-ALL-001", 'low', "Fetching all published sports/leagues");
// 	fantasy_remove_duplicate_players_keep_oldest('fantasy_players');

    $sports = get_posts([
        'post_type'      => 'sport',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    ]);

    if ( empty($sports) ) {
        log_custom_error("No sports found", 'schedule', "SYNC-ALL-002", 'medium', "No published sports found to sync");
        return;
    }

    foreach ($sports as $sport) {
        $league = $sport->post_name;
		
		
// 	    fantasy_remove_duplicate_player_stats_keep_oldest('fantasy_player_stats_'.$league);

        if (!$league) {
            log_custom_error("Sport post missing slug", 'schedule', "SYNC-ALL-003", 'medium', "Sport post ID {$sport->ID} missing post_name");
            continue;
        }

        log_custom_error("Dispatching sync for league", 'schedule', "SYNC-ALL-004", 'low', "Dispatching fantasy_sync_players_by_league for '{$league}'");
        fantasy_sync_players_by_league($league);
    }

    log_custom_error("Full league sync completed", 'schedule', "SYNC-ALL-005", 'low', "Completed syncing all leagues");
}