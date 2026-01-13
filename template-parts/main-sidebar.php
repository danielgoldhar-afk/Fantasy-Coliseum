
<style>
    a.site-logo{
            display: flex;
    align-content: center;
    justify-content: center;
    height: 100px;
    gap: 0px;
    }
     a.site-logo img{
        width:100px;
    }
</style>
<?php
$custom_logo_id = get_theme_mod( 'custom_logo' );

if ( $custom_logo_id ) {
    $logo = wp_get_attachment_image_src( $custom_logo_id, 'full' );
    ?>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo">
        <img 
            src="<?php echo esc_url( $logo[0] ); ?>" 
            alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
        >
    </a>
    <?php
}
?>


<!-- FIXED TOP BUTTONS -->
	  <div class="fc-sidebar-top-buttons" href="javascript:void(0);">
		<a class="scaled-up-button btn-yellow menu-toggle-button">
		  <span>Menu</span>
		  <img src="https://fantasycoliseum.com/wp-content/uploads/2025/10/Vector.png" alt="Menu">
		</a>

		<a class="scaled-up-button " href="/my-leagues">
		  <span>Games</span>
		  <img src="https://fantasycoliseum.com/wp-content/uploads/2025/10/Sword.png" alt="Games">
		</a>
	  </div>
	<div class="fc-sidebar-scroll">

		
		<div class="fc-sidebar-heading"><h5>Sport Long</h5></div>

        <div class="fc-sidebar-league-container">
		<?php

			 $current_sport = get_query_var('sport') ?? '';



			$sports = [];
			$args = [
				'post_type'      => 'sport',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			];

			$sport_posts = get_posts($args);

			foreach ($sport_posts as $post) {
				$slug  = $post->post_name;
				$label = $post->post_title;

				// Get featured image URL
				$image_url = get_the_post_thumbnail_url($post->ID, 'thumbnail');



				$sports[$slug] = [
					'label' => $label,
					'image' => $image_url, // or use $image_url if you prefer full URL
				];
			}




			// If no league is selected, set the first one as active
			if (empty($current_sport) || !array_key_exists($current_sport, $sports)) {
				$current_sport = array_key_first($sports);
			}
		?>
        <?php foreach ($sports as $slug => $sport): ?>
			<?php
				$active = ($slug === $current_sport) ? ' active' : '';
				$image_url = $sport['image'];
				$link_url = '/league/'.$slug.'/new';
			?>
			<a href="<?php echo esc_url($link_url); ?>" class="fc-sidebar-league " data-league="<?php echo esc_attr($slug); ?>">
				<img class="press-box-img" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($sport['label']); ?>" />
				<h5><?php echo esc_html($sport['label']); ?></h5>
			</a>
		<?php endforeach; ?>
			
		</div>
		
		<div class="fc-sidebar-heading"><h5>Press box</h5></div>

        <div class="fc-sidebar-league-container">
			<?php

				 $current_sport = get_query_var('sport') ?? '';



				$sports = [];
				$args = [
					'post_type'      => 'sport',
					'posts_per_page' => -1,
					'post_status'    => 'publish',
				];

				$sport_posts = get_posts($args);

				foreach ($sport_posts as $post) {
					$slug  = $post->post_name;
					$label = $post->post_title;

					// Get featured image URL
					$image_url = get_the_post_thumbnail_url($post->ID, 'thumbnail');



					$sports[$slug] = [
						'label' => $label,
						'image' => $image_url, // or use $image_url if you prefer full URL
					];
				}




				// If no league is selected, set the first one as active
				if (empty($current_sport) || !array_key_exists($current_sport, $sports)) {
					$current_sport = array_key_first($sports);
				}
			?>


			<?php foreach ($sports as $slug => $sport): ?>
				<?php
					$active = ($slug === $current_sport) ? ' active' : '';
					$image_url = $sport['image'];
					$link_url = '/news/'.$slug.'/scores';
				?>
				<a href="<?php echo esc_url($link_url); ?>" class="fc-sidebar-league<?php echo esc_attr($active); ?>" data-league="<?php echo esc_attr($slug); ?>">
					<img class="press-box-img" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($sport['label']); ?>" />
					<h5><?php echo esc_html($sport['label']); ?></h5>
				</a>
			<?php endforeach; ?>


		</div>

        <div class="fc-sidebar-heading"><h5>MLB</h5></div>

        <div class="fc-sidebar-league-container">
          <a href="/my-wallet" class="fc-sidebar-league-mlb"><img src="https://fantasycoliseum.com/wp-content/uploads/2025/09/leaders.png" alt="Leaders"/><h5>Wallet</h5></a>
          
        </div>

<!--         <div class="fc-sidebar-extra-container">
            <button class="fc-sidebar-league-extra">
				<img class="press-box-img" src="https://fantasycoliseum.com/wp-content/uploads/2025/09/injuries.png" alt="Injuries" />
				<h5>Injuries</h5>
			  </button>

			  <button class="fc-sidebar-league-extra">
				<img class="press-box-img" src="https://fantasycoliseum.com/wp-content/uploads/2025/09/transactions.png" alt="Transactions" />
				<h5>Transactions</h5>
			  </button>

			  <button class="fc-sidebar-league-extra">
				<img class="press-box-img" src="https://fantasycoliseum.com/wp-content/uploads/2025/09/schedule.png" alt="Schedule" />
				<h5>Schedule</h5>
			  </button>

			  <button class="fc-sidebar-league-extra">
				<img class="press-box-img" src="https://fantasycoliseum.com/wp-content/uploads/2025/09/teams.png" alt="Teams" />
				<h5>Teams</h5>
			  </button>

			  <button class="fc-sidebar-league-extra">
				<img class="press-box-img" src="https://fantasycoliseum.com/wp-content/uploads/2025/09/fanstasy-advice.png" alt="Fantasy Advice" />
				<h5>Fantasy Advice</h5>
			  </button>
        </div> -->
      </div>