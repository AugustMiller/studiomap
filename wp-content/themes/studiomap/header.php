<!DOCTYPE html>

<html <?php language_attributes(); ?>>

	<head profile="http://gmpg.org/xfn/11">
		
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		
		<?php if (is_search()) { ?>
		   <meta name="robots" content="noindex, nofollow" /> 
		<?php } ?>

		<title>
			<?php
				/*
				 * Print the <title> tag based on what is being viewed.
				 */
				global $page, $paged;

				// Add the blog name.
				bloginfo( 'name' );

				wp_title( '/', true, 'left' );

				// Add a page number if necessary:
				if ( $paged >= 2 || $page >= 2 )
					echo ' / ' . sprintf( 'Page %s' , max( $paged, $page ) );
			?>
		</title>
		
		<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
		
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />
		
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

		<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/stylesheets/base.css" type="text/css" />

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>

		<script type="text/javascript" src="//use.typekit.net/rpd3hfk.js"></script>
		<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

		<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

		<?php wp_head(); ?>
		
	</head>

	<body <?php body_class(); ?>>
		
		<div id="page">

			<header id="navigation">
				<h1>
					<a href="<?php echo get_option('home'); ?>/">
						<?php bloginfo('name'); ?>
					</a>
				</h1>
			</header>

