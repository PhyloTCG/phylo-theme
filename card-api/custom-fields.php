<?php
/*
 *  Install Add-ons
 *
 *  The following code will include all 4 premium Add-Ons in your theme.
 *  Please do not attempt to include a file which does not exist. This will produce an error.
 *
 *  All fields must be included during the 'acf/register_fields' action.
 *  Other types of Add-ons (like the options page) can be included outside of this action.
 *
 *  The following code assumes you have a folder 'add-ons' inside your theme.
 *
 *  IMPORTANT
 *  Add-ons may be included in a premium theme as outlined in the terms and conditions.
 *  However, they are NOT to be included in a premium / free plugin.
 *  For more information, please read http://www.advancedcustomfields.com/terms-conditions/
 */

/**
 *  Register Field Groups
 *
 *  The register_field_group function accepts 1 array which holds the relevant data to register a field group
 *  You may edit the array as you see fit. However, this may result in errors if the array is not compatible with ACF
 */

if ( function_exists( 'register_field_group' ) ) {

	register_field_group( array(
		'id' => 'acf_card-data',
		'title' => 'Card Data',
		'fields' => array(
			array(
				'key' => 'field_517c835843f9b',
				'label' => 'Card Text',
				'name' => 'card_text',
				'type' => 'wysiwyg',
				'default_value' => '',
				'toolbar' => 'basic',
				'media_upload' => 'no',
			),
			array(
				'key' => 'field_517c81a7dae65',
				'label' => 'Wikipedia URL',
				'name' => 'wiki_url',
				'type' => 'text',
				'default_value' => '',
				'formatting' => 'none',
			),

			array(
				'key' => 'field_517c81a7dae68',
				'label' => 'Encyclopedia of Life',
				'name' => 'eol_url',
				'type' => 'text',
				'default_value' => '',
				'formatting' => 'none',
			),
			array(
				'key' => 'field_517c83f74be9a',
				'label' => 'Card Colour',
				'name' => 'card_color',
				'type' => 'color_picker',
				'required' => 1,
				'default_value' => '',
			),
			array(
				'key' => 'field_517c83f74beha',
				'label' => 'Card Border  Colour',
				'name' => 'border_card_color',
				'type' => 'color_picker',
				'default_value' => '#212121',
			),
			array(
				'key' => 'field_517c66e3f7641',
				'label' => 'Name Size',
				'name' => 'name_size',
				'instructions' => ' If the name is too long you can make it smaller by making the font size smaller',
				'type' => 'select',
				'multiple' => 0,
				'allow_null' => 1,
				'choices' => array(
					''			 => 'Full Size 14px',
					'smaller-13' => 'Smaller - 13px',
					'smaller-12' => 'Even Smaller - 12px',
					'smaller-11' => 'Extra Small - 11px',
					'smaller-10' => 'Tiny Font Size - 10px',
				),
				'default_value' => '',
			),
			array(
				'key' => 'field_517c822343f97',
				'label' => 'Graphic',
				'name' => '',
				'type' => 'tab',
			),
			array(
				'key' => 'field_517c814bdae30',
				'label' => 'Graphic Source',
				'name' => 'graphic_source',
				'type' => 'radio',
				'instructions' => 'Choose Where the graphic is coming from',
				'multiple' => 0,
				'allow_null' => 0,
				'choices' => array(
					'url' => 'Graphic Url',
					'upload' => 'Upload Graphic',
				),
				'default_value' => '',
				'layout' => 'horizontal',
			),
			array(
				'key' => 'field_517c81a7dae31',
				'label' => 'Graphic URL',
				'name' => 'graphic_url',
				'type' => 'text',
				'conditional_logic' => array(
					'status' => 1,
					'rules' => array(
						array(
							'field' => 'field_517c814bdae30',
							'operator' => '==',
							'value' => 'url',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'none',
			),
			array(
				'key' => 'field_517c803c49ed5',
				'label' => 'Graphic',
				'name' => 'graphic',
				'type' => 'image',
				'conditional_logic' => array(
					'status' => 1,
					'rules' => array(
						array(
							'field' => 'field_517c814bdae30',
							'operator' => '==',
							'value' => 'upload',
						),
					),
					'allorany' => 'all',
				),
				'save_format' => 'url',
				'preview_size' => 'medium',
			),
			array(
				'key' => 'field_517c80abdae2e',
				'label' => 'Graphic Artist Name',
				'name' => 'graphic_artist_name',
				'type' => 'text',
				'default_value' => '',
				'formatting' => 'html',
			),
			array(
				'key' => 'field_517c80dddae2f',
				'label' => 'Graphic Artist URL',
				'name' => 'graphic_artist_url',
				'type' => 'text',
				'instructions' => 'Please enter the url of the artist',
				'default_value' => '',
				'formatting' => 'html',
			),
			array(
				'key' => 'field_517c80dddae2z',
				'label' => 'Graphic License',
				'name' => 'graphic_license',
				'type' => 'select',
				'multiple' => 0,
				'allow_null' => 0,
				'choices' => array(
					'by-nc-nd' => 'Attribution-NonCommercial-NoDerivs (CC BY-NC-ND)',
					'by-sa' => 'Attribution-ShareAlike (CC BY-SA)',
					'by-nc-sa' => 'Attribution-NonCommercial-ShareAlike (CC BY-NC-SA)',
					'by-nc' => 'Attribution-NonCommercial (CC BY-NC)',
					'by' => 'Attribution (CC-BY)',
					'by-nd' => 'Attribution-NoDerivs (CC BY-ND)',
				),
				'default_value' => 'by-nc-nd',
				'formatting' => 'html',
			),
			array(
				'key' => 'field_517c826d43f98',
				'label' => 'Photo',
				'name' => '',
				'type' => 'tab',
			),
			array(
				'key' => 'field_517c82f243f99',
				'label' => 'Photo Source',
				'name' => 'photo_source',
				'type' => 'radio',
				'instructions' => 'Choose where the photo is coming from',
				'multiple' => 0,
				'allow_null' => 0,
				'choices' => array(
					'url' => 'Graphic Url',
					'upload' => 'Upload Graphic',
				),
				'default_value' => '',
				'layout' => 'horizontal',
			),
			array(
				'key' => 'field_517c833943f9a',
				'label' => 'Photo URL',
				'name' => 'photo_url',
				'type' => 'text',
				'conditional_logic' => array(
					'status' => 1,
					'rules' => array(
						array(
							'field' => 'field_517c82f243f99',
							'operator' => '==',
							'value' => 'url',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'none',
			),
			array(
				'key' => 'field_517c83974be97',
				'label' => 'Photo',
				'name' => 'photo',
				'type' => 'image',
				'conditional_logic' => array(
					'status' => 1,
					'rules' => array(
						array(
							'field' => 'field_517c82f243f99',
							'operator' => '==',
							'value' => 'upload',
						),
					),
					'allorany' => 'all',
				),
				'save_format' => 'url',
				'preview_size' => 'medium',
			),
			array(
				'key' => 'field_517c83b94be98',
				'label' => 'Photo Artist Name',
				'name' => 'photo_artist_name',
				'type' => 'text',
				'default_value' => '',
				'formatting' => 'none',
			),
			array(
				'key' => 'field_517c83d54be99',
				'label' => 'Photo Artist URL',
				'name' => 'photo_artist_url',
				'type' => 'text',
				'instructions' => 'Please enter the url of the Artist',
				'default_value' => '',
				'formatting' => 'none',
			),
			array(
				'key' => 'field_517c80dddae2w',
				'label' => 'Photo License',
				'name' => 'photo_license',
				'type' => 'select',
				'multiple' => 0,
				'allow_null' => 0,
				'choices' => array(
					'by-nc-nd' 	=> 'Attribution-NonCommercial-NoDerivs (CC BY-NC-ND)',
					'by-sa' 	=> 'Attribution-ShareAlike (CC BY-SA)',
					'by-nc-sa' 	=> 'Attribution-NonCommercial-ShareAlike (CC BY-NC-SA)',
					'by-nc' 	=> 'Attribution-NonCommercial (CC BY-NC)',
					'by' 		=> 'Attribution (CC-BY)',
					'by-nd' 	=> 'Attribution-NoDerivs (CC BY-ND)',
				),
				'default_value' => 'by-nc-nd',
				'formatting' => 'html',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'card',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array(
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array(),
		),
		'menu_order' => 0,
	));

	// function for creating the custom fields for the
	register_field_group(array(
		'id' => 'acf_organism-data',
		'title' => 'Organism Data',
		'fields' => array(
			array(
				'key' => 'field_517c79b5406bd',
				'label' => 'Latin Name',
				'name' => 'latin_name',
				'type' => 'text',
				'instructions' => 'Scientific Organize Name',
				'required' => 1,
				'default_value' => '',
				'formatting' => 'none',
			),
			array(
				'key' => 'field_517c79b5496bd',
				'label' => 'Point Score',
				'name' => 'point_score',
				'type' => 'text',
				'required' => false,
				'default_value' => '',
				'formatting' => 'none',
			),
			array(
				'key' => 'field_517c75e3f7641',
				'label' => 'Diet',
				'name' => 'diet',
				'type' => 'select',
				'multiple' => 0,
				'allow_null' => 1,
				'choices' => array(
					'photosynthetic' => 'Photosynthetic - Yellow',
					'carbon-macromolecules' => 'Molecular Carbon - Black',
					'herbivore' => 'Herbivore - Green',
					'omnivore' => 'Omnivore - Brown',
					'carnivore' => 'Carnivore - Red',
				),
				'default_value' => '',
			),
			array(
				'key' => 'field_517c79f4406be',
				'label' => 'Food Chain Hierarchy',
				'name' => 'food_chain_hierarchy',
				'type' => 'select',
				'multiple' => 0,
				'allow_null' => 1,
				'choices' => array(
					1 => '1 - Autotroph',
					2 => '2 - Green',
					3 => '3 - Red; Brown',
				),
				'default_value' => '',
			),
			array(
				'key' => 'field_517c7b479fb94',
				'label' => 'Scale',
				'name' => 'scale',
				'type' => 'select',
				'multiple' => 0,
				'allow_null' => 0,
				'choices' => array(
					0 => 'none',
					1 => '1 - Virus Size',
					2 => '2 - Single Cell Size',
					3 => '3 - Multi Cell Size',
					4 => '4 - Insect Size',
					5 => '5 - Tiny Animal / Plant',
					6 => '6 - Small Animal / Plant',
					7 => '7 - Medium Animal / Plant',
					8 => '8 - Large Animal / Plant',
					9 => '9 - Giant Animal / Plant',
				),
				'default_value' => '',
			),
			array(
				'key' => 'field_517c7cb221417',
				'label' => 'Habitat 1',
				'name' => 'habitat_1',
				'type' => 'select',
				'multiple' => 0,
				'allow_null' => 0,
				'choices' => array(
					'0'		=> '------',
					'desert' => 'Desert',
					'fresh-water' => 'Fresh Water',
					'forest' => 'Forest',
					'grasslands' => 'Grassland',
					'ocean' => 'Ocean',
					'tundra' => 'Tundra',
					'urban' => 'Urban',
				),
				'default_value' => '',
			),
			array(
				'key' => 'field_517c7d6b21418',
				'label' => 'Habitat 2',
				'name' => 'habitat_2',
				'type' => 'select',
				'multiple' => 0,
				'allow_null' => 0,
				'choices' => array(
					'0'		=> '------',
					'desert' => 'Desert',
					'fresh-water' => 'Fresh Water',
					'forest' => 'Forest',
					'grasslands' => 'Grassland',
					'ocean' => 'Ocean',
					'tundra' => 'Tundra',
					'urban' => 'Urban',
				),
				'default_value' => '',
			),
			array(
				'key' => 'field_517c7dc621419',
				'label' => 'Habitat 3',
				'name' => 'habitat_3',
				'type' => 'select',
				'multiple' => 0,
				'allow_null' => 0,
				'choices' => array(
					'0'		=> '------',
					'desert' => 'Desert',
					'fresh-water' => 'Fresh Water',
					'forest' => 'Forest',
					'grasslands' => 'Grassland',
					'ocean' => 'Ocean',
					'tundra' => 'Tundra',
					'urban' => 'Urban',
					'ocean' => 'Ocean',
				),
				'default_value' => '',
			),
			array(
				'key' => 'field_517c7df02141a',
				'label' => 'Temperature',
				'name' => 'temperature',
				'type' => 'checkbox',
				'instructions' => 'Climate preference of the organism. An organism may have multiple climate preferences.',
				'multiple' => 0,
				'allow_null' => 0,
				'choices' => array(
					'cold' => 'Cold',
					'cool' => 'Cool',
					'warm' => 'Warm',
					'hot' => 'Hot',
				),
				'default_value' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'card',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array(
			'position' => 'side',
			'layout' => 'default',
			'hide_on_screen' => array(),
		),
		'menu_order' => 0,
	));

	/**
	 * ============================================================= */

	/* DIY CARD FIELDS */
	register_field_group( array(
		'id' => 'acf_diy-card-data',
		'title' => 'Card Data',
		'fields' => array(
			array(
				'key' => 'field_517c835843f9b',
				'label' => 'Card Text',
				'name' => 'card_text',
				'type' => 'wysiwyg',
				'default_value' => '',
				'toolbar' => 'basic',
				'media_upload' => 'no',
			),
			array(
				'key' => 'field_517c83f74be9a',
				'label' => 'Card Colour',
				'name' => 'card_color',
				'type' => 'color_picker',
				'required' => 1,
				'default_value' => '',
			),
			array(
				'key' => 'field_517c66e3f7641',
				'label' => 'Name Size',
				'name' => 'name_size',
				'instructions' => ' If your Animal Name is too long you can make it smaller by making the font size smaller',
				'type' => 'select',
				'multiple' => 0,
				'allow_null' => 1,
				'choices' => array(
					''			 => 'Full Size 14px',
					'smaller-13' => 'Smaller - 13px',
					'smaller-12' => 'Even Smaller - 12px',
					'smaller-11' => 'Extra Small - 11px',
					'smaller-10' => 'Tiny Font Size - 10px',
				),
				'default_value' => '',
			),

			array(
				'key' => 'field_517c814bdae30',
				'label' => 'Image Source',
				'name' => 'graphic_source',
				'type' => 'radio',
				'instructions' => 'Choose where the graphic is coming from',
				'multiple' => 0,
				'allow_null' => 0,
				'choices' => array(
					'url' => 'Graphic Url',
					'upload' => 'Upload Graphic',
				),
				'default_value' => '',
				'layout' => 'horizontal',
			),
			array(
				'key' => 'field_517c81a7dae31',
				'label' => 'Graphic URL',
				'name' => 'graphic_url',
				'type' => 'text',
				'conditional_logic' => array(
					'status' => 1,
					'rules' => array(
						array(
							'field' => 'field_517c814bdae30',
							'operator' => '==',
							'value' => 'url',
						),
					),
					'allorany' => 'all',
				),
				'default_value' => '',
				'formatting' => 'none',
			),
			array(
				'key' => 'field_517c803c49ed5',
				'label' => 'Graphic',
				'name' => 'graphic',
				'type' => 'image',
				'conditional_logic' => array(
					'status' => 1,
					'rules' => array(
						array(
							'field' => 'field_517c814bdae30',
							'operator' => '==',
							'value' => 'upload',
						),
					),
					'allorany' => 'all',
				),
				'save_format' => 'url',
				'preview_size' => 'medium',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'diy-card',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array(
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array(),
		),
		'menu_order' => 0,
	));

	// function for creating the custom fields for the
	register_field_group(array(
		'id' => 'acf_diy-organism-data',
		'title' => 'Organism Data',
		'fields' => array(
			array(
				'key' => 'field_517c79b5406bd',
				'label' => 'Latin Name',
				'name' => 'latin_name',
				'type' => 'text',
				'instructions' => 'Scientific Organize Name',
				'required' => 1,
				'default_value' => '',
				'formatting' => 'none',
			),
			array(
				'key' => 'field_517c79b5496bd',
				'label' => 'Point Score',
				'name' => 'point_score',
				'type' => 'text',
				'required' => false,
				'default_value' => '',
				'formatting' => 'none',
			),
			array(
				'key' => 'field_517c75e3f7641',
				'label' => 'Diet',
				'name' => 'diet',
				'type' => 'select',
				'multiple' => 0,
				'allow_null' => 1,
				'choices' => array(
					'photosynthetic' => 'Photosynthetic - Yellow',
					'carbon-macromolecules' => 'Molecular Carbon - Black',
					'herbivore' => 'Herbivore - Green',
					'omnivore' => 'Omnivore - Brown',
					'carnivore' => 'Carnivore - Red',
				),
				'default_value' => '',
			),
			array(
				'key' => 'field_517c79f4406be',
				'label' => 'Food Chain Hierarchy',
				'name' => 'food_chain_hierarchy',
				'type' => 'select',
				'multiple' => 0,
				'allow_null' => 1,
				'choices' => array(
					1 => '1 - Autotroph',
					2 => '2 - Green',
					3 => '3 - Red; Brown',
				),
				'default_value' => '',
			),
			array(
				'key' => 'field_517c7b479fb94',
				'label' => 'Scale',
				'name' => 'scale',
				'type' => 'select',
				'multiple' => 0,
				'allow_null' => 0,
				'choices' => array(
					0 => 'none',
					1 => '1 - Virus Size',
					2 => '2 - Single Cell Size',
					3 => '3 - Multi Cell Size',
					4 => '4 - Insect Size',
					5 => '5 - Tiny Animal / Plant',
					6 => '6 - Small Animal / Plant',
					7 => '7 - Medium Animal / Plant',
					8 => '8 - Large Animal / Plant',
					9 => '9 - Giant Animal / Plant',
				),
				'default_value' => '',
			),
			array(
				'key' => 'field_517c7cb221417',
				'label' => 'Habitat 1',
				'name' => 'habitat_1',
				'type' => 'select',
				'multiple' => 0,
				'allow_null' => 0,
				'choices' => array(
					'0'		=> '------',
					'desert' => 'Desert',
					'fresh-water' => 'Fresh Water',
					'forest' => 'Forest',
					'grasslands' => 'Grassland',
					'ocean' => 'Ocean',
					'tundra' => 'Tundra',
					'urban' => 'Urban',
				),
				'default_value' => '',
			),
			array(
				'key' => 'field_517c7d6b21418',
				'label' => 'Habitat 2',
				'name' => 'habitat_2',
				'type' => 'select',
				'multiple' => 0,
				'allow_null' => 0,
				'choices' => array(
					'0'		=> '------',
					'desert' => 'Desert',
					'fresh-water' => 'Fresh Water',
					'forest' => 'Forest',
					'grasslands' => 'Grassland',
					'ocean' => 'Ocean',
					'tundra' => 'Tundra',
					'urban' => 'Urban',
				),
				'default_value' => '',
			),
			array(
				'key' => 'field_517c7dc621419',
				'label' => 'Habitat 3',
				'name' => 'habitat_3',
				'type' => 'select',
				'multiple' => 0,
				'allow_null' => 0,
				'choices' => array(
					'0'		=> '------',
					'desert' => 'Desert',
					'fresh-water' => 'Fresh Water',
					'forest' => 'Forest',
					'grasslands' => 'Grassland',
					'ocean' => 'Ocean',
					'tundra' => 'Tundra',
					'urban' => 'Urban',
					'ocean' => 'Ocean',
				),
				'default_value' => '',
			),
			array(
				'key' => 'field_517c7df02141a',
				'label' => 'Temperature',
				'name' => 'temperature',
				'type' => 'checkbox',
				'instructions' => 'Climate preference of the organism. An organism may have multiple climate preferences.',
				'multiple' => 0,
				'allow_null' => 0,
				'choices' => array(
					'cold' => 'Cold',
					'cool' => 'Cool',
					'warm' => 'Warm',
					'hot' => 'Hot',
				),
				'default_value' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'diy-card',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array(
			'position' => 'side',
			'layout' => 'default',
			'hide_on_screen' => array(),
		),
		'menu_order' => 0,
	));



}
