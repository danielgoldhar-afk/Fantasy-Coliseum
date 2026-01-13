<?php

add_action('wp_ajax_create_league', 'handle_create_league_cpt');
add_action('wp_ajax_nopriv_create_league', 'handle_create_league_cpt');
function handle_create_league_cpt() {

    // Generate safe slug for league
    $league_key = strtolower(bin2hex(random_bytes(6))); 

    // Create league post
    $post_id = wp_insert_post([
        'post_type'   => 'league',
        'post_status' => 'publish',
        'post_title'  => sanitize_text_field($_POST['league_name'] ?? 'Untitled League'),
        'post_name'   => $league_key,
        'post_author' => get_current_user_id(),
        'post_content'=> wp_kses_post($_POST['description'] ?? ''),
    ], true);

    if (is_wp_error($post_id)) {
        wp_send_json([
            'success' => false,
            'message' => 'Error creating league: ' . $post_id->get_error_message(),
        ]);
    }

    /* -------------------------------------------------
       SAVE FIELDS EXACTLY AS FORM NAMES
    --------------------------------------------------*/

    update_post_meta($post_id, 'team_name', sanitize_text_field($_POST['team_name'] ?? ''));
    update_post_meta($post_id, 'league_type', sanitize_text_field($_POST['league_type'] ?? ''));
    update_post_meta($post_id, 'game_type', sanitize_text_field($_POST['game_type'] ?? ''));
    update_post_meta($post_id, 'draft_type', sanitize_text_field($_POST['draft_type'] ?? ''));
    update_post_meta($post_id, 'prize', sanitize_text_field($_POST['prize'] ?? ''));
    update_post_meta($post_id, 'entry_fee', sanitize_text_field($_POST['entry_fee'] ?? ''));
    update_post_meta($post_id, 'promo_code', sanitize_text_field($_POST['promo_code'] ?? ''));
    update_post_meta($post_id, 'start_date', sanitize_text_field($_POST['start_date'] ?? ''));
    update_post_meta($post_id, 'end_date', sanitize_text_field($_POST['end_date'] ?? ''));
	update_post_meta($post_id, 'pool_id', sanitize_text_field($_POST['pool_id'] ?? ''));
	

    // Nested meta fields: meta[max_participants], etc.
    if (!empty($_POST['meta']) && is_array($_POST['meta'])) {
        foreach ($_POST['meta'] as $meta_key => $meta_value) {
            update_post_meta($post_id, sanitize_key($meta_key), sanitize_text_field($meta_value));
        }
    }

    /* -------------------------------------------------
       RANDOM TAXONOMY ICON (league_icon)
    --------------------------------------------------*/
    $terms = get_terms([
        'taxonomy'   => 'league_icon',
        'hide_empty' => false,
    ]);

    if (!empty($terms) && !is_wp_error($terms)) {

        $random_term = $terms[array_rand($terms)];

        wp_set_post_terms($post_id, [$random_term->term_id], 'league_icon');

        $icon = get_term_meta($random_term->term_id, '_icon', true);

        if ($icon) {
            update_post_meta($post_id, 'league_icon_id', $icon);
            update_post_meta($post_id, 'league_icon_url', wp_get_attachment_url($icon));
        }
    }

    /* -------------------------------------------------
       OPTIONAL: BANNER / FEATURED IMAGE
    --------------------------------------------------*/
    if (!empty($_FILES['league_image']['name'])) {

        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $img_id = media_handle_upload('league_image', $post_id);

        if (!is_wp_error($img_id)) {
            set_post_thumbnail($post_id, $img_id);
        }
    }

    /* -------------------------------------------------
       REDIRECT TO LEAGUE HOME PAGE
    --------------------------------------------------*/
    $redirect = "/league/{$league_key}/invite/?created";

    wp_send_json([
        'success'  => true,
        'message'  => 'League created successfully!',
        'redirect' => $redirect,
        'key'      => $league_key,
        'post_id'  => $post_id,
    ]);
}