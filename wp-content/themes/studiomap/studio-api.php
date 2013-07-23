<?php
/**
 * Template Name: Maps API
 * Description: API for AJAX calls to get location pins and info.
 *
 * @package WordPress
 * @subpackage PDX studio Map
 * @since 0.1
 */

header('Content-Type: application/json');

echo json_encode($_POST);
/*
$params = $_POST;

$search_name = ( $params["s"] ) ? ( $params["s"] ) : "";
// array_push( $meta_query, $search_name );

$search_size = ( $params["size"] ) ? ( $params["size"] ) : ( 0 );


$query = array(
	"post_type" => "studio",
	"meta_query" => array(
		array(
			// Studio Name
			"key" => "field_51ee1fa6e61d4",
			"value" => $search_name,
			"compare" => "LIKE"
		),
		array(
			// Studio Size
			"key" => "field_51ee282c607c8",
			"value" => $search_size,
			"type" => "numeric",
			"compare" => "BETWEEN"
		)
	),
);

$results = new WP_Query ( $query );

// $params = parse_str( $request );

echo json_encode( $params );