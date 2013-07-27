<?php // get_header();

	if ( have_posts() ) { ?>

	<?php while ( have_posts() ) { the_post(); ?>

	<div <?php post_class('card') ?> id="studio-<?php the_ID(); ?>">
		<div class="card-wrap">
			<div class="metadata">
				<div class="badges"></div>
				<h2><?php the_title(); ?></h2>
				<div class="stats">
					<div class="stat established">
						<div class="stat-label">
							ESTABLISHED
						</div>
						<div class="stat-number">
							<?php echo get_field("established"); ?>
						</div>
					</div>
					<div class="stat employees">
						<div class="stat-label">
							EMPLOYEES
						</div>
						<div class="stat-number">
							<?php echo get_field("size"); ?>
						</div>
					</div>
				</div>

				<div class="tags">
					<?php $tags = wp_get_post_terms( get_the_ID() , "specialties" ); ?>

					<?php for ( $t = 0; $t < count( $tags ); $t++ ) { ?>
						<span class="tag" data-tag="<?php echo $tags[$t]->slug; ?>"><?php echo $tags[$t]->name; ?></span><?php echo ( ( $t === ( count( $tags ) - 1 ) ) ? "" : ", " ); ?>
					<?php } ?>
				</div>

			</div>
			<div class="body">
				<?php echo get_field('description'); ?>
			</div>
		</div>

		<div class="close">CLOSE THIS TILE</div>
		
		<?php edit_post_link('Edit this entry','','.'); ?>
		
		<pre><?php //print_r( array_filter( get_fields( $post->ID ) ) ); ?></pre>
				
	</div>

	<?php } ?>

<?php } //get_footer(); ?>
