<?php


add_action('wp_ajax_fc_save_draft', 'fc_save_draft');
add_action('wp_ajax_nopriv_fc_save_draft', 'fc_save_draft');

function fc_save_draft() {
    if (!session_id()) {
        session_start();
    }

    $league_slug = sanitize_text_field($_POST['contest_id']);
    $players     = $_POST['players'];

    $_SESSION['fc_draft'] = [
        'league_id' => $league_slug,
        'players'   => $players
    ];

    // LOGIN CHECK
    if (is_user_logged_in()) {
        $redirect = site_url('/join-league/' . $league_slug);
    } else {
        $redirect = site_url('/sign-in/?redirect=' . site_url('/join-league/' . $league_slug));
    }

    wp_send_json(['redirect' => $redirect]);
    wp_die();
}
