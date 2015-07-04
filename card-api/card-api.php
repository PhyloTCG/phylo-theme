<?php


/**
 * Phylo_Cards class.
 */
class Phylo_Cards {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	function __construct() {

		require( 'custom-fields.php' );

		add_action( 'init' , 			  array( $this, 'register_cards' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ), 11 );
		add_action( 'wp_ajax_get_card_image', array( $this, 'request_card_image' ) );
		add_filter( 'template_include', array( $this, 'selected_template' ) );

		add_action( 'init', array( $this, 'save_diy_card' ) );

		/**
		 * Make the ajax happen
		 */
		if ( defined( 'DOING_AJAX' ) ) {
			if ( is_user_logged_in() ) {
				add_action( 'wp_ajax_search_cards', array( $this, 'search_cards_ajax' ) );
				add_action( 'wp_ajax_get_selected', array( $this, 'display_selected_ajax' ) );
			} else {
				add_action( 'wp_ajax_nopriv_search_cards', array( $this, 'search_cards_ajax' ) );
				add_action( 'wp_ajax_nopriv_get_selected', array( $this, 'display_selected_ajax' ) );
			}
		}

		/**
		 * Make selection work
		 */
		if ( isset( $_GET['selected'] ) ) {
			add_action( 'pre_get_posts', array( $this, 'selected_posts' ), 11, 1 );
		}
	}

	function display_selected_ajax() {
		if ( isset( $_COOKIE['phylomon_cards'] ) && $_COOKIE['phylomon_cards'] ) {
	   		// Get the selected Cards and DIY-Cards
			$args = array(
				'post__in'         => array_map( 'intval', explode( ',', $_COOKIE['phylomon_cards'] ) ),
				'post_type'        => array( 'card', 'diy-card' ),
	 		);

	   		$query = new WP_Query( $args );

		   	if ( $query->have_posts() ) {
			   	while ( $query->have_posts() ) {
			   		$query->the_post();
			   		$json_data[] = array( 'id'=> get_the_id(), 'title' => get_the_title( ), 'permalink' => get_permalink( ) );
			   	}
			   	echo json_encode( $json_data );
			}
		}
		die();
	}

	function selected_posts( $query ) {

	    if ( ( $query->is_main_query() || isset( $_GET['infinity'] ) ) && isset( $_COOKIE['phylomon_cards'] )) {
	    	$query->set( 'post_type', array( 'card', 'diy-card' ) );
	        $query->set( 'post__in', array_map( 'intval', explode( ',', $_COOKIE['phylomon_cards'])) );
	        // $query->set( 'posts_per_page', -1  );
	    }
	}


	function selected_template( $template ){
		if ( isset( $_COOKIE['phylomon_cards'] ) && isset( $_GET['selected'] ) ) {
			return STYLESHEETPATH . '/archive-selected-cards.php';
		}

		return  $template;
	}




	/**
	 * search_cards_ajax function.
	 *
	 * @access public
	 * @return void
	 */
	function search_cards_ajax()
	{
		$term = esc_attr( $_GET['term']);

		$the_query = new WP_Query( "s=$term&post_type=card&post_status=publish" );

		$output = '';
		if ( $the_query->have_posts() ) :
			while ( $the_query->have_posts() ) : $the_query->the_post();

				$img = '';
				// get_the_ID()
				$graphic = self::get_image_url( "graphic", get_the_id() );

				if ( $graphic ):
					$img = $graphic;

				else:

					$photo = self::get_image_url( "photo", get_the_id() );

					if ( $photo )
						$img = $photo;
				endif;

				$output[] = array( 'id' => get_the_id(), 'img'=> $img, 'value' => get_the_title() );
				/*$output .= '{
				"id": "'.get_the_id().'",
				"img": "'.$img.'",
				"value": "'.get_the_title().'" },';
				*/
				unset( $img);

			endwhile;
			echo  json_encode( $output);
		endif;
		die();
	}


	/**
	 * add_scripts function.
	 *
	 * @access public
	 * @return void
	 */
	function add_scripts() {

		if ( 	is_page_template( 'create-card-page.php' )
			||  is_page_template( 'edit-card-page.php' )  ):

			$card_global = array(
				'theme_url' => get_stylesheet_directory_uri(),
				'ajax_url'	=> admin_url( 'admin-ajax.php' )
				);
			wp_enqueue_script( 'create-card' );
			wp_localize_script( 'create-card', 'card_global', $card_global );
		endif;

	}

	/**
	 * pre_save_card function.
	 * helper for
	 * @access public
	 * @return void
	 */
	public function pre_save_card( $post_id ) {

		if ( $post_id != 'new' )
    		return $post_id;

		$post = array(
        	'post_status'  => 'publish' ,
        	'post_title'  => $_POST['card-title'],
        	'post_type'  => 'diy-card'
        );

        $post_id = wp_insert_post( $post );

        return $post_id;

	}

