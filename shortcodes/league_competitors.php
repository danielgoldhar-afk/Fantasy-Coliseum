<?php


add_shortcode('league_competitors', function($atts) {
    global $wpdb, $post;

	
	

 
	
	
    $league_id = $post->post_name ?? 0;
   

    $entries_table = $wpdb->prefix . "fantasy_entries";

    // Fetch joined users
    $entries = $wpdb->get_results(
        $wpdb->prepare("
            SELECT DISTINCT(user_id) as user_id 
            FROM $entries_table 
            WHERE league_id = %s
        ", $league_id)
    );

    if (!$entries) {
        return "<p style='color:#fff; text-align: center'>No competitors have joined this pool yet.</p>";
    }

    $output = '<div class="fc-competitors-wrapper">';

    foreach ($entries as $entry) {
        $uid   = $entry->user_id;
        $user  = get_userdata($uid);

        if (!$user) continue;

        $avatar = get_avatar_url($uid);
        $username = '@' . $user->user_login;

        $output .= '
        <div class="fc-competitor-box">
            <img class="fc-avatar" src="' . esc_url($avatar) . '"/>
            <div class="fc-info">
                <div class="fc-username">' . esc_html($username) . '</div>
            </div>
            <span class="fc-status-dot"></span>
        </div>';
    }

    $total = count($entries);
    $extra = $total > 10 ? $total - 10 : 0;

    if ($extra > 0) {
        $output .= '
        <a href="#" class="fc-more-btn">
            View ' . $extra . ' more players in this pool
        </a>';
    }

    $output .= '</div>';

    // Include CSS
    $output .= '
    <style>
        .fc-competitors-wrapper {
            max-width: 820px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .fc-competitor-box {
            display: flex;
            align-items: center;
            background: rgba(255,255,255,0.08);
            padding: 15px 20px;
            border-radius: 16px;
            position: relative;
        }

        .fc-avatar {
            width: 55px;
            height: 55px;
            border-radius: 50% !important;
            object-fit: cover !important;
        }

        .fc-info {
            margin-left: 15px;
        }

        .fc-username {
            font-size: 18px;
            font-weight: 600;
            color: #fff;
        }

        .fc-status-dot {
            width: 14px;
            height: 14px;
            background: #C5FF47;
            border-radius: 50%;
            position: absolute;
            right: 18px;
        }

        .fc-more-btn {
            grid-column: span 2;
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 15px 20px;
            background: linear-gradient(45deg, #ff8a00, #ffb347);
            color: #000;
            font-size: 18px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
        }
    </style>';

    return $output;
});