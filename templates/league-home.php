<?php
/**
 * Template Name: League Home
 * Template Post Type: page
 */


$slug = $league_id; // e.g. from URL

$league = get_page_by_path($slug, OBJECT, 'league');

if (!$league) {
    echo "League not found.";
    return;
}

$league_image = get_post_meta($league->ID, 'league_image', true ) ;
$max_participants = get_post_meta($league->ID, 'max_participants', true );
$team_name = get_post_meta($league->ID, 'team_name', true );
// var_dump($league_image);

?>
<link rel="stylesheet"
      href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/dashboard.css">

<style>
.banner {
    background: #c46c00;
    border-radius: 12px;
    height: 120px;
    margin-bottom: 25px;
    /* background-image: linear-gradient(to right, #0f3c59, #004c7b); */
    font-size: 22px;
    font-weight: 600;
    color: #fff;
    display: flex;
    align-items: flex-start;
    padding: 50px 20px;
    flex-direction: row;
    justify-content: space-between;
    background-size: cover !important;
    background-position: center !important;
}
	
	 .banner h2 {
		margin: 0 !important	 
	}
.banner span {
    display: block;
    font-size: 14px;
    font-weight: normal;
    /* margin-top: 5px; */
}

  .info-box {
    background-color: #ffffff26;
    border-radius: 8px;
    margin-top: 15px;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #fff;
}
  .info-box.red {
    background-color: #5a1d1d;
  }
.info-box a {
    background-color: #ff9502;
    color: #000;
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
	text-decoration: none
}

  /* Standings + Chat */
.standings {
    background-color: #ffffff2e;
    border-radius: 8px;
    margin-top: 25px;
    padding: 15px;
    color: #fff;
}
	.standings * {
		color: #fff;
	}
  table {
    width: 100%;
    border-collapse: collapse;
  }
  th, td {
    padding: 8px 10px;
    text-align: left;
  }
  th {
    color: #9faec0;
    font-size: 13px;
  }

  .chat {
    background-color: #142a3d;
    border-radius: 8px;
    padding: 15px;
    margin-top: 20px;
  }
  .chat h3 {
    margin: 0 0 10px;
    font-size: 14px;
    color: #9faec0;
  }
	
	
	
	
	.custom-file-label {
    display: inline-block;
    padding: 10px 18px;
    background: #000000;
    color: #fff;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
}

	#league-wallpaper-upload-text {
		font-size: 14px;
	}


</style>

<div class="fc-dashboard-page">
  <div class="fc-dashboard-page-overlay"></div>
  <div class="fc-dashboard-page-inner">
    <div class="fc-dashboard-page-inner-page">
      
     <!-- ✅ SIDEBAR START -->
    <div id="fc-dashboard-sidebar" class="fc-dashboard-sidenav">
      <?php include get_template_directory() . '/template-parts/league-sidebar.php'; ?>
    </div>
    <!-- ✅ SIDEBAR END -->
      <!-- ✅ CONTENT -->
		<div id="fc-dashboard-contentarea" class="fc-dashboard-content">
			
			<h1 class="fc-page-title">
    		   <?php echo strtoupper($league->post_title); ?>
    		</h1>
    		
    		<span class="fc-page-subtitle">Home</span>
			<br>
			
			
			<div class="banner" id="league-banner" <?php if ($league_image){ ?> style="background: url('<?=$league_image;?>')" <?php } ?> >
				<div>
					<h2>
					  <?php echo $league->post_title; ?>
					</h2>
					<span>The Home of Fantasy Sports</span>
					
				</div>
				<label for="league-wallpaper-upload" class="custom-file-label"  id="upload-wallpaper-btn" 
            class="button button-primary custom-file-label" 
            data-league="<?php echo $league->ID; ?>">
					<span id="league-wallpaper-upload-text">Upload Image</span>
				</label>

			
				
				<input type="file" id="league-wallpaper-upload" accept="image/*" style="display:none;">

  
			  	
			</div>

			<div class="info-box">
			  <span>Your league is not full — 1 out of <?=$max_participants;?> have joined so far.</span>
			  <a href="/league/<?php echo $league->post_name; ?>/invite">Invite Friends Now</a>
			</div>

			<!-- <div class="info-box">
			  <span>Draft has not yet taken place. The date has not yet been set.</span>
			  <a>Set Draft Date</a>
			</div>

			<div class="info-box red">
			  <span>All transactions (claims, drops, trades, lineup changes) will be retroactively dated back to Period 1, affecting past periods, until the draft is completed.</span>
			</div> -->

			<div class="standings">
			  <h3>Standings</h3>
			  <table>
				<thead>
				  <tr><th>Rk</th><th>Team</th><th>Day</th><th>Season</th></tr>
				</thead>
				<tbody>
				  <tr><td>1</td><td><?=$team_name;?> </td><td>0</td><td>0</td></tr>
				</tbody>
			  </table>
			</div>

<!-- 			<div class="chat">
			  <h3>Chat #General</h3>
			  <p><i>No messages yet...</i></p>
			</div> -->
			
			
			<?php if(is_user_logged_in()): ?>

				<form method="POST">
					<input type="hidden" name="join_league" value="1">
					<button type="submit" class="join-btn">Join League</button>
				</form>

			<?php else : ?>

				<p>Please login to join this league.</p>

			<?php endif; ?>

			
			
			
        </div>

		
		
    </div>
  </div>
</div>

