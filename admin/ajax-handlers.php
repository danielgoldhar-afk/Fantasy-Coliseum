<?php 

add_action('wp_ajax_fm_save_pool', function(){
    check_ajax_referer('fm_nonce');

    global $wpdb;
    $pools = $wpdb->prefix.'fantasy_pools';
    $pool_id = intval($_POST['pool_id']);
    $name = sanitize_text_field($_POST['name']);
    $league_id = intval($_POST['league_id']);

    if($pool_id) {
        $wpdb->update($pools, ['name'=>$name,'league_id'=>$league_id], ['pool_id'=>$pool_id]);
    } else {
        $wpdb->insert($pools, ['name'=>$name,'league_id'=>$league_id,'created_at'=>current_time('mysql')]);
        $pool_id = $wpdb->insert_id;
    }

    wp_send_json_success(['pool_id'=>$pool_id]);
});

add_action('wp_ajax_fm_add_box', function(){
    check_ajax_referer('fm_nonce');

    global $wpdb;
    $boxes = $wpdb->prefix.'fantasy_boxes';
    $pool_id = intval($_POST['pool_id']);
    $name = sanitize_text_field($_POST['name']);
    $max_entries = intval($_POST['max_entries']);

    $wpdb->insert($boxes,['pool_id'=>$pool_id,'name'=>$name,'max_entries'=>$max_entries,'created_at'=>current_time('mysql')]);
    $box_id = $wpdb->insert_id;

    wp_send_json_success(['box_id'=>$box_id,'name'=>$name,'max_entries'=>$max_entries]);
});


// Edit Box
add_action('wp_ajax_fm_edit_box', function(){
    check_ajax_referer('fm_nonce');

    global $wpdb;
    $boxes = $wpdb->prefix.'fantasy_boxes';
    $box_id = intval($_POST['box_id']);
    $name = sanitize_text_field($_POST['name']);
    $max_entries = intval($_POST['max_entries']);

    $wpdb->update($boxes,['name'=>$name,'max_entries'=>$max_entries],['box_id'=>$box_id]);

    wp_send_json_success(['box_id'=>$box_id,'name'=>$name,'max_entries'=>$max_entries]);
});

// Delete Box
add_action('wp_ajax_fm_delete_box', function(){
    check_ajax_referer('fm_nonce');

    global $wpdb;
    $boxes = $wpdb->prefix.'fantasy_boxes';
    $box_id = intval($_POST['box_id']);

    $wpdb->delete($boxes,['box_id'=>$box_id]);

    wp_send_json_success(['box_id'=>$box_id]);
});




// Add Player
add_action('wp_ajax_fm_add_player', function(){
    check_ajax_referer('fm_nonce');
    global $wpdb;
    $players = $wpdb->prefix.'fantasy_players';

    $box_id = intval($_POST['box_id']);
    $name = sanitize_text_field($_POST['player_name']);
    $team = sanitize_text_field($_POST['team']);
    $position = sanitize_text_field($_POST['position']);
    $image = esc_url_raw($_POST['image_url']);

    $wpdb->insert($players, [
        'box_id'=>$box_id,
        'player_name'=>$name,
        'team'=>$team,
        'position'=>$position,
        'image_url'=>$image,
        'created_at'=>current_time('mysql')
    ]);

    $player_id = $wpdb->insert_id;
    wp_send_json_success([
        'player_id'=>$player_id,
        'player_name'=>$name,
        'team'=>$team,
        'position'=>$position,
        'image_url'=>$image
    ]);
});

// Edit Player
add_action('wp_ajax_fm_edit_player', function(){
    check_ajax_referer('fm_nonce');
    global $wpdb;
    $players = $wpdb->prefix.'fantasy_players';

    $player_id = intval($_POST['player_id']);
    $name = sanitize_text_field($_POST['player_name']);
    $team = sanitize_text_field($_POST['team']);
    $position = sanitize_text_field($_POST['position']);
    $image = esc_url_raw($_POST['image_url']);

    $wpdb->update($players, [
        'player_name'=>$name,
        'team'=>$team,
        'position'=>$position,
        'image_url'=>$image
    ], ['player_id'=>$player_id]);

    wp_send_json_success([
        'player_id'=>$player_id,
        'player_name'=>$name,
        'team'=>$team,
        'position'=>$position,
        'image_url'=>$image
    ]);
});

// Delete Player
add_action('wp_ajax_fm_delete_player', function(){
    check_ajax_referer('fm_nonce');
    global $wpdb;
    $players = $wpdb->prefix.'fantasy_players';

    $player_id = intval($_POST['player_id']);
    $wpdb->delete($players, ['player_id'=>$player_id]);

    wp_send_json_success(['player_id'=>$player_id]);
});