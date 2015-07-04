<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package phylo
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'phylo' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header><!-- .page-header -->

			<?php /* Start the Loop */

			if( isset( $_GET['post_type']) && $_GET['post_type'] == 'card' ) { ?>
			<div class="card-primary" id="post-shell">
				<?php /* Start the Loop */ ?>
				<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
						<?php Phylo_Cards::display_card(); ?>
				<?php endwhile; ?>
			</div>

			<?php } else { ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'search' ); ?>

			<?php endwhile; ?>
			<?php } ?>
			<?php phylo_content_nav( 'nav-below' ); ?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'search' ); ?>

		<?php endif; ?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>