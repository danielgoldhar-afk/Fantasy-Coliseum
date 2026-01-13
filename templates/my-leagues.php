<?php
/**
 * Template Name: My Leagues
 * Template Post Type: page
 */

?>
<link rel="stylesheet"
      href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/dashboard.css">



<div class="fc-dashboard-page">
  <div class="fc-dashboard-page-overlay"></div>
  <div class="fc-dashboard-page-inner">
    <div class="fc-dashboard-page-inner-page">
      
         <!-- ✅ SIDEBAR START -->
        <div id="fc-dashboard-sidebar" class="fc-dashboard-sidenav">
          <?php include get_template_directory() . '/template-parts/league-sidebar.php'; ?>
        </div>
        <!-- ✅ SIDEBAR END -->
		<style>
			.my-leagues-wrapper {
				background-color: #FFFFFF14;
				border: 0.3px solid rgba(255,255,255,0.15);
				padding: 10px;
				border-radius: 20px;
				text-decoration: none;
				display: grid;
				grid-template-columns: 1fr 1fr;
				margin-top: 30px;
			}
			.league-item {
				background: #00000047;
				padding: 15px;
				border-radius: 20px;
				display: flex;
				align-items: center;
				gap: 20px;
				align-content: space-between;
			}
			.league-item h3{
				color: #fff	;
				margin-top: 0
			}
		</style>
        <!-- ✅ CONTENT -->
    	<div id="fc-dashboard-contentarea" class="fc-dashboard-content">
    		<h1 class="fc-page-title">
    		    My Contests
    		</h1>
    		 <?php 
			 	$args = [
					'post_type'      => 'league',
					'posts_per_page' => -1,
					'post_status'    => 'publish'
				];

				$query = new WP_Query($args);

				ob_start();

				if ($query->have_posts()) {
					echo "<div class='my-leagues-wrapper'>";

					while ($query->have_posts()) {
						$query->the_post();
						
							// ✅ Get league_icon term
						$terms = wp_get_post_terms($post->ID, 'league_icon');

						$icon_url = false;

	// 					echo "<pre>";

	// 					print_r($terms);

						if (!empty($terms) && !is_wp_error($terms)) {
							$term = $terms[0];

							// ✅ Get icon from term meta
							$icon_id = get_term_meta($term->term_id, 'avatar', true);

							if ($icon_id) {
								$icon_url = wp_get_attachment_url($icon_id);
							}
						}

						// ✅ Fallback icon if empty
						if (!$icon_url) {
							$icon_url = 'https://fantasycoliseum.com/wp-content/uploads/2025/09/scores.png';
						}

						echo "<div class='league-item' >
								<img src='$icon_url' style='width:60px;height:60px;object-fit:cover;border-radius:8px;margin-bottom:10px;' />
								<div>
								<h3 style='font-size:18px;margin-bottom:10px;'>" . get_the_title() . "</h3>
								<a style='border-radius: 10px; background: #FF9500; color: #fff; padding: 7px 15px; text-decoration: none; display:inline-block;' 
									href='" . get_permalink() . "'>View details</a>
									</div>
							  </div>";
					}

					echo "</div>";

				} else {
					echo "<p>No leagues found.</p>";
				}

				wp_reset_postdata();
			?>
    		
        </div>
        <!-- ✅ CONTENT -->
		
		
    </div>
  </div>
</div>

<!-- ✅ JQUERY -->
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/dashboard.js"></script>