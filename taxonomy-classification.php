<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package phylo
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php single_term_title(); ?></h1>
				<?php
				// show an optional category description
				$category_description = term_description();
				if ( ! empty( $category_description ) ) :
					echo apply_filters( 'category_archive_meta', '<div class="taxonomy-description">' . $category_description . '</div>' );
				endif;
				?>
			</header><!-- .page-header -->
			<div class="card-primary">
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
						<?php Phylo_Cards::display_card(); ?>
				<?php endwhile; ?>
			</div>
			<?php phylo_content_nav( 'nav-below' ); ?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'archive' ); ?>

		<?php endif; ?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