	/**
	 * register_cards function.
	 *
	 * @access public
	 * @return void
	 */
	function register_cards(){
		/**
		 *  CARD
		 **/
		$labels = array(
		    'name' => 'Cards',
		    'singular_name' => 'Card',
		    'add_new' => 'Add Card',
		    'add_new_item' => 'Add New Card',
		    'edit_item' => 'Edit Card',
		    'new_item' => 'New Card',
		    'all_items' => 'All Cards',
		    'view_item' => 'View Card',
		    'search_items' => 'Search Cards',
		    'not_found' =>  'No cards found',
		    'not_found_in_trash' => 'No cards found in Trash',
		    'parent_item_colon' => '',
		    'menu_name' => 'Cards'
		  );

		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'cards' ),
			'capability_type' => 'post',
			'has_archive' => true,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array( 'title', 'editor', 'excerpt', 'comments' )
		);


		register_post_type( 'card', $args );


		/**
		 *  DIY CARD
		 **/
		$diy_labels = array(
		    'name' => 'DIY Cards',
		    'singular_name' => 'DIY Card',
		    'add_new' => 'Add DIY Card',
		    'add_new_item' => 'Add New DIY Card',
		    'edit_item' => 'Edit DIY Card',
		    'new_item' => 'New DIY Card',
		    'all_items' => 'All DIY Cards',
		    'view_item' => 'View DIY Card',
		    'search_items' => 'Search DIY Cards',
		    'not_found' =>  'No cards found',
		    'not_found_in_trash' => 'No cards found in Trash',
		    'parent_item_colon' => '',
		    'menu_name' => 'DIY Cards'
		  );

		$diy_args = array(
			'labels' => $diy_labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'diy-cards' ),
			'capability_type' => 'post',

			'has_archive' => true,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array( 'title', 'comments', 'author' )
		);

		register_post_type( 'diy-card', $diy_args );


		/**
		 * Type Of Card
		 **/
		$labels = array(
			'name'                => _x( 'Type', 'taxonomy general name' ),
			'singular_name'       => _x( 'Type', 'taxonomy singular name' ),
			'search_items'        => __( 'Search Types' ),
			'all_items'           => __( 'All Types' ),
			'parent_item'         => __( 'Parent Type' ),
			'parent_item_colon'   => __( 'Parent Type:' ),
			'edit_item'           => __( 'Edit Type' ),
			'update_item'         => __( 'Update Type' ),
			'add_new_item'        => __( 'Add New Type' ),
			'new_item_name'       => __( 'New Type Name' ),
			'menu_name'           => __( 'Type' )
		);

		$args = array(
			'hierarchical'        => true,
			'labels'              => $labels,
			'show_ui'             => true,
			'show_admin_column'   => true,
			'query_var'           => true,
			'rewrite'             => array( 'slug' => 'type-of' )
		);

		register_taxonomy( 'type-of', array( 'card' ), $args );

		/**
		 * Classification
		 **/
		$labels = array(
			'name'                         => _x( 'Classifications', 'taxonomy general name' ),
			'singular_name'                => _x( 'Classification', 'taxonomy singular name' ),
			'search_items'                 => __( 'Search Classifications' ),
			'popular_items'                => __( 'Popular Classifications' ),
			'all_items'                    => __( 'All Classifications' ),
			'parent_item'                  => null,
			'parent_item_colon'            => null,
			'edit_item'                    => __( 'Edit Classification' ),
			'update_item'                  => __( 'Update Classification' ),
			'add_new_item'                 => __( 'Add New Classification' ),
			'new_item_name'                => __( 'New Classification Name' ),
			'separate_items_with_commas'   => __( 'Separate classification with commas' ),
			'add_or_remove_items'          => __( 'Add or remove classification' ),
			'choose_from_most_used'        => __( 'Choose from the most used classifications' ),
			'not_found'                    => __( 'No classification found.' ),
			'menu_name'                    => __( 'Classifications' )
		);

		$args = array(
			'hierarchical'            => true,
			'labels'                  => $labels,
			'show_ui'                 => true,
			'show_admin_column'       => true,
			'update_count_callback'   => '_update_post_term_count',
			'query_var'               => true,
			'rewrite'                 => array( 'slug' => 'classification' , 'hierarchical' => true )
		);

		register_taxonomy( 'classification', array( 'card' ), $args );

		/**
		 * DECK
		 **/
		$labels = array(
			'name'                         => _x( 'Decks', 'taxonomy general name' ),
			'singular_name'                => _x( 'Deck', 'taxonomy singular name' ),
			'search_items'                 => __( 'Search Decks' ),
			'popular_items'                => __( 'Popular Decks' ),
			'all_items'                    => __( 'All Decks' ),
			'parent_item'                  => null,
			'parent_item_colon'            => null,
			'edit_item'                    => __( 'Edit Deck' ),
			'update_item'                  => __( 'Update Deck' ),
			'add_new_item'                 => __( 'Add New Deck' ),
			'new_item_name'                => __( 'New Deck Name' ),
			'separate_items_with_commas'   => __( 'Separate classification with commas' ),
			'add_or_remove_items'          => __( 'Add or remove classification' ),
			'choose_from_most_used'        => __( 'Choose from the most used classifications' ),
			'not_found'                    => __( 'No classification found.' ),
			'menu_name'                    => __( 'Decks' )
		);

		$args = array(
			'hierarchical'            => true,
			'labels'                  => $labels,
			'show_ui'                 => true,
			'show_admin_column'       => true,
			'update_count_callback'   => '_update_post_term_count',
			'query_var'               => true,
			'rewrite'                 => array( 'slug' => 'decks' )
		);

		register_taxonomy( 'deck', array( 'card' ), $args );

	}

	static function get_card_array( $id = null ) {
		$id = self::get_id( $id );
		return array(
			'id' 					=> esc_attr( $id ),
			'background' 			=> self::get_card_background( $id ),
			'card-colour'			=> get_field( 'card_color', $id ),
			'border-colour'     	=> self::get_card_border_colour( $id ),
			'permalink'				=> get_permalink( $id ),
			'excerpt'				=> get_the_excerpt(),
			'name-size'				=> get_field( 'name_size', $id ),
			'title'     			=> get_the_title( $id ),
			'title-attr'			=> esc_attr( get_the_title( $id ) ),
			'latin-name'			=> get_field( 'latin_name', $id ),
			'scale'     			=> self::get_scale( $id ),
			'scale-number'			=> get_field( 'scale', $id ),
			'food-chain'			=> self::get_food_chain( $id ),
			'graphic'   			=> self::get_image_url( 'graphic', $id ),
			'photo'     			=> self::get_image_url( 'photo', $id ),
			'classification' 		=> self::get_classifications( $id ),
			'point-score'			=> get_field( 'point_score', $id ),
			'card-text'				=> get_field( 'card_text', $id ),
			'temperature'			=> self::get_temperature( $id ),
			'temperature-data'		=> get_field( 'temperature', $id ),
			'graphic-credit'		=> self::get_image_credit( 'graphic', $id ),
			'graphic-credit-data' 	=> self::get_image_credit_data( 'graphic', $id ),
			'photo-credit'			=> self::get_image_credit( 'photo', $id  ),
			'photo-credit-data'		=> self::get_image_credit_data( 'photo', $id  ),
			'habitat-1'				=> get_field( 'habitat_1', $id ),
			'habitat-2'				=> get_field( 'habitat_2', $id ),
			'habitat-3'				=> get_field( 'habitat_3', $id ),
			'wiki-link'				=> self::get_wiki_link( $id ),
			'wiki-url'				=> get_field( 'wiki_url', $id ),
			'eol-link'				=> self::get_eol_link( $id ),
			'eol-url'				=> get_field( 'eol_url', $id ),
			'photo-licence'			=> self::get_licence( 'photo', $id),
			'graphic-licence'		=> self::get_licence( 'graphic', $id)
		);
	}

	static function get_license_data(){
		return array(
			'by-nc-nd' 	=> array( 'title' => 'Attribution-NonCommercial-NoDerivs (CC BY-NC-ND)', 	'url' => 'http://creativecommons.org/licenses/by-nc-nd/4.0/',	'icon' => array( 'by', 'nc', 'nd' ) ),
			'by-sa' 	=> array( 'title' => 'Attribution-ShareAlike (CC BY-SA)', 					'url' => 'http://creativecommons.org/licenses/by-sa/4.0/',		'icon' => array( 'by', 'sa' ) ),
			'by-nc-sa' 	=> array( 'title' => 'Attribution-NonCommercial-ShareAlike (CC BY-NC-SA)', 	'url' => 'http://creativecommons.org/licenses/by-nc-sa/4.0/',	'icon' => array( 'by', 'nc', 'sa' ) ),
			'by-nc' 	=> array( 'title' => 'Attribution-NonCommercial (CC BY-NC)', 				'url' => 'http://creativecommons.org/licenses/by-nc/4.0/',		'icon' => array( 'by', 'nc' ) ),
			'by' 		=> array( 'title' => 'Attribution (CC-BY)', 								'url' => 'http://creativecommons.org/licenses/by/4.0/',			'icon' => array( 'by' ) ),
			'by-nd' 	=> array( 'title' => 'Attribution-NoDerivs (CC BY-ND)', 					'url' => 'http://creativecommons.org/licenses/nd/4.0/',			'icon' => array( 'nd' ) )
			);
	}

	static function get_licence( $type, $id ) {

		$licence 	= get_field( $type.'_license', $id );
		$get_field 	= ( $licence ? $licence : 'by-nc-nd' );


		$licenses = self::get_license_data();
		foreach( $licenses[$get_field]['icon'] as $icon ) {
			$icons[] = '<img src="'. get_template_directory_uri() .'/img/cc-icons/'.$icon.'.png" width="12" height="12" class="cc-icon-img" />';
		}
		return '<a target="_blank" href="'.esc_url( $licenses[$get_field]['url'] ).'" title="'. $licenses[$get_field]['title'].'">' . implode( '', $icons ) . '</a>';
	}
	/**
	 * display_card function.
	 *
	 * @access public
	 * @return void
	 */
	static function display_card( $card = null, $action = 'display' ) {

		if ( !$card ){

			$card = self::get_card_array();
		}
		$style = '';
		if ( $action == 'create' )
			$style = 'style="background-color:'.$card['background-color'].'"';
		?>

		<div class="card-container">
			<div id="card-<?php echo $card['id']; ?>" style="border-color: <?php echo $card['border-colour']; ?>"  class="phylocard count card-flip graphic-card" <?php echo $style; ?>  >
	    		<img  class="card-background" src="<?php echo $card['background']; ?>">

	    		<h2 id="card-name-<?php echo  $card['id']; ?>" class="card-name <?php echo $card['name-size']; ?>">
	    			<a id="card-link-<?php echo  $card['id']; ?>" href="<?php echo $card['permalink']; ?>"><?php echo $card['title']; ?></a>
	    		</h2>

	            <?php if ( $card['latin-name'] ): ?>
	    		  <span class="latin-name"><?php echo $card['latin-name']; ?></span>
	    		<?php endif; ?>

	    		<div class="num-values">
	    			<?php echo $card['scale']; ?>
	    			<?php echo $card['food-chain']; ?>
	    		</div>

	    		<div class="card-image">
	    			<!-- GRAPHIC -->
	    			<?php if ( !empty( $card['graphic'] ) || $action == "create"  ): ?>
	    			<div class="graphic"><img  class="card-graphic" src="<?php echo $card['graphic']; ?>"></div>
	    			<?php else: ?>
	    			<div class="graphic empty">
    				<strong>Sorry, there is no graphic available.  If you have one, please submit <a href="http://www.flickr.com/groups/phylomon/">here</a>.</strong>
    				</div>
	    			<?php endif;

	    			?>
	    			<?php if ( $action == "display" && get_post_type() == 'card' ):?>


	    			<?php if ( !empty( $card['photo'] ) ): ?>
	    				<div class="photo">
	    					<img  class="card-photo" src="<?php echo $card['photo']; ?>">
	    				</div>
	    			<?php else: ?>
	    				<div class="photo empty">
		    				<strong>
							Sorry, there is no photo available. If you have one, please submit
							<a href="http://www.flickr.com/groups/1293102@N24/">here</a>
							.
							</strong>
						</div>
	    			<?php endif;?>

	    			<?php endif; ?>
	    		</div>

	    		<div class="card-classification">
	    			<?php echo $card['classification']; ?>
	    		</div>

	    		<div class="creative-commons">
					<?php if (  !empty( $card['graphic'] ) ) {?>
	    			<span class="graphic"><?php echo isset( $card['graphic-licence'] ) ? $card['graphic-licence'] : ''; ?></span>
	    			<?php } ?>
	    			<?php if ( !is_singular( ) && !empty( $card['photo'] ) ) { ?><span class="photo" style="display:none;"><?php echo $card['photo-licence']; ?></span><?php } ?>

	    		</div>

	    		<div class="card-text">
		    		<?php if ( $card['point-score'] ): ?>
		    			<p style="text-align: right;" class="point-score"><strong><?php echo $card['point-score']; ?> POINTS</strong></p>
		    		<?php endif; ?>
		    		<?php echo $card['card-text']; ?>
	    		</div>

    			<div class="card-temperature"> <?php echo $card['temperature']; ?> </div>

	    		<div class="card-credit">
	    			<?php
	    			if ( get_post_type() == 'card' ) {
	    				if ( $card['graphic-credit'] ) {
	    					echo $card['graphic-credit'];
	    				}

		    			if ( $card['photo-credit'] ) {
		    				echo $card['photo-credit'];
		    			}
	    			} else {
	    				echo "<br />".$card[ 'graphic-credit' ];
	    			}
	    			?>
	    		</div>
			</div><!-- end of card -->

			<?php if ( $action == "display" ):?>
		    	<div class="card-flip-content card-flip card-flip-back" id="card-flip-content-<?php echo $card['id'] ?>">
		    		<?php echo $card['excerpt']; ?>
		 			<a title="<?php echo $card['title-attr'] ?>" href="<?php echo $card['permalink']; ?>">read more</a>
		    	</div>
			<?php endif; ?>

		<?php if ( $action == "display"  ): ?>
		<ul id="card-action-<?php echo $card['id'] ?>" class="card-action">
			<li>
				<label><input type="checkbox" id="select-card-<?php echo $card['id'] ?>" value="<?php echo $card['id'] ?>" class="select-card checkbox"> Select </label>
			</li>
			<?php if ( get_post_type() == 'card' ): ?>
			<li class="flip-container"><a class="flip" href="#flip" id="flip-<?php echo $card['id'] ?>">Flip Card</a></li>
			<?php endif; ?>
			<li><a title="<?php echo $card['title'];?>" class="permalink" href="<?php echo $card['permalink'] ?>" id="card-permalink-<?php echo $card['id'] ?>">Permalink</a></li>

			<?php if ( function_exists( 'STR_current_user_can_manage_diy_card' ) && STR_current_user_can_manage_diy_card( get_the_author_meta( 'ID' ) ) && get_post_type() == 'diy-card' ) { ?>
				<li><a title="Edit <?php echo $card['title'];?>" class="permalink" href="/edit/?card_id=<?php echo $card['id'] ?>" id="card-edit-<?php echo $card['id'] ?>">Edit</a></li>
				<li><a title="Delete <?php echo $card['title'];?>" class="permalink" href="/edit/?card_id=<?php echo $card['id'] ?>&action=delete" id="card-delete-<?php echo $card['id'] ?>">Delete</a></li>
			<?php } ?>
			<?php echo $card['wiki-link'] ?>
			<?php echo $card['eol-link'] ?>
		</ul>
	<?php endif; // end of card action  ?>
	</div>
	<?php if ( is_singular() && $action == 'display' && get_post_type() == 'card' ): ?>
	<div class="card-photo-shell">

    	<div class="card-image ">

    	<?php if ( $card['photo'] ): ?> <!-- PHOTO -->
    		<div class="photo-page"><img src="<?php echo $card["photo"]; ?>" /></div>
    		<?php else: ?>
    		<div class="photo-page empty">
    		<strong>Sorry, there is no photo available.  If you have one, please submit <a href="http://www.flickr.com/groups/1293102@N24/">here</a>.</strong>
    		</div>
    	<?php endif; ?>
    	</div>
    	<?php if ( $card['photo-credit'] ):
    		echo $card['photo-credit'];
    	endif; ?>
    	<div class="creative-commons">
    			<a href="http://creativecommons.org/licenses/by-nc-nd/2.0/deed.en_CA" target="_blank"><img src="<?php bloginfo( 'template_url' ); ?>/img/creative-commons.png" alt="Creative Commons Attribution-Noncommercial-No Derivatives Works 2.0" /></a>
    	</div>
    </div>
	<?php endif; ?>
		<?php
	}

	/**
	 * diy_card_form function.
	 *
	 * @access public
	 * @return void
	 */
	function diy_card_form( $id ) {
		require( 'card-form-template.php' );
	}
	/**
	 * get_image_url function.
	 *
	 * @access public
	 * @param string $type (default: "graphic")
	 * @return void
	 */
	static function get_image_url( $type="graphic", $id = null ) {
		$id = self::get_id( $id );
		$source = get_field( $type.'_source', $id );
		if ( 'url' == $source )
			return get_field( $type.'_url', $id );
		else
			return get_field( $type, $id );
	}

	static function get_image_credit_data( $type="graphic", $id = null ) {
		$id = self::get_id( $id );
		if ( get_post_type() == 'card' ):
			$data['name'] 	= get_field( $type.'_artist_name', $id );
			$data['url']	= get_field( $type.'_artist_url', $id );
		else:
			global $post;
			if ( $post->ID == $id ) {
				$data['name'] 	= get_the_author_meta( 'display_name' );
				$data['url']	= get_the_author_meta( 'url' );
			} else {
				$post_data = get_post( $id );
				$data['name'] 	= get_the_author_meta( 'display_name', $post_data->post_author );
				$data['url']	= get_the_author_meta( 'url', $post_data->post_author );
			}
		endif;
		return $data;
	}

	/**
	 * get_image_credit function.
	 *
	 * @access public
	 * @param string $type (default: "graphic")
	 * @return void
	 */
	static function get_image_credit( $type="graphic", $id = null ){
		$id = self::get_id( $id );
		$data = self::get_image_credit_data( $type, $id );

		$html = '
		<div class="'.esc_attr( $type ).'"> <!-- <?php echo $type; ?> -->';

		if ( ! empty( $data['name'] ) ) {
    	$html = $html . '<span>'.ucfirst( $type ).' by <em>'. $data['name'].'</em></span>';
    		if ( $data['url'] ):
    			$raw_url = str_replace( array( 'http://','https://' ), '', $data['url'] );

    		$html .='<a href="'. esc_url( $data['url'] ).'">'.$raw_url.'</a>';
    		endif;
    	}
    	$html .= '</div>';
    	return $html;
	}

	/**
	 * get_scale function.
	 *
	 * @access public
	 * @return void
	 */
	static function get_scale( $id = null ){
		$id = self::get_id( $id );
		$scale = get_field( 'scale', $id  );
		if ( $scale )
			return '<img alt="Scale '.esc_attr( $scale ).'" src="'. get_template_directory_uri().'/img/num/'. esc_attr( $scale ).'.png" />';
    	return '';
	}

	/**
	 * get_food_chain function.
	 *
	 * @access public
	 * @return void
	 */
	static function get_food_chain( $id = null ){
		$id = self::get_id( $id );
		$food = get_field( 'diet', $id );
		$hierarchy = get_field( 'food_chain_hierarchy', $id  );
		if ( $food && $hierarchy ):
			return '<img alt="Diat: '.esc_attr( $food ).' , Hierachy '.esc_attr( $hierarchy ).'" src="'.get_template_directory_uri().'/img/num/'. esc_attr( $food ). esc_attr( $hierarchy ).'.png" />';

		elseif (  !$food && $hierarchy ) :
			return '<img alt="Hierachy '.esc_attr( $hierarchy ).'" src="'.get_template_directory_uri().'/img/num/'. esc_attr( $hierarchy ).'.png">';
		endif;

		return '';
	}

	/**
	 * get_temperature function.
	 *
	 * @access public
	 * @return void
	 */
	static function get_temperature( $id = null ){
		$id = self::get_id( $id );
		$values = get_field( 'temperature', $id );
		if ( is_array( $values ) )
			return implode(", ", $values);
		else
			return '';

	}

	/**
	 * eol_link function.
	 *
	 * @access public
	 * @return void
	 */
	static function get_eol_link( $id = null, $just_link = false  ){
		$id = self::get_id( $id );
		$url = get_field( 'eol_url', $id );
		if ( $url ) {
			$link = '<a title="go to Encyclopedia of Life" class="permalink eol-link" href="'.esc_url( $url).'">EOL</a>';

			if ( $just_link ){
				return $link;
			} else  {
				return '<li>'. $link .'<li>';
			}
		}

		return '';
	}

	/**
	 * wiki_link function.
	 *
	 * @access public
	 * @return void
	 */
	static function get_wiki_link( $id = null, $just_link = false  ){
		$id = self::get_id( $id );
		$url = get_field( 'wiki_url', $id );
		if ( $url ) {
			$link = '<a title="go to Wikipedia" class="permalink wikipedia-link" href="'.esc_url( $url).'">Wiki</a>';

			if ( $just_link ){
				return $link;
			} else  {
				return '<li>'. $link .'<li>';
			}
		}

		return '';
	}

	static function get_id( $id ) {
		return (  null  == $id ? get_the_ID() : $id );
	}
	/**
	 * get_classifications function.
	 *
	 * @access public
	 * @return void
	 */
	static function get_classifications( $id = null ){
		$id = self::get_id( $id );
		return self::get_card_terms( $id, 'classification' );

	}
	static function get_type_of( $id = null ){
		$id = self::get_id( $id );
		return self::get_card_terms( $id, 'type-of' );

	}
	static function get_deck( $id = null ){
		$id = self::get_id( $id );
		return self::get_card_terms( $id, 'deck' );

	}

	static function get_card_terms( $id = null, $taxonomy = 'classification' ) {
		$id = self::get_id( $id );
		$args = array( 'orderby' => 'term_group', 'order' => 'ASC', 'fields' => 'all' );

		$terms = wp_get_object_terms( $id, 'classification', $args );

		if ( $terms ):
			$term_links = array();
			foreach( $terms as $term):
				$term_links[] = '<a rel="classification" title="View all cards that are classified with ' .esc_attr( $term->slug ). '" href="'.get_term_link( $term->slug, 'classification' ).'">'.$term->name.'</a>';

			endforeach;

			return implode( ", ", $term_links );
		endif;

		return ''; // return empty array

	}

	/**
	 * get_card_background function.
	 *
	 * @access public
	 * @return void
	 */
	static function get_card_background( $id = null ){
		$id = self::get_id( $id );
		$habitat = array();
		$habitat[] = get_field( 'habitat_1', $id );
		$habitat[] = get_field( 'habitat_2', $id );
		$habitat[] = get_field( 'habitat_3', $id );
		$color 	   = get_field( 'card_color', $id );

		$version   = 1;

		$color = strtoupper( substr( $color, 1 ) );

		if ( empty( $habitat ) && empty( $color ) )
			return theme_url().'/phylo/img/blank.gif';
		// check if the file exist already

		$file_url = '/img/generated-card-images/br-'.$color.'-'.implode( "-",$habitat)."-".$version.".png";

		if ( file_exists( get_template_directory().$file_url ) ):
			return get_template_directory_uri().$file_url;
		endif;

		require_once( get_template_directory()."/img/card-image/image.php" );
		create_image( 250, 392, $color, $habitat, $version, $file_url );
		return get_template_directory_uri().$file_url;
	}

	static function get_card_border_colour( $id ) {
		$id = self::get_id( $id );
		$color 	   = get_field( 'border_card_color', $id );
		if ( empty( $color))
			return "#212121";
		return $color;
	}

	/**
	 * save_diy_card function.
	 *
	 * @access public
	 * @return void
	 */
	function save_diy_card(){


		if ( isset( $_GET['action'] ) && 'delete' == $_GET['action'] ){
			$id = ( isset( $_GET['id'] ) ? $_GET['id'] : 0 );
			if ( $id && wp_verify_nonce( $_GET['_nonce'], 'delete_card'.$id ) ){

				wp_delete_post( $id );
				$page = get_page_by_path( '/diy-cards' );

				wp_redirect( '/my-cards' );
				die();
			}
		}


		$id = ( isset( $_POST['post_id'] ) ? $_POST['post_id'] : 0 );
		if ( isset( $_POST['_nonce_save_diy_card'] ) && wp_verify_nonce( $_POST['_nonce_save_diy_card'], 'save_diy_card'.$id ) ){


   			// save the DIY Card

   			$current_user = wp_get_current_user();


 			if ( $id ) {

 				$my_post = get_post( $id, ARRAY_A );
 				$my_post['post_title'] 	= $_POST['common_name'];
 				wp_update_post( $my_post);
 				$post_id = $id;

 			} else {
 				$my_post = array(
					'post_title' 	=> $_POST['common_name'],
					'post_status' 	=> 'publish',
					'post_author' 	=> $current_user->ID,
					'post_type'	=> 'diy-card'
				);
 				// Insert the post into the database
				$post_id = wp_insert_post( $my_post );

 			}


			// Fields
			// Card Text
			update_field( "field_517c835843f9b", $_POST['card_info'], $post_id );

 			$card_colour_field = ( $_POST['card_colour'] ? $_POST['card_colour'] : '#FFFFFF' );
 			// Card Colour
			update_field( "field_517c83f74be9a", $card_colour_field, $post_id );

 			if ( $_FILES['card_image']['name'] ) {
	 			$attachemnt_id = $this->handel_file_upload( $_FILES['card_image'], $post_id, $_POST['common_name'] );

	 			if ( is_numeric( $attachemnt_id) ):
		 			// Graphic Upload
					update_field( "field_517c803c49ed5", $attachemnt_id, $post_id );
					// Image Source - Meta
					update_field( "field_517c814bdae30", "upload", $post_id );
	 			else:
	 				// Graphic URL
	 				update_field( "field_517c81a7dae31", $attachemnt_id, $post_id );
	 				// Image Source - Meta
					update_field( "field_517c814bdae30", "url", $post_id );
	 			endif;
 			}
 			// Latin Name
			update_field( "field_517c79b5406bd", $_POST['latin_name'], $post_id );

 			// Point Score
 			update_field( "field_517c79b5496bd", $_POST['card_point_value'], $post_id );

			// Diet
			update_field( "field_517c75e3f7641", $_POST['diet'], $post_id );

			// Food Chain Hierarchy
			update_field( "field_517c79f4406be", $_POST['food_chain_hierarchy'], $post_id );

			// Scale
			update_field( "field_517c7b479fb94", $_POST['scale'], $post_id );

			// Habitat 1
			update_field( "field_517c7cb221417", $_POST['habitat_1'], $post_id );

			// Habitat 2
			update_field( "field_517c7d6b21418", $_POST['habitat_2'], $post_id );

			// Habitat 3
			update_field( "field_517c7dc621419", $_POST['habitat_3'], $post_id );

			// Temperature
			update_field( "field_517c7df02141a", $_POST['temperature'], $post_id );



			// Name size - Meta
			update_field( "field_517c66e3f7641", "", $post_id );

 			wp_redirect( get_permalink( $post_id ) );
 			die();
		}

	}

	function handel_file_upload( $file, $post_id, $card_title="card_title"){
		// HANDLE THE FILE UPLOAD

        // If the upload field has a file in it
        if (isset( $file) && ( $file['size'] > 0) ) {


            // Get the type of the uploaded file. This is returned as "type/extension"
            $arr_file_type = wp_check_filetype(basename( $file['name']));
            $uploaded_file_type = $arr_file_type['type'];

            // Set an array containing a list of acceptable formats
            $allowed_file_types = array( 'image/jpg','image/jpeg','image/gif','image/png' );

            // If the uploaded file is the right format
            if (in_array( $uploaded_file_type, $allowed_file_types)) {

				if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );

                // Options array for the wp_handle_upload function. 'test_upload' => false
                $upload_overrides = array( 'test_form' => false );

                // Handle the upload using WP's wp_handle_upload function. Takes the posted file and an options array
                $uploaded_file = wp_handle_upload( $file, $upload_overrides);

                // If the wp_handle_upload call returned a local path for the image
                if (isset( $uploaded_file['file'])) {

                    // The wp_insert_attachment function needs the literal system path, which was passed back from wp_handle_upload
                    $file_name_and_location = $uploaded_file['file'];

                    // Generate a title for the image that'll be used in the media library
                    $file_title_for_media_library = $card_title;

                    // Set up options array to add this file as an attachment
                    $attachment = array(
                        'post_mime_type' => $uploaded_file_type,
                        'post_title' => 'Uploaded image ' . addslashes( $file_title_for_media_library),
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );

                    // Run the wp_insert_attachment function. This adds the file to the media library and generates the thumbnails. If you wanted to attch this image to a post, you could pass the post id as a third param and it'd magically happen.
                    $attach_id = wp_insert_attachment( $attachment, $file_name_and_location, $post_id );


					require_once(ABSPATH . "wp-admin" . '/includes/image.php' );
                    $attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
                    wp_update_attachment_metadata( $attach_id,  $attach_data);
					/*
                    // Before we update the post meta, trash any previously uploaded image for this post.
                    // You might not want this behavior, depending on how you're using the uploaded images.
                    $existing_uploaded_image = (int) get_post_meta( $post_id,'_xxxx_attached_image', true);
                    if (is_numeric( $existing_uploaded_image)) {
                        wp_delete_attachment( $existing_uploaded_image);
                    }

                    // Now, update the post meta to associate the new image with the post
                    update_post_meta( $post_id,'_xxxx_attached_image',$attach_id);

                    // Set the feedback flag to false, since the upload was successful
                    $upload_feedback = false;
                    */

                    return $attach_id;


                } else { // wp_handle_upload returned some kind of error. the return does contain error details, so you can use it here if you want.
					return "";
                    // $upload_feedback = 'There was a problem with your upload.';
                    // update_post_meta( $post_id,'_xxxx_attached_image',$attach_id);

                }

            } else { // wrong file type

                // $upload_feedback = 'Please upload only image files (jpg, gif or png).';
                // update_post_meta( $post_id,'_xxxx_attached_image',$attach_id);

            }

        }
	}


	/**
	 * request_card_image function.
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	static function request_card_image(){
		$habitat = array();
		if ( !empty( $_POST['habitat_1']))
			$habitat[] = $_POST['habitat_1'];
		if ( !empty( $_POST['habitat_2']))
			$habitat[] = $_POST['habitat_2'];

		if ( !empty( $_POST['habitat_3']))
			$habitat[] = $_POST['habitat_3'];
		$color 	   = $_POST['color'];
		$version   = 1;


		$color = strtoupper( substr( $color, 1 ) );

		// check if the file exist already


		$file_url = '/img/generated-card-images/br-'.$color.'-'.implode( "-",$habitat)."-".$version.".png";

		if ( file_exists( get_template_directory().$file_url ) ):
			return get_template_directory_uri().$file_url;
		endif;

		require_once( get_template_directory()."/img/card-image/image.php" );
		create_image( 250, 392, $color, $habitat, $version, $file_url );
		echo get_template_directory_uri().$file_url;
		die();

	}
}
$phylo_cards = new Phylo_Cards();

function phylo_display_card( $args = null, $action = 'display' ){

	global $phylo_cards;
	$phylo_cards->display_card( $args, $action );

}

function phylo_display_form( $id = null ){
	if ( null  == $id ) {
		$id = get_the_ID();
	}

	global $phylo_cards;
	$phylo_cards->diy_card_form( $id );
}
