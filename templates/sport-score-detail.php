<?php
/**
 * Template Name: League Scores Details
 * Template Post Type: page
 */

?>
<link rel="stylesheet"
      href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/dashboard.css">



<style>
  .calendar-wrapper {
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
      width: 100%;
      max-width: 1540px;
    }

    .date-picker {
      max-width: 100%;
      height: 64px;
      display: flex;
      align-items: center;
      background: rgba(255, 255, 255, 0.12);
      border-radius: 12px;
      padding: 0 8px;
      color: #fff;
      gap: 8px;
      box-shadow: 0px 13px 16.3px 0px rgba(0, 0, 0, 0.12);
      flex: 1;
      overflow-x: auto;
      scroll-behavior: smooth;
    }

    .date-btn {
      background: linear-gradient(180deg, #FF9500 0%, #FF8000 100%);
      border: none;
      color: #fff;
      width: 48px;
      height: 48px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .dates {
      display: flex;
      gap: 16px;
      flex: 1;
    }

    .date-item {
      text-align: center;
      padding: 8px 12px;
      border-radius: 12px;
      transition: 0.3s;
      min-width: 90px;
      cursor: pointer;
      flex-shrink: 0;
    }
    .date-item.active {
      background: rgba(255, 255, 255, 0.2);
    }
    .day {
      font-size: 12px;
      opacity: 0.8;
    }
    .date {
      font-size: 14px;
      font-weight: 600;
    }

    .calendar-btn {
      background: linear-gradient(180deg, #FF9500 0%, #FF8000 100%);
      color: #fff;
      width: 64px;
      height: 64px;
      border-radius: 8px;
      box-shadow: 0px 6px 19.3px 0px rgba(0, 0, 0, 0.24);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 0.3px solid rgba(255, 255, 255, 0.15);
      flex-shrink: 0;
      position: relative;
    }

    /* Hide actual date input */
    #datepicker {
      position: absolute;
      left: -9999px;
      opacity: 0;
    }

   /* ==================== Calendar Styling ==================== */
    .ui-datepicker {
      background: #2b2b2bf2;
      border-radius: 12px;
      padding: 12px;
      width: 100%;
      max-width: 320px;
      box-shadow: 0px 13px 16.3px 0px rgba(0, 0, 0, 0.12);
      z-index: 9999 !important;
      font-family: Arial, sans-serif;
    }

    /* Header */
    .ui-datepicker-header {
      background: linear-gradient(180deg, #FF9500 0%, #FF8000 100%);
      border-radius: 8px;
      padding: 12px;
      color: #fff;
      font-weight: bold;
      font-size: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    /* Prev / Next buttons */
    .ui-datepicker-prev,
    .ui-datepicker-next {
      cursor: pointer;
      top: 10px !important;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: rgba(255,255,255,0.2);
      transition: 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
		color: white;
    }

	

    /* Day labels */
    .ui-datepicker thead th {
      color: #bbb;
      font-size: 12px;
      font-weight: normal;
      padding: 6px;
      text-align: center;
    }

    /* Dates grid */
    .ui-datepicker td {
      padding: 3px;
      text-align: center;
    }
    .ui-datepicker td a {
      display: block;
      padding: 8px 0;
      border-radius: 8px;
      text-decoration: none;
      font-size: 13px;
      transition: 0.2s;
      text-align: center;
    }

    .ui-datepicker .ui-datepicker-header{
      padding: 10px;
    }

    .ui-datepicker .ui-datepicker-prev {
      left: 10px;
    }
    .ui-datepicker .ui-datepicker-next{
      right: 10px;
    }


    .ui-state-default, .ui-widget-content .ui-state-default{
          border: 1px solid #c5c5c5;
     background: #f6f6f600; 
    font-weight: normal;
    color: #ffffff;
    }

    /* Remove default icons */
.ui-datepicker-prev span,
.ui-datepicker-next span {
  display: none !important;
}

/* Custom prev button */
.ui-datepicker-prev {
  left: 10px;
  background: url("https://fantasycoliseum.com/wp-content/uploads/2025/10/ArrowLeft.png") no-repeat center center;
  background-size: 14px 14px;
}

/* Custom next button */
.ui-datepicker-next {
  right: 10px;
  background: url("https://fantasycoliseum.com/wp-content/uploads/2025/10/ArrowLeft-1.png") no-repeat center center;
  background-size: 14px 14px;
}


    /* Hover */
    .ui-datepicker td a:hover {
    background: #ffa2053b !important
    }

    /* Selected date */
    .ui-datepicker .ui-state-active {
      background: linear-gradient(180deg, #FF9500 0%, #FF8000 100%) !important;
      color: #fff !important;
      font-weight: bold;
       border: none;
    }

    /* Today highlight */
    .ui-datepicker .ui-state-highlight {
      background: linear-gradient(180deg, #FF9500 0%, #FF8000 100%);
      border-radius: 8px;
      border: none;
    }
	/* Hide scrollbar on mobile & tablet */
	@media (max-width: 1024px) {
	  .date-picker {
		-ms-overflow-style: none;  /* IE/Edge */
		scrollbar-width: none;     /* Firefox */
	  }
	  .date-picker::-webkit-scrollbar {
		display: none;             /* Chrome/Safari */
	  }
	}

	/* Desktop full-width */
	@media (min-width: 1025px) {
	  .date-picker {
		width: 100%;
		overflow: hidden; /* no scrollbars */
		justify-content: space-between; /* distribute items evenly */
	  }
	  .dates {
		flex: 1;
		justify-content: space-between;
	  }
	}

</style>

<div class="fc-dashboard-page">
  <div class="fc-dashboard-page-overlay"></div>
  <div class="fc-dashboard-page-inner">
    <div class="fc-dashboard-page-inner-page">
      
     <!-- ✅ SIDEBAR START -->
    <div id="fc-dashboard-sidebar" class="fc-dashboard-sidenav">
      <?php include get_template_directory() . '/template-parts/main-sidebar.php'; ?>
    </div>
    <!-- ✅ SIDEBAR END -->
      <!-- ✅ CONTENT -->
		<div id="fc-dashboard-contentarea" class="fc-dashboard-content">
				
			
			
			<h1 class="fc-page-title">
    		   <?php echo strtoupper($current_sport); ?>
    		</h1>
    		
    		<span class="fc-page-subtitle">Scores</span>
			<br>
			
			
				 <div class="calendar-wrapper">
				  <div class="date-picker">
					<button class="date-btn" id="prev">
					  <img src="https://fantasycoliseum.com/wp-content/uploads/2025/10/ArrowLeft.png" alt>
					</button>
					<div class="dates" id="dates"></div>
					<button class="date-btn" id="next">
					  <img src="https://fantasycoliseum.com/wp-content/uploads/2025/10/ArrowLeft-1.png" alt>
					</button>
				  </div>

				  <!-- Calendar Icon Button -->
				  <button class="calendar-btn" id="calendarBtn">
					<img src="https://fantasycoliseum.com/wp-content/uploads/2025/10/Vector-1.png" width="28">
				  </button>

				  <!-- Hidden date input for jQuery UI datepicker -->
				  <input type="text" id="datepicker">
				</div>
				<br>
				<?php echo do_shortcode('[nba_scoreboard]'); ?>
            </div>

    </div>
  </div>
</div>

<!-- ✅ JQUERY -->
<!-- ✅ JQUERY -->
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/dashboard.js"></script>
    
<script>
jQuery(document).ready(function($){

	

		let currentPage = 1;
	  	const ajaxUrl = '<?php echo site_url('/wp-admin/admin-ajax.php'); ?>';
		let currentGame = '<?php echo $current_sport; ?>'.toLowerCase();

	 	let today = new Date();
		let startDate = new Date(today);
		let todayDate = new Date();
	
	
 		
	
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