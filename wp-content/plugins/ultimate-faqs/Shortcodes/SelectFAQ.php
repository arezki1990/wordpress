<?php
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Display_Select_FAQs($atts) {
	$Custom_CSS = get_option("EWD_UFAQ_Custom_CSS");
	$FAQ_Accordion = get_option("EWD_UFAQ_FAQ_Accordion");
	$Reveal_Effect = get_option("EWD_UFAQ_Reveal_Effect");

	$ReturnString = "";

	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
									'faq_name' => "",
									'faq_slug' => "",
									'faq_id' => ""),
									$atts
		)
	);

	$name_array = explode(",", $faq_name);
	$slug_array = explode(",", $faq_slug);
	$id_array = explode(",", $faq_id);

	foreach ($name_array as $post_name) {
		$single_post = get_page_by_title($post_name, "OBJECT", "ufaq");
		$post_id_array[] = $single_post->ID;
	}

	foreach ($slug_array as $post_slug) {
		$single_post = get_page_by_path($post_slug, "OBJECT", "ufaq");
		$post_id_array[] = $single_post->ID;
	}

	foreach ($id_array as $post_id) {
		$post_id_array[] = $post_id;
	}

	$params = array(
					'posts_per_page' => -1,
					'post_type' => 'ufaq',
					'include' => $post_id_array
				);
	$faqs = get_posts($params);

	if ($Custom_CSS != "") {$ReturnString .= "<style>" . $Custom_CSS . "</style>";}

	$ReturnString .= "<script language='JavaScript' type='text/javascript'>";
	if ($FAQ_Accordion == "Yes") {$ReturnString .= "var faq_accordion = true;";}
	else {$ReturnString .= "var faq_accordion = false;";}
	$ReturnString .= "var reveal_effect = '" . $Reveal_Effect . "';";
	$ReturnString .= "</script>";

	$ReturnString .= "<div class='ufaq-faq-list' id='ufaq-faq-list'>";
	foreach ($faqs as $faq) {
		$Terms = wp_get_post_terms($faq->ID, 'ufaq-category');

		$ReturnString .= "<div class='ufaq-faq-div'>";

		$ReturnString .= "<div class='ufaq-faq-title' id='ufaq-title-" . $faq->ID . "' data-postid='" . $faq->ID . "'>";
		$ReturnString .= "<h3><a href='" . get_permalink($faq->ID) . "'>" . $faq->post_title . "</a></h3>";
		$ReturnString .= "</div>";

		if (strlen($faq->post_excerpt) > 0) {$ReturnString .= "<div class='ufaq-faq-excerpt' id='ufaq-excerpt-" . $faq->ID . "'>" . $faq->post_excerpt . "</div>";}
		$ReturnString .= "<div class='ufaq-faq-body ewd-ufaq-hidden' id='ufaq-body-" . $faq->ID . "'>";
		$ReturnString .= "<div class='ufaq-faq-post' id='ufaq-post-" . $faq->ID . "'>" . $faq->post_content . "</div>";

		if ($Hide_Categories == "No") {
			$ReturnString .= "<div class='ufaq-faq-categories' id='ufaq-categories-" . $faq->ID . "'>";
			if (sizeOf($Terms) > 1) {$ReturnString .= "Categories: ";}
			else {$ReturnString .= "Category: ";}
			foreach ($Terms as $Term) {$ReturnString .= "<a  href='" . $current_url . "?include_category=" . $Term->slug . "'>" .$Term->name . "</a>, ";}
			if (sizeOf($Terms) > 0) {$ReturnString = substr($ReturnString, 0, strlen($ReturnString)-2);}
			$ReturnString .= "</div>";
		}

		$ReturnString .= "</div>";
		$ReturnString .= "</div>";
	}
	$ReturnString .= "</div>";

	return $ReturnString;
}
add_shortcode("select-faq", "Display_Select_FAQs");