<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package phylo
 */
?>

	</div><!-- #main -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div id="footer-widget-area" class="widget-area" role="complementary">
		
		<?php if ( ! dynamic_sidebar( 'footer' ) ) : ?>

		<?php endif; // end sidebar widget area ?>
		</div><!-- #footer-widget-area -->

		<div class="site-info">
			<?php do_action( 'phylo_credits' ); ?>
			<?php bloginfo( 'name' ); ?> / <?php bloginfo( 'description' ); ?> | <?php echo __( ' Proudly powered by', 'phylo' ) ?> <a href="http://wordpress.org/" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'phylo' ); ?>" rel="generator">WordPress</a> 
			
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>