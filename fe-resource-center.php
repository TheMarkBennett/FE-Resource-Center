<?php
/**
 * Plugin Name:       Facult Excellence Resource Center
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handles all the elements or the resource center including awards, leaves, etc.
 * Version:           1.0.1
 * Author:            Mark Bennett
 * Author URI:        https://provost.ucf.edu
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */



// Add css style
function utm_user_scripts() {
    $plugin_url = plugin_dir_url( __FILE__ );

    $version =  time();

    wp_enqueue_style( 'fe-rc-style',  $plugin_url . "assets/fe-rc-styles.css");
    wp_enqueue_script( 'fe-rc-script',  $plugin_url . 'assets/fe-rc-awards.js');
    wp_enqueue_style( 'print-css', $plugin_url . 'assets/print.css', array(), $version,  'print' );
    
}

add_action( 'wp_enqueue_scripts', 'utm_user_scripts' );



 //custom postypes

 function cptui_register_my_cpts_ucf_fe_awards() {

	/**
	 * Post Type: Awards.
	 */

	$labels = [
		"name" => __( "Awards", "custom-post-type-ui" ),
		"singular_name" => __( "Award", "custom-post-type-ui" ),
	];

	$args = [
		"label" => __( "Awards", "custom-post-type-ui" ),
		"labels" => $labels,
		"description" => "Creation of Faculty Excellence Awards",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"delete_with_user" => false,
		"show_in_rest" => false,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => "ucf_fe_rc",
		"show_in_nav_menus" => false,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "award", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title" ],
	];

	register_post_type( "ucf_fe_awards", $args );
}

add_action( 'init', 'cptui_register_my_cpts_ucf_fe_awards' );


 //Option page

 class fe_rc_Settings_Page {

	public function __construct() {

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'init_settings'  ) );

	}

	public function add_admin_menu() {

		add_menu_page(
			'Faculty Excellence Resource Center',
			'FE Resource Center',
			'manage_options',
			'ucf_fe_rc',
			array( $this, 'page_layout' ),
			'',
			99
		);

	}

	public function init_settings() {

		register_setting(
			'settings_group',
			'fe_rc_settings'
		);

		add_settings_section(
			'fe_rc_settings_section',
			'',
			false,
			'fe_rc_settings'
		);


	}

	public function page_layout() {

		// Check required user capability
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( 'You do not have sufficient permissions to access this page.' );
		}

		// Admin Page Layout
		echo '<div class="wrap">' . "\n";
		echo '	<h1>' . get_admin_page_title() . '</h1>' . "\n";
		echo '	<form action="options.php" method="post">' . "\n";

		settings_fields( 'settings_group' );
		do_settings_sections( 'fe_rc_settings' );
		submit_button();

		echo '	</form>' . "\n";
		echo '</div>' . "\n";

	}

}

new fe_rc_Settings_Page;




/*
Content filter that add ACF fields to the_content() filter. This will make the content searchable
*/
function ucf_fe_content_filter_resource_center($content){

    if ( is_singular( 'ucf_fe_awards' )  && is_main_query()) { //filter for awards post type

        $count = 1;

         // Get the repeater field for awards
         if( have_rows('fe_awards_sections') ):

            $table_content = '
            
            <div class="row sm-mb-5">

            <div class="col-md-7 ">

            <h2>Table of Content</h2>

            <ul id="fe-table-of-content" class="d-flex flex-row flex-wrap list-unstyled no-gutters fe-table-of-content">';

            $new_content = '<ol class="fe-ol list-unstyled">';
            
            // loop through the rows of data
        while ( have_rows('fe_awards_sections') ) : the_row();

            

            $table_content .= '<li class="col-12 col-md-6 sm-mb-4"><a href="#awards-section-' . $count .'" class="fe-anchor">'. get_sub_field('fe_awards_section_title') .'</a></li>';

            

            $new_content .= '<li id="awards-section-' . $count .'" class="pt-5">';

            $new_content .= '<h2 class="mb-3">' . get_sub_field('fe_awards_section_title') . '</h2>';
        
            $acf_content =  get_sub_field('fe_awards_section_content', false, false);

            $new_content .=  apply_filters('acf_the_content', $acf_content);

            $new_content .= '<a href="#content" class="mt-4 d-print-none fe-back-to-top fe-anchor">Back to Top</a>';

            $new_content .= '</li>';

            $count++;
  
        endwhile;

        $table_content .= '</ul>';

        $table_content .= '
        
        </div>

        <div class="col-12 col-md-4 offset-md-1 fe-infobox py-3 px-3">
            <h2>Award Info</h2> 

            <div class="fe-ab">Opening Date</div>
            <div class="odate">'. get_field("fe_awards_opening_date") .'</div>
            <div class="fe-ab">Closing Date</div>
            <div class="cdate">'. get_field("fe_awards_closing_date") .'</div>
            <div class="fe-ab">Award Contact</div>
            <div class="cdate">'. get_field("fe_award_contact") .'</div>            
            <div class="fe-ab">'; 
            
            $table_content .= '<button onclick="window.print();return false;" class="d-print-none print-button" >Print this page</button>';

           
        $table_content .= '</div>    
            </div>
        
        
        ';

        $table_content .= '</div>';

        $new_content .= '</ol>';        

        $content .= $table_content  . $new_content;      

        else :

        // no rows found

        endif;



		

    }
    return $content;

}
add_filter('the_content', 'ucf_fe_content_filter_resource_center');