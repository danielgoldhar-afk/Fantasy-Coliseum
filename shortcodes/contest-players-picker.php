<?php

add_shortcode('contest_player_picker', 'fc_player_picker_shortcode');

function fc_player_picker_shortcode(){
    global $wpdb;
    $contest_slug = get_query_var('league_id');
    
    $contest = get_page_by_path($contest_slug, OBJECT, 'league'); // your CPT = contest
    
    if(!$contest){
        echo "<h2>Contest not found.</h2>";
		return;
//         exit;
    }
	 $pools_table = $wpdb->prefix . 'fantasy_pools';

    

    // GET ALL POOLS
    $pool = $wpdb->get_results("SELECT * FROM $pools_table WHERE pool_id = $pool_id ORDER BY pool_id DESC");
    
    echo "<h1 style='color: #fff;  '>" . esc_html($contest->post_title) . "</h1>";
	echo "<p style='color: #fff; '>Select the players to continue joining contest</p>";
//     echo "<p>Contest ID: " . $contest->ID . "</p>";

//     echo "<pre>";
// 	print_r(get_post_meta($contest->ID) ) ;
// 	echo "</pre>";
 
	
	$pool_id = get_post_meta($contest->ID, 'pool_id', true);
    if(!$pool_id){
        return "<p>No pool selected.</p>";
    }

    $boxes_table     = $wpdb->prefix . 'fantasy_boxes';
    $players_table   = $wpdb->prefix . 'fantasy_pool_player_selections';

    // GET ALL BOXES FOR THIS POOL
    $boxes = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $boxes_table WHERE pool_id = %d", $pool_id)
    );

    if(!$boxes){
        return "<p>No boxes found for this pool.</p>";
    }
	
	

    ob_start();
    ?>

    <div class="fc-player-picker">

        <?php foreach($boxes as $box): ?>
            <div class="fc-box-card">
                <h3 style="" ><?php echo esc_html($box->box_name); ?></h3>

                <div class="fc-box-players">
                    <?php 
                    // GET PLAYERS INSIDE THIS BOX
                    $players = $wpdb->get_results(
                        $wpdb->prepare("SELECT * FROM $players_table WHERE box_id = %d", $box->box_id)
                    );

                    if($players){
                        foreach($players as $p):
                    ?>
                       <label class="fc-player-item">
    
						<input type="checkbox" 
							   class="fc-player-checkbox" 
							   data-player-id="<?php echo $p->player_api_id; ?>"
							   data-box-id="<?php echo $box->box_id; ?>"
							   name="selected_players[<?=$box->box_id;?>]" 
							   value="<?php echo $p->player_api_id; ?>">

						<div class="fc-player-thumb">
<!-- 							<img src="<?php echo esc_url($p->image_url ?? 'https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg'); ?>" alt="<?php echo esc_attr($p->player_name); ?>"> -->
							
						</div>

						<div class="fc-player-name">
							<?php echo esc_html($p->player_name); ?>
						</div>

					</label>

                    <?php 
                        endforeach;
                    } else {
                        echo "<p>No players found in this box.</p>";
                    }
                    ?>
                </div>
            </div>
        <?php endforeach; ?>

      <button id="fc-submit-team" class="fc-submit-btn">Submit Team</button>

    </div>

    <?php
    // ADD JS + CSS
    ?>
    <style>
    h1 {
        font-family: "Aeonik Trial", sans-serif !important;
    }
		.fc-player-picker * {
			font-family: "Aeonik Trial", Sans-serif;
		}
      .fc-player-picker {
			display: grid;
			gap: 20px;
			justify-items: start;
			grid-template-columns: 1fr 1fr;
		}
		
		@media (max-width: 768px){
			 .fc-player-picker {
			
				grid-template-columns: 1fr;
			}
		}
       .fc-box-card {
		   padding: 20px;
		   border-radius: 10px;
		   /* border: 1px solid #ddd; */
		   background-color: #FFFFFF14;
		}
		.fc-box-card h3 {
			color: #fff;
			margin-top: 0;
		}
       .fc-box-players {
            margin-top: 15px;
            display: flex;
            align-items: stretch;
            justify-content: flex-start;
            flex-wrap: wrap;
            gap: 10px;
           
        }
        .fc-submit-btn {
            font-family: "Aeonik Trial", Sans-serif;
            font-size: 22px;
            font-weight: 600;
            color: #fff;
            padding: 22px 56px;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            background: linear-gradient(180deg, #FFA13C 0%, #FF8C1A 100%);
            box-shadow: 0px 10px 30px rgba(0,0,0,0.35),
                        inset 0px 3px 6px rgba(255,255,255,0.25);
            transition: all 0.25s ease;
            margin: 0 auto;
        }
        .fc-submit-btn:hover {
            background: #005f8d;
        }
		.fc-player-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            /* border: 1px solid #ddd; */
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
            gap: 8px;
            transition: 0.2s;
            text-align: center;
            width: 120px;
            background: rgba(255, 255, 255, 0.04);
            border: 0.3px solid rgba(255, 255, 255, 0.15);
            /* margin: 0 5px; */
            box-shadow: 0px 6px 19.3px 0px rgba(0,0,0,0.24);
            /* aspect-ratio: 1 / 1; */
            justify-content: center;
            white-space: nowrap;
            min-width: fit-content;
        }
		.fc-player-item:has(input:checked){
			background: #ffa2053b !important;
			border: 0.3px solid rgba(255, 255, 255, 0.15);
			
		}
		.fc-player-item:hover {
			border-color: #0073aa;
		}

		.fc-player-item input {
			display: none; /* Hide checkbox */
		}

		.fc-player-item img {
			width: 90px;
			height: 90px;
			object-fit: cover;
			border-radius: 4px;
		}

		.fc-player-name {
			font-size: 15px;
			font-weight: 500;
			color: #fff;
		}

        .fc-player-thumb {
            display: none;
        }
        @media (max-width: 768px){
            .fc-player-item {
                
                width: 100% !important;
                
                 aspect-ratio: unset !important; 
                
            }
            .fc-box-card h3 {
                color: #fff;
                margin-top: 0;
                text-align: center;
            }
        }
        
        

    </style>

    <script>
 
	jQuery(document).on("click", "#fc-submit-team", function () {

		let players = [];

		jQuery(".fc-player-checkbox:checked").each(function(){
			players.push({
				box_id: jQuery(this).data("box-id"),
				player_id: jQuery(this).data("player-id")
			});
		});

		jQuery.post('/wp-admin/admin-ajax.php', {
			action: "fc_save_draft",
			players: players,
			contest_id: '<?=$contest_slug?>'
		}, function(response){
			window.location.href = response.redirect; 
		});
	});

    </script>

    <?php
    return ob_get_clean();
}