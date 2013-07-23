<?php

	// Clean up the <head>
	function removeHeadLinks() {
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'wlwmanifest_link');
	}

	add_action('init', 'removeHeadLinks');

	/*
		Studio Post type
	*/

	function studio_type_init() {
		$labels = array(
			'name' => 'Studios',
			'singular_name' => 'Studio',
			'add_new' => 'Add Studio',
			'add_new_item' => 'Add New Studio',
			'edit_item' => 'Edit Studio',
			'new_item' => 'New Studio',
			'all_items' => 'All Studios',
			'view_item' => 'View Studio',
			'search_items' => 'Search Studios',
			'not_found' =>  'No studios found',
			'not_found_in_trash' => 'No studios found in Trash', 
			'parent_item_colon' => '',
			'menu_name' => 'Studios'
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'show_in_menu' => true, 
			'query_var' => true,
			'rewrite' => array( 'slug' => 'studios' ),
			'capability_type' => 'post',
			'has_archive' => true, 
			'hierarchical' => false,
			'menu_position' => 20,
			'menu_icon' => "",	
			'supports' => array()
		); 

		register_post_type( 'studio', $args );
	}

	add_action( 'init', 'studio_type_init' );

?>