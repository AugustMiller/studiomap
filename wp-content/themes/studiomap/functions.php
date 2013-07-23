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



	/*
		For our maps AJAX interface
	*/

	wp_enqueue_script( 'studio-post', get_bloginfo("template_directory") . '/js/studio-app.js', array( 'jquery' ) );

	// Tie the app to the admin-ajax.php handler.
	wp_localize_script( 'studio-post', 'Studios', array( 'endpoint' => admin_url( 'admin-ajax.php' ) ) );

	add_action( 'wp_ajax_nopriv_studio-post', 'studio_post' );
	add_action( 'wp_ajax_studio-post', 'studio_post' );

	function studio_post ( ) {
		//	Override whatever headers WordPress has set.
		header('Content-Type: application/json');

		// echo json_encode($_POST);

		$params = $_POST;


		$meta = array(
			"relation" => "OR"
		);

		$search_name = ( $params["search"] ) ? ( $params["search"] ) : "";

		array_push( $meta , array(
			// Studio Name Key
			"key" => "field_51ee1fa6e61d4",
			"value" => $search_name,
			"compare" => "LIKE"
		));



		//	Studio Size


		$search_size = array();
		// $search_size["min"] = ( $params["studio-size"]["min"] ) ? ( $params["studio-size"]["min"] ) : ( 0 );
		$search_size["min"] = $params["studio-size"]["min"];
		// $search_size["max"] = ( $params["studio-size"]["max"] ) ? ( $params["studio-size"]["max"] ) : ( 9999 );
		$search_size["max"] = $params["studio-size"]["max"];

		array_push( $meta , array(
			// Studio Size Key
			"key" => "field_51ee95319696d",
			"value" => array( $search_size["min"] , $search_size["max"] ),
			"type" => "numeric",
			"compare" => "BETWEEN"
		));

		// Push it!

		$query = array(
			"post_type" => "studio",
			"meta_query" => $meta
		);

		$results = new WP_Query ( $query->posts );

		echo json_encode( $results );

		// Quit the process and let the response loose:
		exit;
	}