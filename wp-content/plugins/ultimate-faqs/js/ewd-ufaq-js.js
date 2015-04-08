jQuery(function(){ //DOM Ready
    ufaqSetClickHandlers();
});

function runEffect(display, post_id) {
    var selectedEffect = reveal_effect;
    // most effect types need no options passed by default
    var options = {};
    // some effects have required parameters
    if ( selectedEffect === "size" ) {
      options = { to: { width: 200, height: 60 } };
    }
    // run the effect
    if (display == "show") {jQuery( "#ufaq-body-"+post_id ).show( selectedEffect, options, 500, handleStyles(display, post_id) );}
	if (display == "hide") {jQuery( "#ufaq-body-"+post_id ).hide( selectedEffect, options, 500, handleStyles(display, post_id) );}
};

// callback function to bring a hidden box back
function handleStyles(display, post_id) {
	if (display == "show") {setTimeout(function() {jQuery('#ufaq-body-'+post_id).removeClass("ewd-ufaq-hidden");}, 500 );}
	if (display == "hide") {setTimeout(function() {jQuery('#ufaq-body-'+post_id).addClass("ewd-ufaq-hidden");}, 500 );}
};

function ufaqSetClickHandlers() {
	jQuery('.ufaq-faq-title').on('click', function(event) {
		var post_id = jQuery(this).attr("data-postid");
		
		event.preventDefault();
		
		var selectedIdString = 'ufaq-body-'+post_id;
		
		if (jQuery('#'+selectedIdString).hasClass("ewd-ufaq-hidden")) {
			jQuery('#ufaq-excerpt-'+post_id).addClass("ewd-ufaq-hidden");
			if (reveal_effect != "none") {runEffect("show", post_id);}
			else {jQuery('#ufaq-body-'+post_id).removeClass("ewd-ufaq-hidden");}
			
			if (faq_accordion) {
				jQuery('.ufaq-faq-body').each(function() {					
					if (jQuery(this).attr("id") != selectedIdString) {
				  		jQuery(this).addClass("ewd-ufaq-hidden");
						jQuery(this).removeAttr("style");
					}
				});
			}
		}
		else {
			jQuery('#ufaq-excerpt-'+post_id).removeClass("ewd-ufaq-hidden");
			if (reveal_effect != "none") {runEffect("hide", post_id);}
			else {jQuery('#ufaq-body-'+post_id).addClass("ewd-ufaq-hidden");}
		}
	});
}

jQuery(document).ready(function() {

    jQuery('.ufaq-faq-title').click(function(){
    	var faqID = jQuery(this).attr('id');
    	jQuery('html, body').animate({scrollTop: jQuery("#"+faqID).offset().top -80}, 100);
    });

    jQuery("#ufaq-ajax-search-btn").click(function(){
		Ufaq_Ajax_Reload();
    });


});

function Ufaq_Ajax_Reload() {
    var Question = jQuery('.ufaq-text-input').val();
    var include_cat = jQuery('#ufaq-include-category').val();
    var exclude_cat = jQuery('#ufaq-exclude-category').val();
    var orderby = jQuery('#ufaq-orderby').val();
    var order = jQuery('#ufaq-order').val();
    var post_count = jQuery('#ufaq-post-count').val();

    jQuery('#ufaq-ajax-results').html('<h3>Retrieving results...</h3>');

    var data = 'Q=' + Question + '&include_category=' + include_cat + '&exclude_category=' + exclude_cat + '&orderby=' + orderby + '&order=' + order + '&post_count=' + post_count + '&action=ufaq_search';
    jQuery.post(ajaxurl, data, function(response) {
        response = response.substring(0, response.length - 1);
        jQuery('#ufaq-ajax-results').html(response);
        ufaqSetClickHandlers();
    });
}