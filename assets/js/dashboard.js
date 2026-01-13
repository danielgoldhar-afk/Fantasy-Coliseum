jQuery(document).ready(function($){

		// Manual toggle on button click
		$(".menu-toggle-button").click(function(){
			$("#fc-dashboard-sidebar").toggleClass("closed");
		});

		// ✅ Auto-collapse when window width ≤ 600px
		function handleSidebarResponsive() {
			const $sidebar = $("#fc-dashboard-sidebar");
			if ($(window).width() <= 600) {
				if (!$sidebar.hasClass("closed")) {
					$sidebar.addClass("closed");
				}
			} else {
				if ($sidebar.hasClass("closed")) {
					$sidebar.removeClass("closed");
				}
			}
		}

		// Run once on page load
		handleSidebarResponsive();

		// Run on window resize
		$(window).on("resize", handleSidebarResponsive);
		
});