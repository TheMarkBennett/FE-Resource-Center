<?php

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
		"show_in_menu" => "fe-resource-center",
		"show_in_nav_menus" => false,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "award", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor" ],
	];

	register_post_type( "ucf_fe_awards", $args );
}

add_action( 'init', 'cptui_register_my_cpts_ucf_fe_awards' );
