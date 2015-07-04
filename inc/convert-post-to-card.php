<?php


// add_action('admin_menu', 'phylo_bulk_convert_posts_add_pages');

function phylo_bulk_convert_posts_add_pages() {
	$css = add_management_page( __( 'Convert Post to Card', 'convert-post-types' ), __( 'Convert Post Types', 'convert-post-types' ), 'manage_options', 'convert-post-types', 'phylo_bulk_convert_post_type_options' );
	add_action( 'admin_head-'.$css, 'phylo_bulk_convert_post_type_css' );
}

function phylo_bulk_convert_post_type_css() {
	?>
	<style type="text/css">
		
	</style>
	<?php
}

function phylo_bulk_convert_post_type_options() {

	phylo_bulk_convert_posts();

	?>
    <div class="wrap">
    hello

    </div>
    
<?php  // if user can

}

