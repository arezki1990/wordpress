<?php
/* Creates the admin page, and fills it in based on whether the user is looking at
*  the overview page or an individual item is being edited */
function EWD_UFAQ_Output_Options_Page() {
		global $Full_Version;
		
		include( plugin_dir_path( __FILE__ ) . '../html/AdminHeader.php');
		include( plugin_dir_path( __FILE__ ) . '../html/StatisticsPage.php');
		include( plugin_dir_path( __FILE__ ) . '../html/AdminFooter.php');
}
?>