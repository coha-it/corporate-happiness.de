<?php
/**
 * Template Name: Startseite mit Slider 
 * 
 * @package _tk
 */
 

get_header(); ?>

<?php 
    echo do_shortcode("[metaslider id=3690]"); 
?>
<div class="main-content">
<?php // substitute the class "container-fluid" below if you want a wider content area ?>
	<div class="container">
		<div class="row">
			<div id="content" class="main-content-inner col-sm-12">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'page' ); ?>

		<?php
			// If comments are open or we have at least one comment, load up the comment template
			if ( comments_open() || '0' != get_comments_number() )
				comments_template();
		?>

	<?php endwhile; // end of the loop. ?>

<?php get_footer('above'); ?>
<?php get_footer(); ?>
