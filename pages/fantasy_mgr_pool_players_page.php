<?php 
function fantasy_pool_players_page() {
    global $wpdb;
	
	  $box_id           = intval($_GET['box_id'] ?? 0);
    $pool_id          = intval($_GET['pool_id'] ?? 0);
	
	

	$pools_table      = $wpdb->prefix . 'fantasy_pools';
	
	$pools = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$pools_table} WHERE pool_id = %d",
        $pool_id
    ));
// 	print_r($pools);
	
	$pool = $pools[0];
	$league = $pool->league;
	
	
	
    $selections_table = $wpdb->prefix . 'fantasy_pool_player_selections';
    $players_table    = $wpdb->prefix . 'fantasy_players';
	
	
	
    $stats_table      = $wpdb->prefix . 'fantasy_player_stats_'.$league;
	
  
	
	
	

    if (!$box_id) {
        echo '<div class="wrap"><h2>No Box Selected</h2><p>Please go to <strong>Fantasy Manager → Boxes</strong> and click “Manage Players”.</p></div>';
        return;
    }

    // Pagination settings
    $limit  = 50;
    $page   = max(1, intval($_GET['p'] ?? 1));
    $offset = ($page - 1) * $limit;

    // Fetch already selected players for this box
    $saved_players = $wpdb->get_col($wpdb->prepare(
        "SELECT player_api_id FROM {$selections_table} WHERE box_id = %d",
        $box_id
    ));
    $saved_players = array_map('intval', $saved_players ?: []);

    $where  = '';
	$params = [];

	// Optional search
	if ( ! empty($_GET['search']) ) {
		$where     = 'WHERE p.player_name LIKE %s';
		$params[] = '%' . $wpdb->esc_like( sanitize_text_field($_GET['search']) ) . '%';
	}

	// Pagination params
	$params[] = (int) $limit;
	$params[] = (int) $offset;

	$sql = "
		SELECT
			p.player_id,
			p.first_name,
			p.last_name,
			p.team,
			p.position,
			p.image_url,
			p.player_api_id,
			p.player_name,
			COALESCE(s.fantasy_score, 0) AS fantasy_score
		FROM {$players_table} p
		LEFT JOIN {$stats_table} s
			ON s.player_id = p.player_api_id
		{$where}
		ORDER BY fantasy_score DESC
		LIMIT %d OFFSET %d
	";

	$players = $wpdb->get_results(
		$wpdb->prepare($sql, $params)
	);
	
	?>
	

		<input type="text" name="search" value="<?php echo $_GET['search'] ?? ''; ?>" id="searchbox">
		<button id="searchbutton">
			Search
		</button>

	<?php


    if (empty($players)) {
        echo "<div class='wrap'><h2>Players</h2><p>No players found for this box.</p></div>";
        return;
    }

    // Nonce for AJAX
    $nonce = wp_create_nonce('fm_players_nonce');
    ?>
    <div class="wrap">
        <h1>Players (Box #<?php echo $box_id; ?>)</h1>
		
        <a href="<?php echo admin_url('admin.php?page=fantasy_boxes&pool_id='.$pool_id); ?>" class="button button-secondary">Back to Boxes</a>
		
        <p>Click a card to toggle selection. Selections are saved immediately.</p>
		
		
		
        <style>
        .player-grid { display:grid; grid-template-columns: repeat(auto-fill,minmax(150px,1fr)); gap:15px; margin-top:16px; }
        .player-card { border:2px solid #ddd; padding:10px; text-align:center; border-radius:8px; cursor:pointer; transition:all .15s; background:#fff; }
        .player-card.selected { border-color:#1784c8; background:#e9f6ff; box-shadow: 0 2px 6px rgba(0,0,0,0.03); }
        .player-card img { width:80px; height:80px; object-fit:cover; border-radius:50%; display:block; margin:0 auto 8px; }
        .player-card h4 { margin:6px 0 2px; font-size:14px; }
        .player-card p { margin:0; font-size:12px; color:#666; }
        .fm-pagination { margin-top:18px; }
        .fm-pagination .button { margin-right:6px; }
        </style>

        <div id="fm-player-notice" style="display:none;padding:8px;margin-bottom:12px;border-radius:4px;"></div>

        <div class="player-grid" id="fmPlayerGrid">
            <?php foreach ($players as $p):
                $api_id = intval($p->player_api_id);
                $first  = $p->first_name;
                $last   = $p->last_name;
                $name   = trim($first . ' ' . $last);
                $pos    = $p->position ?: '-';
                $team   = $p->team ?: '-';
                $img    = $p->image_url ?: 'https://via.placeholder.com/80';
                $is_selected = in_array($api_id, $saved_players) ? 'selected' : '';
            ?>
                <div class="player-card <?php echo $is_selected; ?>"
                     data-id="<?php echo esc_attr($api_id); ?>"
                     data-name="<?php echo esc_attr($name); ?>"
                     data-team="<?php echo esc_attr($team); ?>"
                     data-position="<?php echo esc_attr($pos); ?>"
                     data-image="<?php echo esc_attr($img); ?>"
                     data-score="<?php echo floatval($p->fantasy_score); ?>">
                    <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($name); ?>">
                    <h4><?php echo esc_html($name); ?></h4>
                    <p><?php echo esc_html($team . ' — ' . $pos); ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php
        $count_params = [];

		// same search logic
		if ( ! empty($_GET['search']) ) {
			$where          = 'WHERE p.player_name LIKE %s';
			$count_params[] = '%' . $wpdb->esc_like( sanitize_text_field($_GET['search']) ) . '%';
		} else {
			$where = '';
		}

		$sql_count = "
			SELECT COUNT(*)
			FROM {$players_table} p
			{$where}
		";

		$totalPlayers = (int) $wpdb->get_var(
			$wpdb->prepare($sql_count, $count_params)
		);
        $totalPages   = max(1, ceil($totalPlayers / $limit));
        if ($totalPages > 1): ?>
            <div class="fm-pagination">
                <?php if ($page > 1): ?>
                    <a class="button" href="<?php echo esc_url(add_query_arg(['page'=>'fantasy_pool_players','box_id'=>$box_id,'pool_id' => $pool_id, 'p'=>$page-1], admin_url('admin.php'))); ?>">« Prev</a>
                <?php endif; ?>

                <?php
                $start = max(1, $page - 2);
                $end   = min($totalPages, $page + 2);
                for ($i = $start; $i <= $end; $i++) {
                    $btn_class = $i === $page ? 'button-primary' : 'button';
                    echo '<a class="'.esc_attr($btn_class).'" href="'.esc_url(add_query_arg(['page'=>'fantasy_pool_players','box_id'=>$box_id, 'pool_id' => $pool_id, 'p'=>$i], admin_url('admin.php'))).'">'.$i.'</a> ';
                }
                ?>
                <?php if ($end < $totalPages): ?>
                    <a class="button" href="<?php echo esc_url(add_query_arg(['page'=>'fantasy_pool_players','box_id'=>$box_id,'pool_id' => $pool_id, 'p'=>$totalPages], admin_url('admin.php'))); ?>"><?php echo $totalPages; ?></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <script>
        (function(){
            const ajaxUrl = "<?php echo admin_url('admin-ajax.php'); ?>";
            const nonce = "<?php echo $nonce; ?>";
            const boxId = "<?php echo intval($box_id); ?>";
            const noticeEl = document.getElementById('fm-player-notice');
			
			
			document.getElementById('searchbutton').addEventListener('click', function(){
				const val = document.getElementById('searchbox').value;
			
				window.location.href = 'https://fantasycoliseum.com/wp-admin/admin.php?page=fantasy_pool_players&box_id=<?php echo $box_id; ?>&pool_id=<?php echo $pool_id; ?>&p=<?php echo $_GET['p'] ?? ''; ?>&search='+val;
				
				
					
			});

            function showNotice(text, type='success') {
                noticeEl.style.display = 'block';
                noticeEl.style.background = type === 'error' ? '#ffe6e6' : '#e6ffef';
                noticeEl.style.border = type === 'error' ? '1px solid #ff7b7b' : '1px solid #8ef0b6';
                noticeEl.innerText = text;
                setTimeout(()=> noticeEl.style.display = 'none', 2500);
            }

            document.querySelectorAll('.player-card').forEach(card => {
                card.addEventListener('click', function(){
                    card.classList.toggle('selected');
                    const selected = card.classList.contains('selected') ? 1 : 0;

                    const payload = {
                        action: 'save_box_player_status',
                        nonce: nonce,
                        box_id: boxId,
                        player_api_id: card.dataset.id,
                        player_name: card.dataset.name,
                        team: card.dataset.team,
                        position: card.dataset.position,
                        image_url: card.dataset.image,
                        selected: selected
                    };

                    jQuery.post(ajaxUrl, payload, function(resp){
                        if (resp && resp.success) {
                            showNotice(resp.data.message || 'Saved', 'success');
                        } else {
                            showNotice((resp && resp.data && resp.data) || 'Error saving', 'error');
                        }
                    }).fail(function(){
                        showNotice('AJAX error', 'error');
                    });
                });
            });
        })();
        </script>
    </div>
    <?php
}

