<?php

	if ( ! $_GET["ajax"] ) get_header();

	if ( have_posts() ) { ?>

	<?php while ( have_posts() ) { the_post(); ?>

	<?php $fields = array_filter( get_fields( $post->ID ) ); ?>

	<div <?php post_class('card') ?> id="studio-<?php the_ID(); ?>">
		<div class="card-wrap">
			<div class="metadata">
				<div class="badges"></div>
				<h2><a href="<?php echo get_field('url'); ?>" target="_blank"><?php the_title(); ?></a></h2>
				<div class="stats">
					<div class="stat established">
						<div class="stat-label">
							ESTABLISHED
						</div>
						<div class="stat-value number">
							<?php echo get_field("established"); ?>
						</div>
					</div>
					<div class="stat employees">
						<div class="stat-label">
							EMPLOYEES
						</div>
						<div class="stat-value number">
							<?php echo get_field("size"); ?>
						</div>
					</div>
				</div>

				<?php if ( $fields["principals"] ) { ?>

				<div class="stats">
					<div class="stat">
						<div class="stat-label">
							Principals
						</div>
						<div class="stat-value text principals">
							<?php foreach ( $fields["principals"] as $principal ) { ?>
							<div class="principal">
								<span class="name"><?php echo $principal["full_name"]; ?></span>
								<span class="role">(<?php echo $principal["role"]; ?>)</span>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<?php } ?>

				<div class="stats">
					<div class="stat">
						<div class="stat-label">
							Tagged
						</div>
						<div class="stat-value text tags">
							<?php $tags = wp_get_post_terms( get_the_ID() , "specialties" ); ?>

							<?php for ( $t = 0; $t < count( $tags ); $t++ ) { ?>
								<span class="tag" data-tag="<?php echo $tags[$t]->slug; ?>"><?php echo $tags[$t]->name; ?></span><?php echo ( ( $t === ( count( $tags ) - 1 ) ) ? "" : ", " ); ?>
							<?php } ?>
						</div>
					</div>
				</div>

			</div>
			<div class="body">
				<?php echo get_field('description'); ?>
			</div>

			<div class="controls">
				<div class="control close">
					<span class="action">
						<active>&times;</active>
						<inactive>&times;</inactive>
					</span>
					<span class="label">Remove</span>
				</div>
				<div class="control minimize">
					<span class="action">
						<active>&lsaquo;</active>
						<inactive>&rsaquo;</inactive>
					</span>
					<span class="label">Minimize</span>
				</div>
			</div>
			
			<?php edit_post_link('Edit this entry','','.'); ?>

		</div>

		
		
		<pre><?php //print_r( array_filter( get_fields( $post->ID ) ) ); ?></pre>
				
	</div>

	<?php } ?>

<?php }

	if ( ! $_GET["ajax"] ) get_footer();

?>
