<?php

// === Backend Login Handler ===
function handle_custom_login_form() {
    $username = sanitize_text_field($_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    if (empty($username) || empty($password)) {
        wp_send_json_error(['message' => 'Please fill in all fields.']);
    }

    $creds = [
        'user_login' => $username,
        'user_password' => $password,
        'remember' => $remember,
    ];

    $user = wp_signon($creds, false);

    if (is_wp_error($user)) {
        wp_send_json_error(['message' => 'Invalid username or password.']);
    }

    wp_send_json_success(['message' => 'Login successful! Redirecting...']);
}
add_action('wp_ajax_custom_login_form', 'handle_custom_login_form');
add_action('wp_ajax_nopriv_custom_login_form', 'handle_custom_login_form');