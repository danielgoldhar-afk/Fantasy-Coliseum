<?php


add_action('wp_ajax_save_box_player_status', 'save_box_player_status');
function save_box_player_status() {
    global $wpdb;

    // Security check
    check_ajax_referer('fm_players_nonce', 'nonce');

    $table = $wpdb->prefix . 'fantasy_pool_player_selections';

    $box_id = intval( $_POST['box_id'] ?? 0 );
    $api_id = intval( $_POST['player_api_id'] ?? 0 );
    $selected = intval( $_POST['selected'] ?? 0 );

    if ( ! $box_id || ! $api_id ) {
        wp_send_json_error('Missing box_id or player_api_id');
    }

    if ( $selected === 1 ) {
        // insert if not exists
        $exists = $wpdb->get_var( $wpdb->prepare(
            "SELECT id FROM {$table} WHERE box_id = %d AND player_api_id = %d LIMIT 1",
            $box_id, $api_id
        ) );

        if ( ! $exists ) {
            $inserted = $wpdb->insert( $table, [
                'player_api_id' => $api_id,
                'box_id'        => $box_id,
                'player_name'   => sanitize_text_field( wp_unslash( $_POST['player_name'] ?? '' ) ),
                'team'          => sanitize_text_field( wp_unslash( $_POST['team'] ?? '' ) ),
                'position'      => sanitize_text_field( wp_unslash( $_POST['position'] ?? '' ) ),
                'image_url'     => esc_url_raw( wp_unslash( $_POST['image_url'] ?? '' ) ),
                'created_at'    => current_time('mysql'),
            ] );

            if ( $inserted === false ) {
                wp_send_json_error('DB insert failed');
            }
        }

        wp_send_json_success([ 'message' => 'Player added' ]);
    }

    // selected == 0 -> delete that one player row for this box
    if ( $selected === 0 ) {
        $deleted = $wpdb->delete( $table, [ 'box_id' => $box_id, 'player_api_id' => $api_id ] );
        if ( $deleted === false ) {
            wp_send_json_error('DB delete failed');
        }
        wp_send_json_success([ 'message' => 'Player removed' ]);
    }

    wp_send_json_success([ 'message' => 'OK' ]);
}