<?php
/**
 *
 * Template Name: My Cards Page
 *
 * @package phylo
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'page' ); ?>

		<?php endwhile; // end of the loop.
		// The Query
		//
if ( function_exists( 'STR_get_all_authors' ) ) {
	$current_user_id = STR_get_all_authors();
	if ( is_array( $current_user_id ) ) {
		$current_user_id = implode( ',', $current_user_id );
	}
} else {
	$current_user_id = get_current_user_id();
}

if ( $current_user_id > 0 ) { {
	$args = array(
				'post_type' => 'diy-card',
				'author'	=> $current_user_id,
				'posts_per_page' => 9,
				'paged' => get_query_var( 'paged' ),
			); }
	global $wp_query;
	$wp_query = new WP_Query( $args );
	$user = wp_get_current_user();
	echo '<a href="'.site_url( 'diy-decks/'. $user->user_nicename ).'">public permalink</p>';
	// The Loop
	if ( $wp_query->have_posts() ) { ?>
				<div class="card-primary">
					<?php /* Start the Loop */ ?>
					<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
							<?php Phylo_Cards::display_card(); ?>
					<?php endwhile; ?>
				</div>
		     	<?php
				phylo_content_nav( 'nav-below' );

	} else { ?>
				You don't have a card yet why don't you <a href="/create" class="button">create one</a>.
				<?php
				// no posts found
	}

	/* Restore original Post Data */
	wp_reset_postdata();
	else : ?>

		<?php
		endif;
		?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
