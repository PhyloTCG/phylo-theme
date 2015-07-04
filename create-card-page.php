<?php
/**
 *
 * Template Name: Create Card
 *
 * @package phylo
 */

	get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

		<?php if ( is_user_logged_in() ): ?>
			<?php phylo_display_form(); ?>
		<?php else : ?>
			<p>Sorry you have to be logged in to Create a Card.</p>
		<?php endif; ?>

		</div><!-- #content -->
	</div><!-- #primary -->
<?php if ( is_user_logged_in() ) : ?>
	<div class="widget-area-wrap">
		<h4>Card Preview</h4>
		<div id="preview-card"></div>
	</div>
<?php endif; ?>
<script type="text/template" id="card-template">
<?php phylo_display_card( array(
				'id' 				=> '<%= id %>',
				'background' 		=> '<%= background %>',
				'background-color'  => '<%= background_color %>',
				'permalink'			=> '<%= permalink %>',
				'excerpt'			=> '<%= excerpt %>',
				'name-size'			=> '<%= name_size %>',
				'title'     		=> '<%= title %>',
				'title-attr'		=> '<%= title %>',
				'latin-name'		=> '<%= latin_name %>',
				'scale'     		=> '<%= scale %>',
				'food-chain'		=> '<%= food_chain %>',
				'graphic'   		=> '<%= image %>',
				'photo'     		=> false,
				'classification' 	=> '<%= classification %>',
				'point-score'		=> '<%= point_score %>',
				'card-text'			=> '<%= card_text %>',
				'temperature'		=> '<%= temperature %>',
				'graphic-credit'	=> '<%= image_credit %>',
				'photo-credit'		=> false,
				'habitat-1'			=> '<%= habitat_1 %>',
				'habitat-2'			=> '<%= habitat_2 %>',
				'habitat-3'			=> '<%= habitat_3 %>',
				), 'create');
				?>
</script>
<?php get_footer(); ?>
