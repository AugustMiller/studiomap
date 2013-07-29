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
			// 'capability_type' => 'editor',
			/*
			'capabilities' => array(
				'publish_posts' => 'publish_studio',
				'edit_posts' => 'edit_studio',
				'edit_others_posts' => 'edit_others_studio',
				'delete_posts' => 'delete_studio',
				'delete_others_posts' => 'delete_others_studio',
				'read_private_posts' => 'read_private_studio',
				'edit_post' => 'edit_studio',
				'delete_post' => 'delete_studio',
				'read_post' => 'read_studio'
			),
			*/
			'has_archive' => true,
			'hierarchical' => false,
			'menu_position' => 20,
			'menu_icon' => "",
			'supports' => array(),
			'taxonomies' => array('category','specialties')
		);

		register_post_type( 'studio', $args );
	}

	add_action( 'init', 'studio_type_init' );

	/*
	function add_studio_role ( ) {
		add_role('studio_author', 'Studio Manager', array(
			'publish_studio' => true,
			'edit_studio' => true,
			'edit_others_studio' => true,
			'delete_studio' => true,
			'delete_others_studio' => true,
			'read_private_studio' => true,
			'edit_studio' => true,
			'delete_studio' => true,
			'read_studio' => true
		));		
	}

	add_action( 'init', 'add_studio_role' );
	*/

	/*
		Taxonomy for Studios
	*/

	function specialties_init() {
		// Create a new taxonomy for studios' specialties
		register_taxonomy(
			'specialties',
			'studio',
			array(
				'label' => __( 'Specialties' ),
				'rewrite' => array( 'slug' => 'specialty' )
			)
		);
	}

	add_action( 'init', 'specialties_init' );


	/*
		For our maps AJAX interface
	*/

	wp_enqueue_script( 'studio-post', get_bloginfo("template_directory") . '/js/studio-app.js', array( 'jquery' ) );

	// Tie the app to the admin-ajax.php handler.
	// wp_localize_script( 'studio-post', 'StudioAPI', array( 'endpoint' => admin_url( 'admin-ajax.php' ) ) );

	add_action( 'wp_ajax_nopriv_studio-post', 'studio_post' );
	add_action( 'wp_ajax_studio-post', 'studio_post' );

	function studio_post ( ) {

		$exec = microtime( true );

		//	Override whatever headers WordPress has set.
		header('Content-Type: application/json');

		// Die early for testing:
		// echo json_encode($_POST); exit;

		$params = $_POST;

		$response = array(
			"studios" => array()
		);

		$meta = array(
			"relation" => "OR"
		);

		$tax = array(
			"relation" => "AND"
		);

		/*
			Add provided parameters to $meta ("meta_query")
		*/

		// Search Text

		if ( $params["search"] !== "" ) {

			$search_name = ( $params["search"] ) ? ( $params["search"] ) : "";

			array_push( $meta , array(
				// Studio Name Key
				"key" => "studio_name",
				"value" => "$search_name",
				"compare" => "LIKE"
			));

			foreach ( explode( ' ' , $search_name ) as $term ) {
				array_push( $meta , array(
					// Description Key
					"key" => "description",
					"value" => "$term",
					"compare" => "LIKE"
				));
			}


		}



		//	Studio Size

		if ( $params["include-studio-size"] ) {
			$search_size = array();

			$search_size["min"] = (int)( $params["studio-size"]["min"] ) ? (int)( $params["studio-size"]["min"] ) : ( 0 );
			$search_size["max"] = (int)( $params["studio-size"]["max"] ) ? (int)( $params["studio-size"]["max"] ) : ( 9999 );

			array_push( $meta , array(
				// Studio Size Key
				"key" => "size",
				"value" => array( $search_size["min"] , $search_size["max"] ),
				"type" => "NUMERIC",
				"compare" => "BETWEEN"
			));
		}

		// Year


		//	Specialties

		if ( $params["specialties"] ) {
			$specialties = $params["specialties"];
			array_map( "makeInteger" , $specialties );

			// echo json_encode($specialties); exit;

			array_push( $tax , array(
				'taxonomy' => 'specialties',
				'field' => 'id',
				'terms' => $specialties,
				'operator' => 'IN'
			));
		}

		if ( $params["categories"] ) {
			$categories = $params["categories"];
			array_map( "makeInteger" , $categories );
			
			// echo json_encode($specialties); exit;

			array_push( $tax , array(
				'taxonomy' => 'category',
				'field' => 'id',
				'terms' => $categories,
				'operator' => 'IN'
			));

		}


		/*
			Gather the arguments
		*/

		$query = array(
			"post_type" => "studio",
			"post_status" => "publish",
			"posts_per_page" => -1,
			"meta_query" => $meta,
			"tax_query" => $tax
		);


		/*
			Run the Query
		*/

		$results = new WP_Query ( $query );

		// Spit out the Query and die early
		// echo json_encode( $results ); exit;

		/*
			Grab Metadata
		*/

		$response["meta"] = array(
			"found" => (int)($results->found_posts),
			"hash" => $results->query_vars_hash,
			"query" => $results->query,
			"server" => $_POST,
			"time" => ( microtime( true ) - $exec )
		);

		if ( $results->have_posts() ) {
			while ( $results->have_posts() ) {

				// Get set up:
				$results->the_post();

				// echo json_encode((array)$results); exit;

				$fields = array_filter( get_fields( get_the_ID() ) );

				$payload = array(
					"wp" => null,
					"permalink" => get_permalink(),
					"id" => get_the_ID(),
					"body" => $fields
				);

				array_push( $response["studios"] , $payload );
			}

		} else {
			$response["studios"] = array();
		}


		/*
			Bippity-Boppity-Boop!
		*/

		echo json_encode( $response );

		// Quit the process and let the response loose:
		exit;
	}

	function makeInteger ( $string ) {
		return intval( $string );
	}


