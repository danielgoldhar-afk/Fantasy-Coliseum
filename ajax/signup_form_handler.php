<?php


// === AJAX Handler ===
function handle_custom_signup_form() {
    // Security & sanitization
    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        wp_send_json_error(['message' => 'All fields are required.']);
    }

    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Invalid email address.']);
    }

    if ($password !== $confirm_password) {
        wp_send_json_error(['message' => 'Passwords do not match.']);
    }

    if (username_exists($username)) {
        wp_send_json_error(['message' => 'Username already exists.']);
    }

    if (email_exists($email)) {
        wp_send_json_error(['message' => 'Email already registered.']);
    }

    // Create the user
    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        wp_send_json_error(['message' => 'Error creating account. Please try again.']);
    }

    // Optional: Auto-login
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);

    wp_send_json_success(['message' => 'Account created successfully! Redirecting...']);
}
add_action('wp_ajax_custom_signup_form', 'handle_custom_signup_form');
add_action('wp_ajax_nopriv_custom_signup_form', 'handle_custom_signup_form');