<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package _tk
 */
?>
			</div><!-- close .*-inner (main-content or sidebar, depending if sidebar is used) -->
		</div><!-- close .row -->
	</div><!-- close .container -->
</div><!-- close .main-content -->

<footer id="colophon" class="site-footer" role="contentinfo">
<?php // substitute the class "container-fluid" below if you want a wider content area ?>
	<div class="container">
		<div class="row">
			<div class="site-footer-inner col-sm-12">

				<div class="site-info">
				© 2019 Corporate Happiness® — Email: <a href="mailto:<?php echo antispambot( 'info@corporate-happiness.de' ); ?>"><?php echo antispambot( 'info@corporate-happiness.de' ); ?></a> | Vorübergehend mobile: <a href="tel:4915223083496">+49 1522 3083496</a> </br>
				Corporate Happiness® ist eine eingetragene Marke der Corporate Happiness GmbH.
				</div><!-- close .site-info -->
				<div class="site-footer-menu">
				<?php wp_nav_menu(
						array(
							'theme_location' 	=> 'footnary',
							'depth'             => 1,
							'container'         => 'div',
							'menu_class' 		=> 'nav',
							'menu_id'			=> 'footer-menu',
							'walker' 			=> new wp_bootstrap_navwalker()
						)
					); ?>
				</div>
			</div>
		</div>
	</div><!-- close .container -->
</footer><!-- close #colophon -->

<?php wp_footer(); ?>


</body>
</html>
