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

				<script>
					var request;
					$(document).ready( function ( ) {
						console.log("Hello!");
						$("#studio-query").on( 'submit' , function( e ) {

							console.log("Form submitted");
							// prevent default posting of form
							e.preventDefault();

							// abort any pending request
							if (request) {
								request.abort();
							}
							// setup some local variables
							var $form = $(this);
							// let's select and cache all the fields
							var $inputs = $form.find("input, select, button, textarea");
							// serialize the data in the form
							var serializedData = $form.serialize();

							console.log(serializedData);

							// let's disable the inputs for the duration of the ajax request
							$inputs.prop("disabled", true);

							// fire off the request to /form.php
							request = $.ajax({
								url: "<?php echo get_permalink(9); ?>",
								type: "POST",
								data: serializedData
							});

							// callback handler that will be called on success
							request.done(function (response, textStatus, jqXHR){
								// log a message to the console
								console.log( response );
							});

							// callback handler that will be called on failure
							request.fail(function (jqXHR, textStatus, errorThrown){
								// log the error to the console
								console.error(
								    "The following error occured: " +
								    textStatus, errorThrown
								);
							});

							// callback handler that will be called regardless
							// if the request failed or succeeded
							request.always(function () {
								// reenable the inputs
								$inputs.prop("disabled", false);
							});

							return false;
						});
					});
				</script>

				<form id="studio-query" method="POST">
					<input type="text" placeholder="search term" id="search-term" name="search" />
					<input id="size-min" name="size[min]" type="number" min="0" value="1" />
					<input id="size-max" name="size[max]" type="number" min="1" value="10" />
					<input type="submit" value="submit it!" id="search-submit" />

				</form>

			</div><!-- #content -->

		</div><!-- #primary -->

<?php get_footer(); ?>