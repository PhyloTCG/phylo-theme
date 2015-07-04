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
		<div class="show-print">For best results print the cards using the Firefox Browser</div>
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">Selected Cards</h1>
				<?php phylo_content_nav( 'nav-below' ); ?>
				<?php
				if ( is_category() ) :
					// show an optional category description
					$category_description = category_description();
					if ( ! empty( $category_description ) ) :
						echo apply_filters( 'category_archive_meta', '<div class="taxonomy-description">' . $category_description . '</div>' );
						endif;

					elseif ( is_tag() ) :
						// show an optional tag description
						$tag_description = tag_description();
						if ( ! empty( $tag_description ) ) :
							echo apply_filters( 'tag_archive_meta', '<div class="taxonomy-description">' . $tag_description . '</div>' );
						endif;

					endif;
				?>
			</header><!-- .page-header -->

			<div class="card-primary" id="post-shell">
				<?php /* Start the Loop */ ?>
				<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
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
