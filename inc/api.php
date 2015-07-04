<?php

add_action("template_redirect",array( "phylomon_card_api", 'init' ) );

/**
 * phylomon_card_api class.
 */
class phylomon_card_api{
	
	/**
	 * init function.
	 * 
	 * @access public
	 * @return void
	 */
	function init(){
		
		if( !isset( $_GET['api'] ) )
			return;
			
			
		$data = self::router();
		if( is_array( $data  ) ) {
			
			switch( $_GET['api'] ) {
			
			case 'xml':
				if( class_exists('SimpleXMLElement') ):
				$xml = new SimpleXMLElement("<?xml version=\"1.0\"?><cards></cards>"); 
				foreach( $data as $card ):
					$subnode = $xml->addChild("card");
					self::array_to_xml( $card, $subnode );
				endforeach;
				echo $xml->asXML();
				else:
					echo "Simple XML Element Not supported";
				endif;
				
			break;
			
			case 'json':
			default:
				header('Content-Type: application/json');
				if(isset($_GET['callback']) && is_string($_GET['callback'])):
					echo $_GET['callback'].'('.json_encode($data).');';
				else:
				
				echo ''.json_encode($data);
				endif;
			break;
		}
		die();
		}
			
	}
	
	/**
	 * router function.
	 * 
	 * @access public
	 * @return void
	 */
	function router(){
		global $post;
		
		
		if( is_front_page() || is_post_type_archive( 'card' ) || is_post_type_archive( 'diy-card' ) ): // homepage

			$post_type = ( is_post_type_archive( 'diy-card' ) ? 'diy-card' : 'card' );
			$page = '';
			if( isset( $_GET['page'] ) && is_numeric( $_GET['page'] ) )
				$page = '&paged='.$_GET['page'];
				
			$category = 'cards';
			if(isset( $_GET['diy'] ) || is_page('diy-cards'))
				$category = 'diy-cards';
			
			$num = 20;
			
			if( isset($_GET['num']) && is_numeric( $_GET['num'] ) ):
				$num = (int) $_GET['num'];
				if( $num > 2000)
				$num = 200;
			endif;
				
				
			$query = 'post_type='.$post_type.'&posts_per_page='.$num.'&post_status=publish'.$page;
			return self::get_cards( $query );
		
		elseif( is_singular( array( 'card', 'diy-card' ) ) ): // single post
			
			$data[0] = Phylo_Cards::get_card_array();
			return $data;
		
		elseif( is_page_template( 'my-cards-page.php' ) ):

			if( function_exists( 'STR_get_all_authors' ) ) {
				$current_user_id = STR_get_all_authors();
				if( is_array( $current_user_id ) ) {
					$current_user_id = implode(',', $current_user_id );
				}
			} else {
				$current_user_id = get_current_user_id();
			}
		
			if( $current_user_id > 0 ){
				
				$query = 'author='.$current_user_id.'&post_type=diy-card&posts_per_page='.$num.'&post_status=publish'.$page;
				return self::get_cards( $query );

			} else { // you have to be logged in to return something here
				return null;
			}
		
		return false;

		endif;

	}
	
	/**
	 * get_cards function.
	 * 
	 * @access public
	 * @param mixed $query
	 * @return void
	 */
	function get_cards(	$query ) {
		global $post;
		$the_query = new WP_Query( $query );
		
					
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$data[] = Phylo_Cards::get_card_array();
		endwhile; 
		
		wp_reset_postdata();
		return $data;
	}
	
	
	// function defination to convert array to xml
	
	/**
	 * array_to_xml function.
	 * 
	 * @access public
	 * @param mixed $data
	 * @param mixed &$xml
	 * @return void
	 */
	function array_to_xml($data, &$xml) {
	    foreach($data as $key => $value) {
	        if(is_array($value)) {
	            if(!is_numeric($key)){
	                $subnode = $xml->addChild("$key");
	                self::array_to_xml($value, $subnode);
	            }
	            else{
	                self::array_to_xml($value, $xml);
	            }
	        }
	        else {
	            $xml->addChild("$key","$value");
	        }
	    }
	}
}






/**************************************************************************************
 *  API DOCS
 **************************************************************************************
 
	url api parameters:
	api - can eather be json or xml default is json, has to be present
	num - returns the number of cards maximum is 200 default is 20
	page - page number to get to the page = 2 for second page
	callback - only applicable to json will call the callback function with the json data passed into it.
	diy - set this parameter to get back the diy cards
	if you don't have a callback parameter specified phylomon_cards will be returned;



	Urls you can visit. 
	homepage:
	so http://phyogame.com
	
	cards:
	http://http://phyogame.com/cards
	
	diy-cards:
	http://http://phyogame.com/diy-cards
	
	single card url:
	http://phyogame.com/2013/04/evening-grosbeak
	
	example urls
	http://phyogame.com/?api=json&num=200&page=1&callback=function_name&diy=1
	
	http://phyogame.com/?api=xml&num=20&page=1&diy=1
	
	http://phyogame.com/2013/04/evening-grosbeak/?api=json
	
	
	same example HTML
	
	<script>
	function get_cards(data) {
		alert('The first card is '+data[0].name );
		console.log( data[0] );
	}
	</script>


	<script src="http://local.dev/?api=json&callback=get_cards"></script>
	<!-- <script src="http://local.dev/2013/04/evening-grosbeak/?api=1&callback=get_cards"></script> -->

*/