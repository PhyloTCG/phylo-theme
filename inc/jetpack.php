<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package phylo
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function phylo_infinite_scroll_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'content',
		'footer'    => 'page',
	) );
}
add_action( 'after_setup_theme', 'phylo_infinite_scroll_setup' );
