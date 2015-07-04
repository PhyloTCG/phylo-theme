<?php
$is_editing = ( isset( $_GET['id'] )  ? true : false );
$id = ( isset( $_GET['id'] )  ? $_GET['id'] : 0 );

?>
<div id="post">
		<form method="post" enctype="multipart/form-data">
			<?php echo wp_nonce_field( 'save_diy_card'.$id, '_nonce_save_diy_card' ); ?>
			<h1><?php
			if ( $id ) {
				$card_name_title = get_the_title( $id );
				echo 'Edit Card:' . $card_name_title;

			} else {
				$card_name_title = '';
				echo 'Create Your Card';
			} ?></h1>

			<?php if ( $id ) { ?>
				<input type="hidden" value="<?php echo esc_attr( $id ); ?>" name="post_id" id="post_id" />
				<input type="hidden" value="<?php echo esc_attr( Phylo_Cards::get_image_url( 'graphic', $id ) ); ?>" name="card-image" id="card-image" />
				<input type="hidden" value="<?php echo esc_attr( Phylo_Cards::get_card_background( $id ) ); ?>" id="card-background-src" />
			<?php } ?>

 			<div class="row form-row">
				<div class="col-2-3">
					<div class="padder">
						<label for="common_name">Common Name</label>
						<input type="text" name="common_name" id="common_name" value="<?php echo esc_attr( $card_name_title ); ?>"/>
					</div>

					<div class="padder">
						<label for="latin_name">Latin Name</label>
						<input type="text" name="latin_name" id="latin_name" value="<?php echo esc_attr( get_field( 'latin_name', $id ) ); ?>" />
					</div>

				</div>

				<div class="col-1-3">

					<div class="padder">
						<label for="scale">Scale</label>
						<select name="scale" class="select" id="scale" >
						    <option value="">- Select -</option>
							<option value="1" <?php selected( get_field( 'scale', $id ), 1 ); ?>>1 - Virus Size</option>
							<option value="2" <?php selected( get_field( 'scale', $id ), 2 ); ?>>2 - Single Cell Size</option>
							<option value="3" <?php selected( get_field( 'scale', $id ), 3 ); ?>>3 - Multi Cell Size</option>
							<option value="4" <?php selected( get_field( 'scale', $id ), 4 ); ?>>4 - Insect Size</option>
							<option value="5" <?php selected( get_field( 'scale', $id ), 5 ); ?>>5 - Tiny Animal / Plant</option>
							<option value="6" <?php selected( get_field( 'scale', $id ), 6 ); ?>>6 - Small Animal / Plant</option>
							<option value="7" <?php selected( get_field( 'scale', $id ), 7 ); ?>>7 - Medium Animal / Plant</option>
							<option value="8" <?php selected( get_field( 'scale', $id ), 8 ); ?>>8 - Large Animal / Plant</option>
							<option value="9" <?php selected( get_field( 'scale', $id ), 9 ); ?>>9 - Giant Animal / Plant</option>
						</select>
					</div>

					<div class="padder">
						<label for="diet">Diet</label>
						<select name="diet" id="diet" class="select" >
							<option value="null">- Select -</option>
							<option  <?php selected( get_field( 'diet', $id ), 'photosynthetic' ); ?> value="photosynthetic">Photosynthetic - Yellow</option>
							<option  <?php selected( get_field( 'diet', $id ), 'carbon-macromolecules' ); ?> value="carbon-macromolecules">Molecular Carbon - Black</option>
							<option  <?php selected( get_field( 'diet', $id ), 'herbivore' ); ?> value="herbivore">Herbivore - Green</option>
							<option  <?php selected( get_field( 'diet', $id ), 'omnivore' ); ?> value="omnivore">Omnivore - Brown</option>
							<option  <?php selected( get_field( 'diet', $id ), 'carnivore' ); ?> value="carnivore">Carnivore - Red</option>
						</select>
					</div>

					<div class="padder">
						<label for="food_chain_hierarchy">Food Chain</label>
						<select name="food_chain_hierarchy" id="food_chain_hierarchy">
							<option value="null">- Select -</option>
							<option value="1" <?php selected( get_field( 'food_chain_hierarchy', $id ), '1' ); ?> >1 - Autotroph</option>
							<option value="2" <?php selected( get_field( 'food_chain_hierarchy', $id ), '2' ); ?> >2 - Green</option>
							<option value="3" <?php selected( get_field( 'food_chain_hierarchy', $id ), '3' ); ?> >3 - Red; Brown</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row form-row">
				<div class="col-2-3">
					<div class="padder">
						<label for="card_image">Card Image</label>
						<input type="file" name="card_image" value="Upload Image" class="button" id="card_image" />
					</div>
				</div>
				<div class="col-1-3">
					<div class="padder">
						<label for="card_point_value">Point Value <span>(optional)</span></label>
						<input type="text" value="<?php echo esc_attr( get_field( 'point_score', $id ) ); ?>" name="card_point_value"  id="card_point_value" />
					</div>

					<div class="padder">
						<label for="card_colour">Card Colour</label>
						<input type="text" value="<?php echo esc_attr( get_field( 'card_color', $id ) ); ?>" name="card_colour" id="card_colour" />
					</div>
				</div>
			</div>
			<div class="row form-row">
				<div class="padder">
					<label for="card_info">Card Info</label>
					<textarea name="card_info" id="card_info"><?php echo esc_textarea( get_field( 'card_text', $id ) ); ?></textarea>
				</div>
			</div>
			<div class="row form-row">
				<div class="col-1-3">
					<div class="padder">
						<label for="habitat_1">Habitat 1</label>
						<select name="habitat_1" class="select  habitat-select" id="habitat_1" >
							<option value="">- Select -</option>
							<option <?php selected( get_field( 'habitat_1', $id ), 'desert' ); ?> value="desert">Desert</option>
							<option <?php selected( get_field( 'habitat_1', $id ), 'fresh-water' ); ?> value="fresh-water">Fresh Water</option>
							<option <?php selected( get_field( 'habitat_1', $id ), 'forest' ); ?> value="forest">Forest</option>
							<option <?php selected( get_field( 'habitat_1', $id ), 'grasslands' ); ?> value="grasslands">Grassland</option>
							<option <?php selected( get_field( 'habitat_1', $id ), 'ocean' ); ?> value="ocean">Ocean</option>
							<option <?php selected( get_field( 'habitat_1', $id ), 'tundra' ); ?> value="tundra">Tundra</option>
							<option <?php selected( get_field( 'habitat_1', $id ), 'urban' ); ?> value="urban">Urban</option>
						</select>
					</div>
				</div>

				<div class="col-1-3">
					<div class="padder">
						<label for="habitat_2">Habitat 2</label>
						<select name="habitat_2" class="select  habitat-select" id="habitat_2" >
							<option value="">- Select -</option>
							<option <?php selected( get_field( 'habitat_2', $id ), 'desert' ); ?> value="desert">Desert</option>
							<option <?php selected( get_field( 'habitat_2', $id ), 'fresh-water' ); ?> value="fresh-water">Fresh Water</option>
							<option <?php selected( get_field( 'habitat_2', $id ), 'forest' ); ?> value="forest">Forest</option>
							<option <?php selected( get_field( 'habitat_2', $id ), 'grasslands' ); ?> value="grasslands">Grassland</option>
							<option <?php selected( get_field( 'habitat_2', $id ), 'ocean' ); ?> value="ocean">Ocean</option>
							<option <?php selected( get_field( 'habitat_2', $id ), 'tundra' ); ?> value="tundra">Tundra</option>
							<option <?php selected( get_field( 'habitat_2', $id ), 'urban' ); ?> value="urban">Urban</option>
						</select>
					</div>
				</div>

				<div class="col-1-3">
					<div class="padder">
						<label for="habitat_3">Habitat 3</label>
						<select name="habitat_3" class="select habitat-select" id="habitat_3" >
							<option value="">- Select -</option>
							<option <?php selected( get_field( 'habitat_3', $id ), 'desert' ); ?> value="desert">Desert</option>
							<option <?php selected( get_field( 'habitat_3', $id ), 'fresh-water' ); ?> value="fresh-water">Fresh Water</option>
							<option <?php selected( get_field( 'habitat_3', $id ), 'forest' ); ?> value="forest">Forest</option>
							<option <?php selected( get_field( 'habitat_3', $id ), 'grasslands' ); ?> value="grasslands">Grassland</option>
							<option <?php selected( get_field( 'habitat_3', $id ), 'ocean' ); ?> value="ocean">Ocean</option>
							<option <?php selected( get_field( 'habitat_3', $id ), 'tundra' ); ?> value="tundra">Tundra</option>
							<option <?php selected( get_field( 'habitat_3', $id ), 'urban' ); ?> value="urban">Urban</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row form-row">
				<label>Climate</label>
				<ul class="inline">
				<?php
				$temperature_array = get_field( 'temperature', $id );

				$temperature = ( is_array( $temperature_array ) ? $temperature_array : array() );  ?>
					<li>
						<label>
							<input type="checkbox" <?php checked( in_array( 'cold', $temperature ) ); ?> value="cold" name="temperature[]" class="checkbox temperature" >
							Cold
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" <?php checked( in_array( 'cool', $temperature ) ); ?> value="cool" name="temperature[]" class="checkbox temperature" >
							Cool
						</label>
					</li>
					<li>
						<label>
							<input type="checkbox" <?php checked( in_array( 'warm', $temperature ) ); ?> value="warm" name="temperature[]" class="checkbox temperature" >
								Warm
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox"  <?php checked( in_array( 'hot', $temperature ) ); ?> value="hot" name="temperature[]" class="checkbox temperature" >
								Hot
							</label>
						</li>
					</ul>
			</div>

			<div class="row form-row">
			<?php if ( $id ) { ?>
				<a href="?id=<?php echo $id;?>&action=delete&_nonce=<?php echo wp_create_nonce( 'delete_card'.$id ); ?>" onclick="return confirm('Are you sure you want to delete this card?');" class="delete-card">Delete Card</a> <input type="submit" name="" value="Update Card" class="button alignright" />
			<?php } else { ?>
				<input type="submit" name="" value="Create Card" class="button alignright" />
			<?php } ?>
			</div>


		</form>
		</div>
