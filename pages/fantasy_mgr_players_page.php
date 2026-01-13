<?php 

function fantasy_players_page() {
    global $wpdb;

    $players_table = $wpdb->prefix . 'fantasy_players';
    
	$league =  isset($_GET['league']) ? $_GET['league'] : 'nfl';
	
	$stats_table   = $wpdb->prefix . 'fantasy_player_stats_'.$league;
	
		

    /* -----------------------
     FILTER INPUTS
    ----------------------- */
    $team_filter     = isset($_GET['team']) ? sanitize_text_field($_GET['team']) : '';
    $position_filter = isset($_GET['position']) ? sanitize_text_field($_GET['position']) : '';
    $search_query    = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

    /* -----------------------
     PAGINATION LOGIC
    ----------------------- */
    $per_page  = 20;
    $page      = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset    = ($page - 1) * $per_page;

    /* -----------------------
     WHERE CONDITIONS
    ----------------------- */
    $where = "WHERE p.league = '".$league."'";

    if ($team_filter !== '') {
        $where .= $wpdb->prepare(" AND p.team = %s", $team_filter);
    }

    if ($position_filter !== '') {
        $where .= $wpdb->prepare(" AND p.position = %s", $position_filter);
    }

    if ($search_query !== '') {
        $search_like = "%" . $wpdb->esc_like($search_query) . "%";
        $where .= $wpdb->prepare("
            AND (p.first_name LIKE %s OR p.last_name LIKE %s OR p.player_name LIKE %s)",
            $search_like, $search_like, $search_like
        );
    }

    /* -----------------------
     TOTAL COUNT
    ----------------------- */
    $total = $wpdb->get_var("
        SELECT COUNT(*) 
        FROM $players_table p
        LEFT JOIN $stats_table s ON p.player_id = s.player_id
        $where
    ");

    $total_pages = ceil($total / $per_page);

    /* -----------------------
     FETCH DATA
    ----------------------- */
    $players = $wpdb->get_results("
        SELECT 
            p.player_id,
			p.player_api_id,
            p.first_name,
            p.last_name,
            p.team,
            p.position,
            p.image_url,
            s.* 
        FROM $players_table p
        LEFT JOIN $stats_table s ON p.player_api_id = s.player_id
        $where
        ORDER BY s.fantasy_score DESC
        LIMIT $offset, $per_page
    ");

    ob_start();
    ?>

    <div class="wrap">
        <h1>Fantasy <?php echo strtoupper($league); ?> Players</h1>

        <!-- FILTER BAR -->
        <form method="get" style="margin-bottom:20px;">
            <input type="hidden" name="page" value="fantasy_players">

            <input type="text" name="search" placeholder="Search player..." 
                   value="<?php echo esc_attr($search_query); ?>"
                   style="padding:6px;width:200px;margin-right:10px;">

            <select name="team" style="padding:6px;margin-right:10px;">
                <option value="">All Teams</option>
                <?php
                $teams = $wpdb->get_col("SELECT DISTINCT team FROM $players_table WHERE team IS NOT NULL ORDER BY team ASC");
                foreach ($teams as $team) {
                    echo "<option value='" . esc_attr($team) . "' " . selected($team_filter, $team, false) . ">$team</option>";
                }
                ?>
            </select>

            <select name="position" style="padding:6px;margin-right:10px;">
                <option value="">All Positions</option>
                <?php
                $positions = $wpdb->get_col("SELECT DISTINCT position FROM $players_table WHERE position IS NOT NULL ORDER BY position ASC");
                foreach ($positions as $pos) {
                    echo "<option value='" . esc_attr($pos) . "' " . selected($position_filter, $pos, false) . ">$pos</option>";
                }
                ?>
            </select>

            <button type="submit" class="button button-primary">Filter</button>
        </form>

        <table class="wp-list-table widefat fixed striped">
            <thead>
            <tr>
               
				<th>ID</th>
                <th>Avatar</th>
				<th>Player</th>
                <th>Team</th>
                <th>Position</th>
                <th>GP</th>
                <th>G</th>
                <th>A</th>
                <th>PTS</th>
                <th>Shots</th>
                <th>Hits</th>
                <th>Faceoff %</th>
                <th>PIM</th>
                <th>Fantasy Score</th>
				<th>Rank</th>
                <th>View</th>
            </tr>
            </thead>

            <tbody>
            <?php
            if ($players) {
                foreach ($players as $p) { ?>
                    <tr>
                       
						<td><?php echo esc_html($p->player_api_id ?: '-'); ?></td>
						<td>
							<?php if ($p->image_url): ?>
                                <br><img src="<?php echo esc_url($p->image_url); ?>" style="width:60px;">
                            <?php endif; ?>
						</td>
                        <td>
                            <strong><?php echo esc_html($p->first_name . " " . $p->last_name); ?></strong>
                           
                        </td>

                        <td><?php echo esc_html($p->team); ?></td>
                        <td><?php echo esc_html($p->position); ?></td>
                        <td><?php echo esc_html($p->games_played); ?></td>
                        <td><?php echo esc_html($p->goals); ?></td>
                        <td><?php echo esc_html($p->assists); ?></td>
                        <td><?php echo esc_html($p->points); ?></td>
                        <td><?php echo esc_html($p->shots); ?></td>
                        <td><?php echo esc_html($p->hits); ?></td>
                        <td><?php echo esc_html($p->faceoff_percent); ?></td>
                        <td><?php echo esc_html($p->penalty_minutes); ?></td>
						
                        <td><strong><?php echo esc_html($p->fantasy_score); ?></strong></td>
						 <td><?php echo esc_html($p->rank_global ?: '-'); ?></td>
						
                        <td>
                            <button class="button view-player" 
                                    data-player='<?php echo json_encode($p, JSON_HEX_APOS); ?>'>
                                View
                            </button>
                        </td>
                    </tr>
                <?php }
            } else {
                echo "<tr><td colspan='14'>No players found.</td></tr>";
            }
            ?>
            </tbody>
        </table>

        <!-- PAGINATION -->
        <div class="tablenav" style="margin-top:20px;">
            <div class="tablenav-pages">
                <?php
                echo paginate_links([
                    'base'    => add_query_arg('paged', '%#%'),
                    'format'  => 'button',
                    'prev_text' => '&laquo;',
                    'next_text' => '&raquo;',
                    'total'   => $total_pages,
                    'current' => $page
                ]);
                ?>
            </div>
        </div>
    </div>

    <!-- POPUP MODAL -->
    <div id="playerModal" style="
        display:none; position:fixed; top:0; left:0; right:0; bottom:0;
        background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center;">
        
        <div style="background:#fff;padding:20px;border-radius:10px;max-width:600px;width:90%;">
            <h2 id="modalName"></h2>
            <img id="modalImg" src="" style="width:80px;margin-bottom:10px;">
            <p><strong>Team:</strong> <span id="modalTeam"></span></p>
            <p><strong>Position:</strong> <span id="modalPosition"></span></p>
            <hr>
            <p><strong>Games Played:</strong> <span id="modalGP"></span></p>
            <p><strong>Goals:</strong> <span id="modalG"></span></p>
            <p><strong>Assists:</strong> <span id="modalA"></span></p>
            <p><strong>Points:</strong> <span id="modalPTS"></span></p>
            <p><strong>Shots:</strong> <span id="modalShots"></span></p>
            <p><strong>Hits:</strong> <span id="modalHits"></span></p>
            <p><strong>Faceoff %:</strong> <span id="modalFO"></span></p>
            <p><strong>PIM:</strong> <span id="modalPIM"></span></p>
            <p><strong>Fantasy Score:</strong> <span id="modalFS"></span></p>

            <button onclick="document.getElementById('playerModal').style.display='none'" 
                    class="button button-primary">Close</button>
        </div>
    </div>

    <script>
        document.querySelectorAll('.view-player').forEach(btn => {
            btn.addEventListener('click', function() {
                const p = JSON.parse(this.dataset.player);

                document.getElementById('modalName').textContent = p.first_name + ' ' + p.last_name;
                document.getElementById('modalTeam').textContent = p.team;
                document.getElementById('modalPosition').textContent = p.position;

                document.getElementById('modalImg').src = p.image_url || '';
                document.getElementById('modalGP').textContent = p.games_played;
                document.getElementById('modalG').textContent = p.goals;
                document.getElementById('modalA').textContent = p.assists;
                document.getElementById('modalPTS').textContent = p.points;
                document.getElementById('modalShots').textContent = p.shots;
                document.getElementById('modalHits').textContent = p.hits;
                document.getElementById('modalFO').textContent = p.faceoff_percent;
                document.getElementById('modalPIM').textContent = p.penalty_minutes;
                document.getElementById('modalFS').textContent = p.fantasy_score;

                document.getElementById('playerModal').style.display = 'flex';
            });
        });
    </script>

    <?php

    echo ob_get_clean();
}
