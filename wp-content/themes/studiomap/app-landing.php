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
					
				<h2>Hi! A Google Maps instance will go here, eventually.</h2>

				<form id="studio-query" method="POST">
					<input type="hidden" name="action" value="studio-post" id="studio-post-action" />
					<input type="text" placeholder="search term" id="search-term" name="search" />
					<input id="size-min" name="studio-size[min]" type="number" min="0" value="1" />
					<input id="size-max" name="studio-size[max]" type="number" min="1" value="10" />
					<input type="submit" value="submit it!" id="search-submit" />

					<!-- Checkboxes for taxonomies! -->

					<?php foreach ( get_terms( 'specialties' ) as $term ) { ?>
						<pre>
							<?php print_r( $term ); ?>
						</pre>
					<?php } ?>

				</form>

			</div><!-- #content -->

		</div><!-- #primary -->

<?php get_footer(); ?>