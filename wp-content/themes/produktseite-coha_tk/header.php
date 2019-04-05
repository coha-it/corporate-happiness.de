<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package _tk
 */
?>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<title><?php wp_title( '|', true, 'right' ); ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="shortcut icon" href="<?php echo esc_url( home_url( '/' ) ); ?>/wp-content/themes/twentyeleven_coha/images/favicon1.ico" type="image/x-icon" /> 
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,400italic,600' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Droid+Serif:300,400,700' rel='stylesheet' type='text/css'>
<!--<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500" rel="stylesheet" type='text/css'>
<link href="https://fonts.googleapis.com/css?family=Merriweather:400,400i,700" rel="stylesheet">-->

	<?php wp_head(); ?>
	<link rel="stylesheet" type="text/css" href="/wp-content/themes/produktseite-coha_tk/includes/css/coha-custom.css" />
</head>

<body id="body" <?php body_class(); ?> data-spy="scroll" data-target=".container-subnav" data-offset="90">
	<?php do_action( 'before' ); ?>


<nav class="site-navigation">
<?php // substitute the class "container-fluid" below if you want a wider content area ?>
	<div class="container">
		<div class="row">
				<div class="navbar navbar-default navbar-fixed-top">
				<div class="site-navigation-inner">
					<div class="navbar-header">
						<div class="mobile-logo">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( 
get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo esc_url( home_url( '/' ) ); 
?>/wp-content/themes/produktseite-coha_tk/includes/img/Corporate-Happiness-Logo.svg"/></a>
						</div>
						<!-- .navbar-toggle is used as the toggle for collapsed navbar content -->
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
							<span class="sr-only"><?php _e('Toggle navigation','_tk') ?> </span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
	
						<!-- Your site title as branding in the menu -->
						<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo 
esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo esc_url( home_url( '/' ) ); 
?>/wp-content/themes/produktseite-coha_tk/includes/img/Corporate-Happiness-Logo.svg"></img></a>
					</div >

					<!-- The WordPress Menu goes here -->
					<div class="CoHaMenu"><?php wp_nav_menu(
						array(
							'theme_location' 	=> 'primary',
							'depth'             => 2,
							'container'         => 'div',
							'container_id'      => 'navbar-collapse',
							'container_class'   => 'collapse navbar-collapse',
							'menu_class' 		=> 'nav navbar-nav navbar-right',
							'fallback_cb' 		=> 'wp_bootstrap_navwalker::fallback',
							'menu_id'			=> 'main-menu',
							'walker' 			=> new wp_bootstrap_navwalker()
						)
					); ?></div>

				</div><!-- .navbar -->
			</div>
		</div>
	</div><!-- .container -->
</nav><!-- .site-navigation -->



