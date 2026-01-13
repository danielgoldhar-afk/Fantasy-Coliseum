<?php

add_shortcode('join_league', 'fc_join_league_shortcode');

function fc_join_league_shortcode() {
    if (!session_id()) { session_start(); }

    ini_set("display_errors", 1);
    error_reporting(E_ALL);

    // GET league ID from rewrite rule
    $league_slug = get_query_var('league_id');
    if (!$league_slug) {
        return "<p>Invalid League.</p>";
    }

    // Get league post by slug
    $league = get_page_by_path($league_slug, OBJECT, 'league');
    if (!$league) {
        return "<p>League not found.</p>";
    }

    $league_id = $league->ID;

    global $wpdb;

    // =======================================
    // IF NOT LOGGED IN → SHOW LOGIN + STOP
    // =======================================
    if (!is_user_logged_in()) {
        $_SESSION['pending_league_id'] = $league_id;

        return wp_login_form([
            'echo' => false,
            'redirect' => home_url('/join-league/' . $league_slug . '/success')
        ]);
    }

    $user_id = get_current_user_id();

    $entries_table = $wpdb->prefix . "fantasy_entries";

    // =======================================
    // CHECK IF ALREADY JOINED THE LEAGUE
    // =======================================
    $already = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT entry_id FROM $entries_table 
             WHERE user_id = %d AND league_id = %d",
            $user_id, $league_id
        )
    );

    if ($already) {
         // REDIRECT AFTER EVERYTHING IS SAVED
		$redirect = site_url('/league/' . $league_slug);

		echo "<script>window.location.href='$redirect';</script>";
		exit;
    }

    // =======================================
    // SAVE SESSION FOR LATER PICK INSERTION
    // =======================================
    $_SESSION['pending_league_id'] = $league_id;


    // =======================================
    // LOAD STRIPE SAFELY (NO DOUBLE LOAD)
    // =======================================
    if (!class_exists('\Stripe\Stripe')) {
        require_once get_stylesheet_directory() . '/required/stripe/init.php';
    }

    \Stripe\Stripe::setApiKey("$stripeSecretKey");

    // =======================================
    // CREATE CHECKOUT SESSION
    // =======================================
    $checkout = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],

        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Join: ' . $league->post_title,
                ],
                'unit_amount' => (get_post_meta($league_id, 'entry_fee', true) ?? 20) * 100, // $50
            ],
            'quantity' => 1,
        ]],

        'mode' => 'payment',

        // Success redirect
        'success_url' => home_url('/join-league/' . $league_slug . '/success'),

        // Cancel
        'cancel_url' => home_url('/join-league/' . $league_slug . ''),

        // Meta for webhook or success page
        'metadata' => [
            'league_id' => $league_id,
            'user_id'   => $user_id,
			'league_slug' => $league_slug
        ],
    ]);

    // =======================================
    // AUTO-REDIRECT
    // =======================================
    echo '<script> window.location.href="'.$checkout->url.'"</script>';
    exit;
}