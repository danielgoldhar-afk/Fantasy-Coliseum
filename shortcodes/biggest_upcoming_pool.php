<?php


function fc_biggest_upcoming_pool_shortcode($atts) {
    $atts = shortcode_atts([
        'league' => '', // optional: NBA, NHL
    ], $atts, 'fc_biggest_upcoming_pool');

    ob_start();
	
    $today = date('Y-m-d');
	
     // Build meta query
    $meta_query = [
        'relation' => 'AND',
        [
            'key'     => '_fc_start_date',
            'value'   => $today,
            'compare' => '>=',
            'type'    => 'DATE',
        ],
        [
            'key'     => '_fc_prize_total',
            'compare' => 'EXISTS',
        ],
    ];

    if (!empty($atts['league'])) {
        $meta_query[] = [
            'key'     => '_fc_league',
            'value'   => $atts['league'],
            'compare' => '=',
        ];
    }

    // Query upcoming contests with biggest pool prize first, then nearest start date
    $args = [
        'post_type'      => 'product',
        'posts_per_page' => 1,
        'meta_query'     => $meta_query,
        'orderby'        => [
            '_fc_prize_total' => 'DESC',  // highest prize first
            '_fc_start_date'  => 'ASC',   // earliest upcoming date next
        ],
    ];


    $pools = new WP_Query($args);

    if ($pools->have_posts()) :
        while ($pools->have_posts()) : $pools->the_post();
            $pool_id     = get_the_ID();
            $prize_total = get_post_meta($pool_id, '_fc_prize_total', true);
            $start_date  = get_post_meta($pool_id, '_fc_start_date', true);
            $max_spots   = get_post_meta($pool_id, '_fc_max_participants', true);
            $contest_id  = get_post_meta($pool_id, '_fc_contest_id', true);
            $spots_filled = 1;

            // Contest details
            $contest_title = get_the_title($contest_id);
            $entry_fee     = get_post_meta($contest_id, '_fc_entry_fee', true);
            $contest_link  = get_permalink($contest_id);
            ?>

            <div class="fc-biggest-pool">
                <h3 class="fc-title"><?php echo esc_html($title); ?></h3>
                <div class="fc-meta">
                    <p><strong>Prize Pool:</strong> $<?php echo number_format($prize_total); ?></p>
                    <p><strong>Entry Fee:</strong> $<?php echo number_format($entry_fee); ?></p>
                    <p><strong>Starts:</strong> <?php echo date('F j, Y', strtotime($start_date)); ?></p>
                    <p><strong>Spots:</strong> <?php echo esc_html($spots_filled . '/' . $max_spots); ?></p>
                </div>
                <a href="<?php echo esc_url(get_permalink($contest_id)); ?>" class="fc-join-btn">Join Now</a>
            </div>

            <style>
                .fc-biggest-pool {
                    border: 1px solid #e1e1e1;
                    border-radius: 12px;
                    padding: 20px;
                    text-align: center;
                    background: #fff;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
                    max-width: 420px;
                    margin: 30px auto;
                }
                .fc-biggest-pool .fc-title {
                    margin-bottom: 10px;
                    font-size: 1.5em;
                    color: #223b69;
                }
                .fc-biggest-pool .fc-meta p {
                    margin: 4px 0;
                }
                .fc-join-btn {
                    display: inline-block;
                    background: #1c4ed8;
                    color: #fff;
                    padding: 10px 20px;
                    border-radius: 6px;
                    text-decoration: none;
                    margin-top: 12px;
                    transition: background 0.3s;
                }
                .fc-join-btn:hover {
                    background: #163bb6;
                }
            </style>
  		<?php
        endwhile;
        wp_reset_postdata();
    else:
        echo '<p>No upcoming pools found.</p>';
    endif;

    return ob_get_clean();
}
add_shortcode('fc_biggest_upcoming_pool', 'fc_biggest_upcoming_pool_shortcode');