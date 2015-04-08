<?php
/* Processes the ajax requests being put out in the admin area and the front-end
*  of the UPCP plugin */

// Returns the FAQs that are found for a specific search
function Ufaq_search() {
    $Path = ABSPATH . 'wp-load.php';
    include_once($Path);

    echo do_shortcode("[ultimate-faqs search_string='" . $_POST['Q'] . "' include_category='" . $_POST['include_category'] . "' exclude_category='" . $_POST['exclude_category'] . "' orderby='" . $_POST['orderby'] . "' order='" . $_POST['order'] . "' post_count='" . $_POST['post_count'] . "']") ;
}
add_action('wp_ajax_ufaq_search', 'Ufaq_search');
add_action( 'wp_ajax_nopriv_ufaq_search', 'Ufaq_search');