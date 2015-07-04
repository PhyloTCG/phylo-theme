<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package phylo
 */
?>

<article id="single-card-<?php the_ID(); ?>" <?php post_class('card'); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->
    <?php phylo_display_card(); ?>

	<div class="entry-content">

    <?php the_content(); ?>
    <?php
    wp_link_pages(
        array(
                'before' => '<div class="page-links">' . __('Pages:', 'phylo'),
                'after'  => '</div>',
        ) 
    );
    ?>
	</div><!-- .entry-content -->
	<footer class="entry-meta"><span class="edit-link">
    <?php

    if(current_user_can('update_core') ) {
        edit_post_link(__('Edit Advanced', 'phylo'), '', '');
        echo " | ";
    }

    if(( 'diy-card' == get_post_type() && current_user_can('edit_post', get_the_id()) ) || get_current_user_id() == $post->post_author ) {
        ?><a href="/edit/?id=<?php the_id();?>">Edit Card </a><?php

    } ?>
	</span></footer>
</article><!-- #post-## -->



