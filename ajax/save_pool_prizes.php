<?php


add_action('wp_ajax_fc_save_pool_prizes', 'fc_save_pool_prizes_ajax');
function fc_save_pool_prizes_ajax(){
//     check_ajax_referer('fc_prize_nonce', 'nonce');
	error_reporting(E_ALL);
	
    global $wpdb;
    $pool_id = intval($_POST['pool_id'] ?? 0);
    if (!$pool_id) wp_send_json_error('Invalid pool');

    $prizes_json = stripslashes($_POST['prizes'] ?? '[]');
    $remaining = sanitize_text_field($_POST['remaining_prize'] ?? '');

    $prizes = json_decode($prizes_json, true);
	
    if (!is_array($prizes)) $prizes = [];

    $table = $wpdb->prefix . 'fantasy_pool_prizes';
    $pools_table = $wpdb->prefix . 'fantasy_pools';

    // Simple approach: delete existing prizes and insert new ones
    $wpdb->delete($table, ['pool_id' => $pool_id]);

    foreach ($prizes as $p) {
        $position = isset($p['position']) ? intval($p['position']) : 0;
        $amount = isset($p['amount']) ? floatval($p['amount']) : 0;
        if ($position === 0 && $amount <= 0) {
            // ignore zero row if no amount
            continue;
        }
        $wpdb->insert($table, [
            'pool_id' => $pool_id,
            'position' => $position,
            'amount' => $amount,
            'created_at' => current_time('mysql'),
        ]);
    }

    // Save remaining prize value into pools table (optional for display)
    $wpdb->update($pools_table, ['remaining_prize' => $remaining], ['pool_id' => $pool_id]);

    wp_send_json_success('Saved');
}