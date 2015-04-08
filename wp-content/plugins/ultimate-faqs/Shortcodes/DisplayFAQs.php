<?php
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Display_FAQs($atts) {
	global $wp;
	
	$current_url = $_SERVER['REQUEST_URI'];
	$Custom_CSS = get_option("EWD_UFAQ_Custom_CSS");
	$FAQ_Accordion = get_option("EWD_UFAQ_FAQ_Accordion");
	$Hide_Categories = get_option("EWD_UFAQ_Hide_Categories");
	$Reveal_Effect = get_option("EWD_UFAQ_Reveal_Effect");
	$Group_By_Category = get_option("EWD_UFAQ_Group_By_Category");
	$Group_By_Order_By = get_option("EWD_UFAQ_Group_By_Order_By");
	$Group_By_Order = get_option("EWD_UFAQ_Group_By_Order");
	$Order_By_Setting = get_option("EWD_UFAQ_Order_By");
	$Order_Setting = get_option("EWD_UFAQ_Order");
    $Socialmedia_String = get_option("EWD_UFAQ_Social_Media");
    $Socialmedia = explode(",", $Socialmedia_String);
	$ReturnString = "";
	
	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
			'search_string' => "",
			'include_category' => "",
			'exclude_category' => "",
			'orderby' => "",
			'order' => "",
            'post_count'=>-1),
			$atts
		)
	);

	if ($orderby == "") {$orderby = $Order_By_Setting;}
	if ($order == "") {$order = $Order_Setting;}
	if ($Group_By_Category == "Yes") {
		  $Category_Array = get_terms('ufaq-category', array('orderby' => $Group_By_Order_By, 'order' => $Group_By_Order));
	}
	else {
			$Category_Array = array("EWD_UFAQ_ALL_CATEGORIES");
	}
	
	if (isset($_GET['include_category'])) {$include_category = $_GET['include_category'];}
	if ($include_category != "" ) {$include_category_array = explode(",", $include_category);}
	else {$include_category_array = array();}
	if (sizeOf($include_category_array) > 0) {
		$include_array = array( 'taxonomy' => 'ufaq-category',
								'field' => 'slug',
								'terms' => $include_category_array
		);
	}
	if ($exclude_category != "" ) {$exclude_category_array = explode(",", $exclude_category);}
	else {$exclude_category_array = array();}
	if (sizeOf($exclude_category_array) > 0) {
		$exclude_array = array( 'taxonomy' => 'ufaq-category',
								'field' => 'slug',
								'terms' => $exclude_category_array,
								'operator' => 'NOT IN'
		);
	}

	$ReturnString .= "<div class='ufaq-faq-list' id='ufaq-faq-list'>";

	foreach ($Category_Array as $Category) {

		if ($Category != "EWD_UFAQ_ALL_CATEGORIES") {$category_array = array( 'taxonomy' => 'ufaq-category',
						 							 	 					'field' => 'slug',
																			'terms' => $Category->slug
													 );
		}
	
		$params = array('posts_per_page' => $post_count,
						'post_type' => 'ufaq',
						'orderby' => $orderby,
						'order' => $order,
						'tax_query' => array('relation' => 'AND',
										$include_array,
										$exclude_array,
										$category_array
									)
				);
		
		$faqs = get_posts($params);
	
		if ($Custom_CSS != "") {$ReturnString .= "<style>" . $Custom_CSS . "</style>";}
		
		$ReturnString .= "<script language='JavaScript' type='text/javascript'>";
		if ($FAQ_Accordion == "Yes") {$ReturnString .= "var faq_accordion = true;";}
		else {$ReturnString .= "var faq_accordion = false;";}
		$ReturnString .= "var reveal_effect = '" . $Reveal_Effect . "';";
		$ReturnString .= "</script>";
	
		if ($Category != "EWD_UFAQ_ALL_CATEGORIES" and sizeOf($faqs) > 0) {
			$ReturnString .= "<div class='ufaq-faq-category'>";
			$ReturnString .= "<div class='ufaq-faq-category-title'>";
			$ReturnString .= "<h4>" . $Category->name . "</h4>";
			$ReturnString .= "</div>";
	    }
	
		foreach ($faqs as $faq) {
			if ($search_string == "" or strpos($faq->post_content, $search_string) !== false or strpos($faq->post_title, $search_string) !== false) {
				$Terms = wp_get_post_terms($faq->ID, 'ufaq-category');
		
				$ReturnString .= "<div class='ufaq-faq-div'>";
		
		
				$ReturnString .= "<div class='ufaq-faq-title' id='ufaq-title-" . $faq->ID . "' data-postid='" . $faq->ID . "'>";
				$ReturnString .= "<h4 ><a class='ewd-ufaq-post-margin-symbol'  href='" . get_permalink($faq->ID) . "'>+ </a><a class='ewd-ufaq-post-margin'  href='" . get_permalink($faq->ID) . "'>" .$faq->post_title . " </a></h4>";
				$ReturnString .= "</div>";
		
				if (strlen($faq->post_excerpt) > 0) {$ReturnString .= "<div class='ufaq-faq-excerpt' id='ufaq-excerpt-" . $faq->ID . "'>" . apply_filters('the_content', html_entity_decode($faq->post_excerpt)) . "</div>";}
				$ReturnString .= "<div class='ufaq-faq-body ewd-ufaq-hidden' id='ufaq-body-" . $faq->ID . "'>";
				$ReturnString .= "<div class='ewd-ufaq-post-margin ufaq-faq-post' id='ufaq-post-" . $faq->ID . "'>" . apply_filters('the_content', html_entity_decode($faq->post_content)) . "</div>";
		
				if ($Hide_Categories == "No") {
					$ReturnString .= "<div class='ufaq-faq-categories' id='ufaq-categories-" . $faq->ID . "'>";
					if (sizeOf($Terms) > 1) {$ReturnString .= "Categories: ";}
					else {$ReturnString .= "Category: ";}
					foreach ($Terms as $Term) {$ReturnString .= "<a  href='" . $current_url . "?include_category=" . $Term->slug . "'>" .$Term->name . "</a>, ";}
					if (sizeOf($Terms) > 0) {$ReturnString = substr($ReturnString, 0, strlen($ReturnString)-2);}
					$ReturnString .= "</div>";
				}
		
				if ($Socialmedia[0] != "") {$ReturnString .= "<div class='ufaq-social-links'>Share: ";}
			    if(in_array("Facebook", $Socialmedia)) {$ReturnString .= "<span class='st_facebook' data-displayText='Facebook'></span> ";}
			    if(in_array("Google", $Socialmedia)) {$ReturnString .= "<span class='st_googleplus' data-displayText='Google +'></span> ";}
			    if(in_array("Twitter", $Socialmedia)) {$ReturnString .= "<span class='st_twitter' data-displayText='Tweet'></span> ";}
			    if(in_array("Linkedin", $Socialmedia)) {$ReturnString .= "<span class='st_linkedin' data-displayText='LinkedIn'></span> ";}
			    if(in_array("Pinterest", $Socialmedia)) {$ReturnString .= "<span class='st_pinterest' data-displayText='Pinterest'></span> ";}
			    if(in_array("Email", $Socialmedia)) {$ReturnString .= "<span class='st_email' data-displayText='Email'></span> ";}
			    if ($Socialmedia[0] != "") {$ReturnString .= "</div>";}	
				
				$ReturnString .= "</div>";
				$ReturnString .= "</div>";
			}
		}
	
		if ($Category != "EWD_UFAQ_ALL_CATEGORIES") {
			$ReturnString .= "</div>";
		}
	}
	$ReturnString .= "</div>";

	return $ReturnString;
}
add_shortcode("ultimate-faqs", "Display_FAQs");
