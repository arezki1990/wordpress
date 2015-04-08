<?php

function UFAQ_AJAX_Search($atts) {
    global $wp;

    $current_url = $_SERVER['REQUEST_URI'];
	$ReturnString = "";

	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
			'include_category' => "",
			'exclude_category' => "",
			'orderby' => "",
			'order' => "",
            'post_count' => -1),
			$atts
		)
	);

    $ReturnString .= "<form action='#' method='post' id='ufaq-ajax-form' class='pure-form pure-form-aligned'>";
    $ReturnString .= "<input type='hidden' name='ufaq-input' value='Search'>";
    $ReturnString .= "<div class='pure-control-group'>";
    $ReturnString .= "<label  id='ufaq-ajax-search-lbl' class='ewd-otp-field-label ewd-otp-bold'>" . __('Enter your question:', "EWD_UFAQ") . "</label>";
    $ReturnString .= "<input type='hidden' name'include_category' value='" . $include_category . "' id='ufaq-include-category' />";
    $ReturnString .= "<input type='hidden' name'exclude_category' value='" . $exclude_category . "' id='ufaq-exclude-category' />";
    $ReturnString .= "<input type='hidden' name'orderby' value='" . $orderby . "' id='ufaq-orderby' />";
    $ReturnString .= "<input type='hidden' name'order' value='" . $order . "' id='ufaq-order' />";
    $ReturnString .= "<input type='hidden' name'post_count' value='" . $post_count . "' id='ufaq-post-count' />";
    $ReturnString .= "<input type='text' class='ufaq-text-input' name='Question ' placeholder='" . __('Enter your question...', "EWD_UFAQ") . "'>";
    $ReturnString .= "</div>";
    $ReturnString .= "<label for='Submit'></label><input type='button' id='ufaq-ajax-search-btn' class='ewd-otp-submit pure-button pure-button-primary' name='Search' value='" . __('Search', "EWD_UFAQ") . "'>";
    $ReturnString .= "</form>";

    $ReturnString .= "<div id='ufaq-ajax-results'></div>";
	
	return $ReturnString;
}
add_shortcode("ultimate-faq-search", "UFAQ_AJAX_Search");
?>