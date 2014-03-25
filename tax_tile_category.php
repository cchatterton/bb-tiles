<?php

// find'n replace (preseve case)
// 1. Tile Categories & Tile Category (as tax)
// 2. Team (as cpt)
// Remember to include in functions.php

function tax_tile_category() {
	$labels = array(
		'name'              => _x( 'Tile Category', 'taxonomy general name' ),
		'singular_name'     => _x( 'Tile Category', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Tile Categories' ),
		'all_items'         => __( 'All Tile Categories' ),
		'parent_item'       => __( 'Parent Tile Category' ),
		'parent_item_colon' => __( 'Parent Tile Category:' ),
		'edit_item'         => __( 'Edit Tile Category' ),
		'update_item'       => __( 'Update Tile Category' ),
		'add_new_item'      => __( 'Add New Tile Category' ),
		'new_item_name'     => __( 'New Tile Category' ),
		'menu_name'         => __( 'Tile Categories' ),
	);
	$args = array(
		'labels' => $labels,
		'show_admin_column' => true,
		'hierarchical' => true,
	);
	register_taxonomy( 'tile_category', array('tile'), $args );
}
add_action( 'init', 'tax_tile_category', 0 );

// Add term page
function tax_tile_category_meta_add() {

	tax_tile_category_meta_field_add('tax_tile_category_thumb1','1st Thumbnail',$desc='Enter a URL for the tile');
	tax_tile_category_meta_field_add('tax_tile_category_thumb2','2nd Thumbnail',$desc='Enter a URL for the tile');
	tax_tile_category_meta_field_add('tax_tile_category_thumb3','3rd Thumbnail',$desc='Enter a URL for the tile');

}
add_action( 'tile category_add_form_fields', 'tax_tile_category_meta_add', 10, 2 );

function tax_tile_category_meta_field_add($name,$label,$desc='Enter a value for this field'){

	echo '<div class="form-field">'."\n";
	echo '	<label for="term_meta['.$name.']">'.$label.'</label>'."\n";
	echo '	<input type="text" name="term_meta['.$name.']" id="term_meta['.$name.']" value="">'."\n";
	echo '	<p class="description">'.$desc.'</p>'."\n";
	echo '</div>'."\n";

}

// Edit term page
function tax_tile_category_meta_edit($term) {

	// put the term ID into a variable
	$t_id = $term->term_id;

	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option( "taxonomy_$t_id" );

	tax_tile_category_meta_field_edit($term_meta,'tax_tile_category_thumb1','1st Thumbnail',$desc='Enter a URL for the tile');
	tax_tile_category_meta_field_edit($term_meta,'tax_tile_category_thumb2','2nd Thumbnail',$desc='Enter a URL for the tile');
	tax_tile_category_meta_field_edit($term_meta,'tax_tile_category_thumb3','3rd Thumbnail',$desc='Enter a URL for the tile');

}
add_action( 'tile category_edit_form_fields', 'tax_tile_category_meta_edit', 10, 2 );

function tax_tile_category_meta_field_edit($term_meta,$name,$label,$desc='Enter a value for this field'){

	echo '<tr class="form-field">'."\n";
	echo '	<th scope="row" valign="top"><label for="term_meta['.$name.']">'.$label.'</label></th>'."\n";
	echo '	<td>'."\n";
	$value = ($term_meta[$name]) ? $term_meta[$name] : '';
	echo '		<input type="text" name="term_meta['.$name.']" id="term_meta['.$name.']" value="'.$value.'">'."\n";
	echo '		<p class="description">'.$desc.'</p>'."\n";
	echo '	</td>'."\n";
	echo '</tr>'."\n";

}

// Save extra taxonomy fields callback function.
function tax_tile_category_meta_save( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );

		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][$key] ) ) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		// Save the option array.
		update_option( "taxonomy_$t_id", $term_meta );
	}
}
add_action( 'edited_tile_category', 'tax_tile_category_meta_save', 10, 2 );
add_action( 'create_tile_category', 'tax_tile_category_meta_save', 10, 2 );

?>