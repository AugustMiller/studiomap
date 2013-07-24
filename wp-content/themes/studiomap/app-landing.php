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

					<!-- Critical for server-side callback! -->
					<input type="hidden" name="action" value="studio-post" id="studio-post-action" />

					<div class="search">
						<input type="text" placeholder="search term" id="search-term" name="search" />
					</div>

					<div class="studio-size">
						<input type="checkbox" name="include-studio-size" value="true" />
						<input id="size-min" name="studio-size[min]" type="number" min="0" value="1" />
						<input id="size-max" name="studio-size[max]" type="number" min="1" value="10" />
					</div>

					<div class="specialties">
						<?php
							$specialties = get_terms( "specialties" , array(
								"orderby" => "count",
								"hide_empty" => 1
							));
							$i = 0;
						?>
						Specialties:
						<?php foreach ( $specialties as $specialty ) { ?>
						<div class="checkbox">
							<label>
								<input type="checkbox" value="<?php echo $specialty->term_id; ?>" name="specialties[<?php echo $i; ?>]" />
								<?php echo $specialty->name; ?> (<?php echo $specialty->count; ?>)
							</label>
						</div>
						<?php $i++; } ?>
					</div>

					<div class="categories">
						<?php
							$categories = get_terms( "category" , array(
								"orderby" => "name",
								"order" => "ASC",
								"hide_empty" => 1,
								"exclude" => array(1)
							));
							$i = 0;
						?>
						Categories:
						<?php foreach ( $categories as $category ) { ?>
						<div class="checkbox">
							<label>
								<input type="checkbox" value="<?php echo $category->term_id; ?>" name="categories[<?php echo $i; ?>]" />
								<?php echo $category->name; ?> (<?php echo $category->count; ?>)
							</label>
						</div>
						<?php $i++; } ?>
					</div>

					<input type="submit" value="submit it!" id="search-submit" />

				</form>

			</div><!-- #content -->

		</div><!-- #primary -->

<?php get_footer(); ?>