<!-- ✅ JQUERY -->
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/dashboard.js"></script>

    
<script>
jQuery(document).ready(function($){
const league_ajax = {
        ajax_url: "<?php echo admin_url('admin-ajax.php'); ?>",
        nonce: "<?php echo wp_create_nonce('create_league_nonce'); ?>",
        league_id: "<?php echo $league->ID; ?>"
    };

		let currentPage = 1;
	  	const ajaxUrl = '<?php echo site_url('/wp-admin/admin-ajax.php'); ?>';
		let currentGame = '<?php echo $current_sport; ?>'.toLowerCase();

	 	let today = new Date();
		let startDate = new Date(today);
		let todayDate = new Date();
	
	// Trigger file input
//     $("#upload-wallpaper-btn").on("click", function () {
//         $("#league-wallpaper-upload").click();
//     });

    // Handle file selection
    $("#league-wallpaper-upload").on("change", function (e) {
        const file = this.files[0];
        if (!file) return;

        let formData = new FormData();
        formData.append("action", "upload_league_wallpaper");
        formData.append("league_id", $("#upload-wallpaper-btn").data("league"));
        formData.append("wallpaper", file);
        formData.append("nonce", league_ajax.nonce);

        $.ajax({
            url: league_ajax.ajax_url,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#upload-wallpaper-btn").text("Uploading...");
            },
            success: function (response) {
                $("#upload-wallpaper-btn").text("Upload Wallpaper");

                if (response.success) {

                    // ✅ Update banner background instantly
                    $("#league-banner").css({
                        'background-image': 'url(' + response.url + ')',
                        'background-size': 'cover',
                        'background-position': 'center'
                    });
                } else {
                    alert(response.message);
                }
            }
        });
    });
 		
	
		function loadScores(c_date = null) {
			var datepicker = $("#datepicker").val() ?? today;
			 var date = c_date ? c_date : datepicker;
			$.ajax({
				url: ajaxUrl +  '?action=load_games&game='+currentGame+'&page=' + currentPage + '&date=' + date,
				type: 'POST',
				data: {
					action: 'fetch_game_scores',
					page: currentPage,
					date: date,
					game: currentGame
				},
				beforeSend: function () {
					$('#nba-scoreboard').html('<p>Loading...</p>');
				},
				success: function (res) {
					if (res.success) {
						$('#nba-scoreboard').html(res.data.html);
						const total = res.data.total;
						const perPage = res.data.per_page;
						const totalPages = Math.ceil(total / perPage);
						$('#nba-page').text(currentPage);
						$('#nba-prev').prop('disabled', currentPage <= 1);
						$('#nba-next').prop('disabled', currentPage >= totalPages);
					} else {
						$('#nba-scoreboard').html('<p>Error fetching scores.</p>');
					}
				}
			});
		}

		// Initial load
		
		loadScores(todayDate.toISOString().split("T")[0]);

		$('#datepicker').change(function () {
			currentPage = 1;
			loadScores();
		});
	
		$(document).on('click', ".date-item", function () {
			var c_date = $(this).data('date');
			loadScores(c_date);
		});


	
	
	
		function generateDates(startDate, selectedDate = null) {
		  const datesContainer = $("#dates");
		  datesContainer.empty();

		  for (let i = 0; i < 10; i++) { // ✅ show 10 days
			let d = new Date(startDate);
			d.setDate(startDate.getDate() + i);

			const dayName = d.toLocaleDateString("en-US", { weekday: "short" });
			const monthDay = d.toLocaleDateString("en-US", { month: "short", day: "numeric" });

			let item = $(`<div class="date-item" data-date="${d.toISOString().split("T")[0]}">
							<div class="day">${dayName}</div>
							<div class="date">${monthDay}</div>
						  </div>`);

			// Active if matches selectedDate OR first by default
			if ((selectedDate && d.toDateString() === selectedDate.toDateString()) || (!selectedDate && i === 0)) {
			  item.addClass("active");
			  setTimeout(() => {
				item[0].scrollIntoView({ behavior: "smooth", inline: "center", block: "nearest" });
			  }, 100);
			}

			datesContainer.append(item);
		  }

		  // Click on a date to activate it
		  $(".date-item").click(function () {
			$(".date-item").removeClass("active");
			$(this).addClass("active");
			this.scrollIntoView({ behavior: "smooth", inline: "center", block: "nearest" });
		  });
		}
		
		
    
		generateDates(startDate, today);

      // Prev button (go back 10 days)
      $("#prev").click(function () {
        startDate.setDate(startDate.getDate() - 10);
        generateDates(startDate);
		   var c_date = $(".date-item.active").data('date');
			loadScores(c_date);
      });

      // Next button (go forward 10 days)
      $("#next").click(function () {
        startDate.setDate(startDate.getDate() + 10);
        generateDates(startDate);
		  var c_date = $(".date-item.active").data('date');
			loadScores(c_date);
      });

      // Initialize jQuery UI Datepicker
      $("#datepicker").datepicker({
        showAnim: "fadeIn",
        dateFormat: "yy-mm-dd",
        beforeShow: function(input, inst) {
          var btn = $("#calendarBtn");
          var offset = btn.offset();
          setTimeout(function () {
            // ✅ keep calendar inside page
            let left = offset.left;
            let dpWidth = inst.dpDiv.outerWidth();
            if (left + dpWidth > $(window).width()) {
              left = $(window).width() - dpWidth - 10;
            }
            inst.dpDiv.css({
              top: offset.top + btn.outerHeight() + 5,
              left: left
            });
          }, 0);
        },
        onSelect: function (dateText) {
          let picked = new Date(dateText);
          generateDates(picked, picked); // pass picked for active
			
			currentPage = 1;
			loadScores();
        }
      });

      // Show datepicker when calendar button clicked
      $("#calendarBtn").click(function () {
        $("#datepicker").datepicker("show");
      });
	
		
      

    });
  </script>