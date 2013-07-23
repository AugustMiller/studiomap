<?php
/**
 * Template Name: Maps Landing
 * Description: App landing for sorting and filtering studio results.
 *
 * @package WordPress
 * @subpackage PDX studio Map
 * @since 0.1
 */


get_header(); ?>
		<?php
			if ( is_user_logged_in( ) ) {
				$logged_in = true;
			} else {
				$logged_in = false;
			}
		?>
		
		<div id="primary" class="map">
			<div id="content" role="main">
					
				Hi! A Google Maps instance will go here, eventually.

			</div><!-- #content -->

		</div><!-- #primary -->

<?php get_footer(); ?>