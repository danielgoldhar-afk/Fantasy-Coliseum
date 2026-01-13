<?php
/**
 * Template Name: League Invite
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
.info-box button {
    background-color: #ff9502;
    color: #000;
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
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

	
	
	
	/* Success alert */
    .success-box {
      display: flex;
      align-items: center;
      gap: 12px;
      background-color: #165a47;
      color: #e2f4e8;
      border-radius: 20px;
      padding: 16px 18px;
      margin-top: 20px;
      box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);
      font-size: 15px;
      line-height: 1.4;
    }

    .success-icon {
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      width: 22px;
      height: 22px;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #aef4d0;
      font-size: 13px;
      flex-shrink: 0;
    }

    .success-content strong {
      color: #b5f5d1;
      display: block;
      font-weight: 600;
      margin-bottom: 2px;
    }

    /* Invite options */
    .invite-options {
      display: flex;
      justify-content: space-between;
      background-color: #151820;
      border-radius: 20px;
      padding: 15px;
      margin-top: 20px;
      flex-wrap: wrap;
      gap: 10px;
    }

	 .invite-option {
		flex: 1;
		text-align: center;
		cursor: pointer;
		padding: 10px 0;
		min-width: 110px;
		transition: background 0.2s;
		color: #fff;
	}
    /* Circle for icons */
    .icon-circle {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0 auto 6px auto;
      transition: 0.2s;
    }

    .icon-circle svg {
      width: 24px;
      height: 24px;
      fill: currentColor;
    }

    .invite-option:hover .icon-circle {
      transform: scale(1.1);
    }

    /* Buttons */
    .buttons {
      display: flex;
      justify-content: center;
      gap: 12px;
      margin-top: 25px;
    }

    .btn {
      border: none;
      padding: 12px 24px;
      border-radius: 10px;
      font-weight: 600;
      cursor: pointer;
      font-size: 15px;
      transition: 0.2s;
    }
    
    .btn {
	  display: inline-flex;
	  align-items: center;
	  gap: 8px; /* space between icon and text */
	}

	.btn-icon {
	  display: inline-flex;
	  align-items: center;
	  justify-content: center;
	}


    .btn-setup {
      background-color: #013d52;
      color: #b0e9ff;
    }

    .btn-home {
      background-color: #0051ff;
      color: white;
    }

    .btn:hover {
      opacity: 0.9;
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
            class="button button-primary custom-file-label"  style="display: none"
            data-league="<?php echo $league->ID; ?>">
					<span id="league-wallpaper-upload-text">Upload Image</span>
				</label>

			
				
				<input type="file" id="league-wallpaper-upload" accept="image/*" style="display:none;">

  
			  	
			</div>

			<?php 
			if(isset($_GET['created'])){
			
			?>
			<div class="success-box">
			  <div class="success-icon">✔</div>
			  <div class="success-content">
				<strong>Congratulations!</strong>
				Your <?=$league->post_title;?>league has been created.
			  </div>
			</div>
			
			<?php } ?>
			
			<div class="invite-options">
				<div class="invite-option">
					<div class="icon-circle" style="background-color:#1DA1F2; color:white;">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
							<path d="M10.4883 14.651L15.25 21H22.25L14.3917 10.5223L20.9308 3H18.2808L13.1643 8.88578L8.75 3H1.75L9.26086 13.0145L2.31915 21H4.96917L10.4883 14.651ZM16.25 19L5.75 5H7.75L18.25 19H16.25Z"></path>
						</svg>
					</div>
					Share on X
				</div>

				<div class="invite-option">
					<div class="icon-circle" style="background-color:#FF6B6B; color:white;">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
							<path d="M3 3H21C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3ZM20 7.23792L12.0718 14.338L4 7.21594V19H20V7.23792ZM4.51146 5L12.0619 11.662L19.501 5H4.51146Z"></path>
						</svg>
					</div>
					Email Invite
				</div>

				<div class="invite-option">
					<div class="icon-circle" style="background-color:#FFD93D; color:#000;">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
							<path d="M13.0607 8.11097L14.4749 9.52518C17.2086 12.2589 17.2086 16.691 14.4749 19.4247L14.1214 19.7782C11.3877 22.5119 6.95555 22.5119 4.22188 19.7782C1.48821 17.0446 1.48821 12.6124 4.22188 9.87874L5.6361 11.293C3.68348 13.2456 3.68348 16.4114 5.6361 18.364C7.58872 20.3166 10.7545 20.3166 12.7072 18.364L13.0607 18.0105C15.0133 16.0578 15.0133 12.892 13.0607 10.9394L11.6465 9.52518L13.0607 8.11097ZM19.7782 14.1214L18.364 12.7072C20.3166 10.7545 20.3166 7.58872 18.364 5.6361C16.4114 3.68348 13.2456 3.68348 11.293 5.6361L10.9394 5.98965C8.98678 7.94227 8.98678 11.1081 10.9394 13.0607L12.3536 14.4749L10.9394 15.8891L9.52518 14.4749C6.79151 11.7413 6.79151 7.30911 9.52518 4.57544L9.87874 4.22188C12.6124 1.48821 17.0446 1.48821 19.7782 4.22188C22.5119 6.95555 22.5119 11.3877 19.7782 14.1214Z"></path>
						</svg>
					</div>
					Copy Invite link
				</div>

				<div class="invite-option">
					<div class="icon-circle" style="background-color:#6C63FF; color:white;">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
							<path d="M14 14.252V16.3414C13.3744 16.1203 12.7013 16 12 16C8.68629 16 6 18.6863 6 22H4C4 17.5817 7.58172 14 12 14C12.6906 14 13.3608 14.0875 14 14.252ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13ZM12 11C14.21 11 16 9.21 16 7C16 4.79 14.21 3 12 3C9.79 3 8 4.79 8 7C8 9.21 9.79 11 12 11ZM18 17V14H20V17H23V19H20V22H18V19H15V17H18Z"></path>
						</svg>
					</div>
					Invite from past leagues
				</div>
			</div>
			
			
			
			   <!-- Buttons -->
			   <!-- Buttons -->
			<div class="buttons">
			  <a class="btn btn-setup">
				<span class="btn-icon">
				  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
					<path d="M8.68637 4.00008L11.293 1.39348C11.6835 1.00295 12.3167 1.00295 12.7072 1.39348L15.3138 4.00008H19.0001C19.5524 4.00008 20.0001 4.4478 20.0001 5.00008V8.68637L22.6067 11.293C22.9972 11.6835 22.9972 12.3167 22.6067 12.7072L20.0001 15.3138V19.0001C20.0001 19.5524 19.5524 20.0001 19.0001 20.0001H15.3138L12.7072 22.6067C12.3167 22.9972 11.6835 22.9972 11.293 22.6067L8.68637 20.0001H5.00008C4.4478 20.0001 4.00008 19.5524 4.00008 19.0001V15.3138L1.39348 12.7072C1.00295 12.3167 1.00295 11.6835 1.39348 11.293L4.00008 8.68637V5.00008C4.00008 4.4478 4.4478 4.00008 5.00008 4.00008H8.68637ZM6.00008 6.00008V9.5148L3.5148 12.0001L6.00008 14.4854V18.0001H9.5148L12.0001 20.4854L14.4854 18.0001H18.0001V14.4854L20.4854 12.0001L18.0001 9.5148V6.00008H14.4854L12.0001 3.5148L9.5148 6.00008H6.00008ZM12.0001 16.0001C9.79094 16.0001 8.00008 14.2092 8.00008 12.0001C8.00008 9.79094 9.79094 8.00008 12.0001 8.00008C14.2092 8.00008 16.0001 9.79094 16.0001 12.0001C16.0001 14.2092 14.2092 16.0001 12.0001 16.0001ZM12.0001 14.0001C13.1047 14.0001 14.0001 13.1047 14.0001 12.0001C14.0001 10.8955 13.1047 10.0001 12.0001 10.0001C10.8955 10.0001 10.0001 10.8955 10.0001 12.0001C10.0001 13.1047 10.8955 14.0001 12.0001 14.0001Z"></path>
				  </svg>
				</span>
				League Setup
			  </a>

			  <a href="/league/<?php echo $league->post_name; ?>/home" class="btn btn-home">
				<span class="btn-icon">
				  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
					<path d="M12 1L21.5 6.5V17.5L12 23L2.5 17.5V6.5L12 1ZM5.49388 7.0777L12.0001 10.8444L18.5062 7.07774L12 3.311L5.49388 7.0777ZM4.5 8.81329V16.3469L11.0001 20.1101V12.5765L4.5 8.81329ZM13.0001 20.11L19.5 16.3469V8.81337L13.0001 12.5765V20.11Z"></path>
				  </svg>
				</span>
				League Home
			  </a>
			</div>

			
			
			
			
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