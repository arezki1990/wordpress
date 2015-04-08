<?php
/**
 * Plugin Name: Nice Responsive WP_FAQ
 * Plugin URI: http://nakshighor.com/plugins/nice-responsive-wp-faq/
 * Description: Are you want add a Responsive Faq pages in your websites? Because it will be helps you to get a more visitors and get a good relations with your clients. Now ThemeRoad is helps you to add a Faq pages in your sites easily without any kind of coding. You can just download and install our plugin as like other plugin. And then create unlimited new faq as you wish with title and unlimited description. And then you create a Faq page and just use this shortcode [tmrd_faqs].If want to use custom background or title color then go to settings > Faq color and choose your custom color as you wish. And Enjoy your Faq pages with unlimited Background and Title color. Don't Forget to give us good rating.
 
 * Version:  1.0.0
 * Author: Theme Road
 * Author URI: http://nakshighor.com/plugins/nice-responsive-wp-faq/
 * License:  GPL2
 *Text Domain: tmrd
 *  Copyright 2015 GIN_AUTHOR_NAME  (email : BestThemeRoad@gmail.com
 *
 *	This program is free software; you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License, version 2, as
 *	published by the Free Software Foundation.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program; if not, write to the Free Software
 *	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */
/*
 * Creating custom cost type to  adding FAQs.

 */



add_action( 'admin_enqueue_scripts', 'tmrd_add_color_picker' );
function tmrd_add_color_picker( $hook ) {
 
    if( is_admin() ) { 
     
        // Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' ); 
         
        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'custom-script-handle', plugins_url( '/assets/js/custom-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); 
    }
}




include '/assets/inc/ex-settings.php';

function tmrd_faq_post_type() {

	$labels = array(
		'name'                => _x( 'FAQs', 'tmrd' ),
		'singular_name'       => _x( 'FAQ', 'tmrd' ),
		'menu_name'           => __( 'FAQs', 'tmrd' ),
		'parent_item_colon'   => __( 'Parent FAQs:', 'tmrd' ),
		'all_items'           => __( 'All FAQs', 'tmrd' ),
		'view_item'           => __( 'View FAQ', 'tmrd' ),
		'add_new_item'        => __( 'Add New FAQ', 'tmrd' ),
		'add_new'             => __( 'New FAQ', 'tmrd' ),
		'edit_item'           => __( 'Edit FAQ', 'tmrd' ),
		'update_item'         => __( 'Update FAQ', 'tmrd' ),
		'search_items'        => __( 'Search FAQs', 'tmrd' ),
		'not_found'           => __( 'No FAQs found', 'tmrd' ),
		'not_found_in_trash'  => __( 'No FAQs found in Trash', 'tmrd' ),
		);
	$args = array(
		'labels'              => $labels,
		'description'         => __( 'Theme Road FAQs Post Type', 'tmrd' ),
		
		'supports'            => array( 'title', 'editor' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 20,
		'menu_icon'           => '',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		);
	register_post_type( 'tmrd_faq', $args );
}

// Hook into the 'init' action
add_action( 'init', 'tmrd_faq_post_type');





function add_menu_icons_styles(){
?>
 
<style>
#adminmenu .menu-icon-tmrd_faq div.wp-menu-image:before {
  content: '\f145';
}
</style>
 
<?php
}
add_action( 'admin_head', 'add_menu_icons_styles' );



/*
 * FAQ Post Query And Short Code
 */
function tmrd_faqs_query_shortcode() {

	$args = array (
		'post_type'              => 'tmrd_faq'
		);

	// The Query
	$tmrd_faq = new WP_Query( $args );

	// First FAQ Active
	$count = 0;
	// Code
	?>
	<div id="tmrd-colorful-faqs">
		<div class="panel-group" id="accordion">
			<?php if ( $tmrd_faq->have_posts() ) {
				while ( $tmrd_faq->have_posts() ) {
					$tmrd_faq->the_post(); $count ++; ?>
					<?php if($count == 1) { ?>
					<div class="panel panel-default">
						<div class="panel-heading" >
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#faq-<?php the_ID(); ?>">
									<span class="pull-right icon"></span>
									<?php the_title() ?>
								</a>
							</h4>
						</div>
						<div id="faq-<?php the_ID(); ?>" class="panel-collapse in">
							<div class="panel-body">
								<?php the_content(); ?>
							</div>
						</div>
					</div>
					<?php } else { ?>
					<div class="panel panel-default">
						<div class="panel-heading" >
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#faq-<?php the_ID(); ?>" class="collapsed">
									<span class="pull-right icon"></span>
									<?php the_title() ?>
								</a>
							</h4>
						</div>
						<div id="faq-<?php the_ID(); ?>" class="panel-collapse collapse">
							<div class="panel-body">
								<?php the_content(); ?>
							</div>
						</div>
					</div>
					<?php	} }
				} else {
					echo "No FAQs Found";
				}
				?>
			</div>
		</div><!-- /#ccr-colorful-faqs -->
	<?php wp_reset_postdata();
	return;

}
add_shortcode( 'tmrd_faqs', 'tmrd_faqs_query_shortcode' );


/*
 * Enqueue Bootstrap According JS and Styleseets
 */

function tmrd_faq_load_script_style() {
	wp_enqueue_script('jquery' );
	wp_enqueue_style( 'tmrd-faq-style', plugins_url('/assets/css/bootstrap.css', __FILE__), array(), '1.0.0', 'all' );
	wp_enqueue_style( 'faq-custom-style', plugins_url('/assets/css/faq-custom.css', __FILE__) );
	wp_enqueue_script( 'tmrd-faq-js', plugins_url('/assets/js/bootstrap.min.js', __FILE__), array('jquery'), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'tmrd_faq_load_script_style' );
















function faq_desire_stylesheet(){?>

<?php $options = get_option('plugin_options'); ?>

<style type="text/css">
	

.panel-default > .panel-heading {
color: #333333;
background-color: <?php echo $options['text_string']; ?> !important;
border-color: #dddddd;
}


.panel-title a{

	color: <?php echo $options['pass_string']; ?> !important;
}


.entry-content h1, .comment-content h1, .entry-content h2, .comment-content h2, .entry-content h3, .comment-content h3, .entry-content h4, .comment-content h4, .entry-content h5, .comment-content h5, .entry-content h6, .comment-content h6,
.panel-title {
 margin: 0px 0; 
 margin: 0 0; 
line-height: 1.714285714;
}



</style>

	
<?php
}
add_action('wp_head','faq_desire_stylesheet');




