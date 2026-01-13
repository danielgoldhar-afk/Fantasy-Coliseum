<?php


add_action('wp_ajax_fc_get_pool_prizes', 'fc_get_pool_prizes_ajax');
function fc_get_pool_prizes_ajax(){
    check_ajax_referer('fc_prize_nonce', 'nonce');

    global $wpdb;
    $pool_id = intval($_POST['pool_id'] ?? 0);
    if (!$pool_id) wp_send_json_error('Invalid pool');

    $table = $wpdb->prefix . 'fantasy_pool_prizes';

    $rows = $wpdb->get_results($wpdb->prepare("SELECT id, position, amount FROM $table WHERE pool_id=%d ORDER BY position ASC, id ASC", $pool_id));
    // Also return remaining_prize (we store in pools table OR you can store in new table; below we try pools table)
    $pools_table = $wpdb->prefix . 'fantasy_pools';
    $remaining = $wpdb->get_var($wpdb->prepare("SELECT remaining_prize FROM $pools_table WHERE pool_id=%d", $pool_id));
    wp_send_json_success(['prizes' => $rows, 'remaining_prize' => $remaining]);
}