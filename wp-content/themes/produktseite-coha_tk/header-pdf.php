<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package _tk
 */
?><!DOCTYPE html>
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
	<link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700' rel='stylesheet' type='text/css'>
	

	<?php wp_head(); ?>
	
	<style>
	body {
	padding-top:0px;
	}
	</style>

</head>

<body id="body" <?php body_class(); ?> data-spy="scroll" data-target=".container-subnav" data-offset="90">
	<?php do_action( 'before' ); ?>

<header id="masthead" class="site-header" role="banner">
<?php // substitute the class "container-fluid" below if you want a wider content area ?>
	<div class="container">
		<div class="row">
			<div class="site-header-inner col-sm-12">

				<div class="site-branding">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
				<img src="<?php echo esc_url( home_url( '/' ) ); ?>/wp-content/themes/produktseite-coha_tk/includes/img/coha_logo_web.png">
				</a>
				</div>

			</div>
		</div>
	</div><!-- .container -->
</header><!-- #masthead -->


	<div id="temp-subnav-navigation" class="temp-container-subnav col-sm-12">
	<div class="container">
		<div class="row">

			<div id="navbar-subnav" class="navbar-subnav">
			<ul class="subnav">
			<li><script>
    document.write('<a href="' + document.referrer + '">Zurück</a>');
</script></li> 
			<li><?php echo do_shortcode('[dkpdf-button]');?></li>
			</ul>
			</div>	
		
		</div>	
		</div>	
	</div>
