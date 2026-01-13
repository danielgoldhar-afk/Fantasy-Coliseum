<?php


add_shortcode('create_league_form', 'render_create_league_form');

function render_create_league_form() {
    ob_start(); ?>
<style>
/* === League Create Form Styling === */

.league-create-form {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    max-width: 500px;
    background: #0000004a;
    margin: 0 auto;
    padding: 30px 50px;
    border-radius: 20px;
}
.league-create-wrap h1 {
  font-size: 24px;
  margin-bottom: 20px;
  font-weight: 600;
  color: #23282d;
}


.league-create-form .form-group {
  width: calc(50% - 10px);
}

.league-create-form .form-group.full {
  width: 100%;
}

.league-create-form label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #ffffff;
}
.league-create-form input[type="text"], .league-create-form input[type="url"], .league-create-form input[type="file"], .league-create-form input[type="number"], .league-create-form input[type="datetime-local"], .league-create-form textarea, .league-create-form select , .league-create-form input[type="date"]{
    width: 100%;
    padding: 15px 12px;
    border: 1px solid #ccd0d4;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.2s ease-in-out;
    background-color: #ffffff30;
    border: unset !important;
    color: #fff;
    font-size: 16px;
}

.league-create-form input:focus,
.league-create-form textarea:focus,
.league-create-form select:focus {
  border-color: #007cba;
  box-shadow: 0 0 0 1px #007cba;
  outline: none;
}

.league-create-form textarea {
  min-height: 80px;
  resize: vertical;
}

.league-create-form .preview-image {
  margin-top: 8px;
  border-radius: 8px;
  overflow: hidden;
  width: 100%;
  max-height: 150px;
  object-fit: cover;
  border: 1px solid #eee;
}

.league-create-form .button {
  margin-top: 20px;
  padding: 10px 20px;
  font-weight: 600;
  border-radius: 6px;
  transition: all 0.2s ease;
}

.league-create-form .button.button-primary {
  background-color: #007cba;
  color: #fff;
  border: none;
}

.league-create-form .button.button-primary:hover {
  background-color: #005a9e;
}

.league-create-form .button.cancel-btn {
  background-color: #f6f7f7;
  color: #444;
  border: 1px solid #dcdcde;
  margin-left: 10px;
}

.league-create-form .button.cancel-btn:hover {
  background-color: #e0e0e0;
}

/* Responsive */
@media (max-width: 600px) {
  .league-create-form .form-group {
    width: 100%;
  }
}
</style>
    <form id="create-league-form" class="league-create-form" enctype="multipart/form-data">
		
		<label>Pool*</label>
		<select name="pool_id" required>
			<option value="">Select Pool</option>

			<?php
	global $wpdb;
	 $pools_table = $wpdb->prefix . 'fantasy_pools';
			  // GET ALL POOLS
    		$pools = $wpdb->get_results("SELECT * FROM $pools_table ORDER BY pool_id DESC");
			if ($pools) {
				foreach ($pools as $pool) {
					echo '<option value="' . esc_attr($pool->pool_id) . '">' . esc_html($pool->pool_name) . '</option>';
				}
			}
			?>
		</select>

		
        <label>League Name*</label>
        <input type="text" name="league_name" required>

        <label>Team Name*</label>
        <input type="text" name="team_name" required>

<!--         <label>League Icon</label>
        <input type="file" name="league_icon" accept="image/*">

        <label>League Image</label>
        <input type="file" name="league_image" accept="image/*"> -->

        <label>League Type*</label>
        <select name="league_type" required>
            <option value="Redraft">Redraft</option>
            <option value="Keeper">Keeper</option>
            <option value="Dynasty">Dynasty</option>
        </select>
		
		<label>Game*</label>
		<select name="game_type" required>
			<option value="">Select League Type</option>

			<?php
			$sports = get_posts([
				'post_type'      => 'sport',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'orderby'        => 'title',
				'order'          => 'ASC'
			]);

			if ($sports) {
				foreach ($sports as $sport) {
					echo '<option value="' . esc_attr($sport->post_name) . '">' . esc_html($sport->post_title) . '</option>';
				}
			}
			?>
		</select>

        <label>Draft Type*</label>
        <select name="draft_type" required>
            <option value="Live Online Standard">Live Online Standard</option>
            <option value="Auto Draft">Auto Draft</option>
        </select>
		
        <!-- Example meta fields -->
        <label>Prize</label>
        <input type="number" name="prize" min="1" >
		
		<label>Entry fee</label>
        <input type="number" name="entry_fee" min="2" max="1000">
       
        <label>Promotion Code</label>
        <input type="text" name="promo_code">

        <label>League Description</label>
        <textarea name="description"></textarea>
		
    	<label>Max Participants</label>
        <input type="number" name="meta[max_participants]" min="2" max="20">
		
        <!-- Example meta fields -->
<!--         <label>Max Teams</label>
        <input type="number" name="meta[max_teams]" min="2" max="20">
		 -->
   		<label>Start Date</label>
        <input type="date" name="start_date" >
		
		<label>End Date</label>
        <input type="date" name="end_date" >
		
		
       

        <button type="submit" class="button button-primary">Create League</button>
        <div id="league-message"></div>
    </form>
	<script>
	jQuery(document).ready(function($){
		$('#create-league-form').on('submit', function(e){
			e.preventDefault();
			const formData = new FormData(this);

			formData.append('action', 'create_league');
	//         formData.append('nonce', league_ajax.nonce);

			$.ajax({
				url: '/wp-admin/admin-ajax.php',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					$('#league-message')
						.html(response.message)
						.css('color', response.success ? 'green' : 'red');

					if (response.success) {
						$('#create-league-form')[0].reset();
						if (response.redirect) {
							setTimeout(() => {
								window.location.href = response.redirect;
							}, 1000); // redirect after 1 second
						}
					}
				}

			});
		});
	});
	</script>
    <?php
    return ob_get_clean();
}