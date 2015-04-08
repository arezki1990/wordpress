<?php
/* The file contains all of the functions which make changes to the WordPress tables */


function EWD_UFAQ_UpdateOptions() {
		$Custom_CSS = $_POST['custom_css'];
		$FAQ_Accordion = $_POST['faq_accordion'];
		$Hide_Categories = $_POST['hide_categories'];
		$Reveal_Effect = $_POST['reveal_effect'];
		$Group_By_Category = $_POST['group_by_category'];
		$Group_By_Order_By = $_POST['group_by_order_by'];
		$Group_By_Order = $_POST['group_by_order'];
		$Order_By_Setting = $_POST['order_by_setting'];
		$Order_Setting = $_POST['order_setting'];
        $Socialmedia_Array = $_POST['Socialmedia'];
        if (is_array($Socialmedia_Array)) {$socialmedia= implode(",", $Socialmedia_Array);}


		
		$Custom_CSS = stripslashes_deep($Custom_CSS);
		$FAQ_Accordion = stripslashes_deep($FAQ_Accordion);
		$Hide_Categories = stripslashes_deep($Hide_Categories);
		$Reveal_Effect = stripslashes_deep($Reveal_Effect);
		$Group_By_Category = stripslashes_deep($Group_By_Category);
		$Group_By_Order_By = stripslashes_deep($Group_By_Order_By);
		$Group_By_Order = stripslashes_deep($Group_By_Order);
		$Order_By_Setting = stripslashes_deep($Order_By_Setting);
		$Order_Setting = stripslashes_deep($Order_Setting);
        $socialmedia = stripslashes_deep($socialmedia);
		
		update_option('EWD_UFAQ_Custom_CSS', $Custom_CSS);
		update_option('EWD_UFAQ_FAQ_Accordion', $FAQ_Accordion);
		update_option('EWD_UFAQ_Hide_Categories', $Hide_Categories);
		update_option('EWD_UFAQ_Reveal_Effect', $Reveal_Effect);
		update_option('EWD_UFAQ_Group_By_Category', $Group_By_Category);
		update_option('EWD_UFAQ_Group_By_Order_By', $Group_By_Order_By);
		update_option('EWD_UFAQ_Group_By_Order', $Group_By_Order);
		update_option('EWD_UFAQ_Order_By', $Order_By_Setting);
		update_option('EWD_UFAQ_Order', $Order_Setting);
        update_option('EWD_UFAQ_Social_Media',  $socialmedia);
}

?>