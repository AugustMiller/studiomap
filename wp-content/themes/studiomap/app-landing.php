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
		
		<section class="search">

			<div class="wrapper">
				<form id="studio-query" class="clearfix" method="POST">

					<div class="column col-3">
						<!-- Critical for server-side callback! -->
						<input type="hidden" name="action" value="studio-post" id="studio-post-action" />

						<div class="field-group search-box">
							<div class="field text">
								<input type="text" placeholder="Search" id="search-term" name="search" />
							</div>
						</div>

						<div class="field-group studio-size">
							<div class="field boolean">
								<label>
									<input type="checkbox" name="include-studio-size" value="true" />
									Filter by number of employees?
								</label>
							</div>
							<div class="split clearfix">
								<div class="field number left">
									<input id="size-min" name="studio-size[min]" type="number" min="0" max="9999" placeholder="Min" />
								</div>
								<div class="field number right">
									<input id="size-max" name="studio-size[max]" type="number" min="1" placeholder="Max" />
								</div>
							</div>
						</div>

					</div>

					<div class="column col-3">
						<div class="field-group specialties">
							<?php
								$specialties = get_terms( "specialties" , array(
									"orderby" => "count",
									"hide_empty" => 1
								));
								$i = 0;
							?>
							<h3>
								Specialties
							</h3>
							<?php foreach ( $specialties as $specialty ) { ?>
							<div class="field boolean">
								<label>
									<input class="checkbox" type="checkbox" value="<?php echo $specialty->term_id; ?>" name="specialties[<?php echo $i; ?>]" />
									<div class="label-text">
										<?php echo $specialty->name; ?>
										<span class="count">(<?php echo $specialty->count; ?>)</span>
									</div>
								</label>
							</div>
							<?php $i++; } ?>
						</div>
					</div>

					<div class="column col-3">
						<div class="field-group categories">
							<?php
								$categories = get_terms( "category" , array(
									"orderby" => "name",
									"order" => "ASC",
									"hide_empty" => 1,
									"exclude" => array(1)
								));
								$i = 0;
							?>
							<h3>
								Groups
							</h3>
							<?php foreach ( $categories as $category ) { ?>
							<div class="field boolean">
								<label>
									<input class="checkbox" type="checkbox" value="<?php echo $category->term_id; ?>" name="categories[<?php echo $i; ?>]" />
									<div class="label-text">
										<?php echo $category->name; ?>
										<span class="count">(<?php echo $category->count; ?>)</span>
									</div>
								</label>
							</div>
							<?php $i++; } ?>
						</div>

					</div>

					<div class="column col-3">
						<input class="button" type="submit" value="Update" id="search-submit" />
					</div>

				</form>
			</div>

			<script>
				// Similarly to wp_enqueue_script(), but more usable
				var Locations,
					API = {
						"endpoint" : "<?php echo admin_url( 'admin-ajax.php' ); ?>"
					};

				$(document).ready( function ( ) {
					Locations = new Studios( "<?php echo admin_url( 'admin-ajax.php' ); ?>" , "<?php bloginfo('stylesheet_directory'); ?>" );
				});

			</script>


		</section>

		<section class="cards">
			<div id="rolodex" class="rolodex"></div>
		</section>

		<section class="map">
			<div class="studio-map-holder">
				<div id="map-primary" class="map-primary"></div>
			</div>
		</section>

<?php get_footer(); ?>