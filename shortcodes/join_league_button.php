<?php

add_shortcode('join_league_button', 'fc_join_league_button_shortcode');
function fc_join_league_button_shortcode() {
    global $post, $wpdb;

    if (!$post) return "";

    $league = $post;
    $league_id = $league->post_name;

    // Get pool_id from league meta
    $pool_id = get_post_meta($league_id, 'pool_id', true);

    // Total spots
    $max_spots = (int) get_post_meta($pool_id, '_max_participants', true);

    // Count filled entries
    $entries_table = $wpdb->prefix . "fantasy_entries";
    $filled = (int) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM $entries_table WHERE league_id = %s",
            $league_id
        )
    );

    $user_id = get_current_user_id();

    // Check if user already joined
    $already_joined = false;
    if ($user_id) {
        $already_joined = (bool) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT entry_id FROM $entries_table WHERE league_id=%s AND user_id=%d",
                $league_id, $user_id
            )
        );
    }

    $is_full = $max_spots > 0 && $filled >= $max_spots;

    // ===== Button HTML =====
    ob_start();
    ?>

    

        <?php if ($is_full): ?>

            <!-- Pool full -->
            <button disabled 
                style="
                    background:#6c645e;
                    color:#cfcac7;
                    padding:18px 50px;
                    border-radius:40px;
                    font-size:28px;
                    font-weight:600;
                    border:none;
                    opacity:0.6;
                    cursor:not-allowed;
                ">
                Pool filled!
            </button>

        <?php elseif ($already_joined): ?>

            <!-- Already joined -->
            <button disabled
                style="
                    background:#6c645e;
                    color:#cfcac7;
                    padding:18px 50px;
                    border-radius:40px;
                    font-size:28px;
                    font-weight:600;
                    border:none;
                    opacity:0.6;
                    cursor:not-allowed;
                ">
                Already Joined!
            </button>

        <?php else: ?>

            <!-- Join button -->
            <a href="<?php echo home_url('/contest/' . $league->post_name . '/players-picker/'); ?>"
               style="
                    background: linear-gradient(90deg, #ff9f32, #ff7700);
                    color:#fff;
                    padding:18px 50px;
                    border-radius:40px;
                    font-size:30px;
                    font-weight:600;
                    text-decoration:none;
                    display:inline-block;
                ">
                Join this Pool
            </a>

        <?php endif; ?>

    

    <?php
    return ob_get_clean();
}
add_shortcode('join_league_button', 'fc_join_league_button_shortcode');