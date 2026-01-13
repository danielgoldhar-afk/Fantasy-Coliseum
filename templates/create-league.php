<?php
/**
 * Template Name: Create League
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
      <!-- ✅ CONTENT -->
		<div id="fc-dashboard-contentarea" class="fc-dashboard-content">
			<h1 class="fc-page-title">
    		    Create League
    		</h1>
    		<?php
    		// Get current year
            $current_year = date('Y');
            
            // Get next year (last two digits)
            $next_year_short = date('y', strtotime('+1 year'));
$sport = get_query_var('sport');
    		
    		?>
    		<span class="fc-page-subtitle"><?php echo  $current_year . '-' . $next_year_short . ' ' . strtoupper($sport); ?></span>
			<br>
			
			
			<?php echo do_shortcode('[create_league_form]'); ?>
        </div>
    </div>
  </div>
</div>

<!-- ✅ JQUERY -->
<!-- ✅ JQUERY -->
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/dashboard.js"></script>