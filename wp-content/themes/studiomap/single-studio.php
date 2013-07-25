<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<div <?php post_class('card') ?> id="studio-<?php the_ID(); ?>">
		
		<div class="metadata">
			<div class="badges"></div>
			<h2><?php the_title(); ?></h2>
			<div class="stats">
				<div class="stat established">
					<?php echo get_field("established"); ?>
				</div>
				<div class="stat employees">
					<?php echo get_field("size"); ?>
				</div>
			
			<pre>
				<?php print_r( array_filter( get_fields( $post->ID ) ) ); ?>
			</pre>
			
			<?php the_tags( 'Tags: ', ', ', ''); ?>

		</div>
		
		<?php edit_post_link('Edit this entry','','.'); ?>
		
	</div>

<?php endwhile; endif; ?>