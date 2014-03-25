<?php

function my_custom_post_tile() {
	$labels = array(
		'name'               => _x( 'Tiles', 'post type general name' ),
		'singular_name'      => _x( 'Tile', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'Tile' ),
		'add_new_item'       => __( 'Add New Tile' ),
		'edit_item'          => __( 'Edit Tile' ),
		'new_item'           => __( 'New Tile' ),
		'all_items'          => __( 'All Tiles' ),
		'view_item'          => __( 'View Tiles' ),
		'search_items'       => __( 'Search Tiles' ),
		'not_found'          => __( 'No Tile found' ),
		'not_found_in_trash' => __( 'No Tile found in the Trash' ),
		'parent_item_colon'  => '',
		'menu_name'          => 'Tiles'
	);

	$args = array(
		'labels'        => $labels,
		'description'   => 'Holds our tile',
		'public'        => true,
		'menu_position' => 20,
	// 'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
		'supports'      => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'excerpt'),
		'has_archive'   => true,
	);
	register_post_type( 'tile', $args );
}
add_action( 'init', 'my_custom_post_tile' );

// Styling for the custom post type icon
function my_tile_icon() {

	$menu = site_url().'/wp-admin/images/menu.png';
	$icon = site_url().'/wp-admin/images/icons32.png';

	echo '<style type="text/css" media="screen">'."\n";
	echo '	#menu-posts-tile .wp-menu-image { background: url('.$menu.') no-repeat 1px -33px!important; }'."\n";
	echo '	#menu-posts-tile:hover .wp-menu-image {background: url('.$menu.') no-repeat 1px -1px!important; }'."\n";
	echo '	#icon-edit.icon32-posts-tile {background: url('.$icon.') no-repeat -12px -5px;}'."\n";
	echo '  #tile_value {width: 50%;}'."\n";
	echo '</style>'."\n";

}
add_action( 'admin_head', 'my_tile_icon' );

// Set Messages
function tile_updated_messages( $messages ) {
//http://codex.wordpress.org/Function_Reference/register_post_type

		global $post, $post_ID;

		$messages['tile'] = array(
				0 => '', // Unused. Messages start at index 1.
				1 => sprintf( __('Tile updated.', 'your_text_domain'), esc_url( get_permalink($post_ID) ) ),
				2 => __('Custom field updated.', 'your_text_domain'),
				3 => __('Custom field deleted.', 'your_text_domain'),
				4 => __('Tile updated.', 'your_text_domain'),
				/* translators: %s: date and time of the revision */
				5 => isset($_GET['revision']) ? sprintf( __('Tile restored to revision from %s', 'your_text_domain'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				6 => sprintf( __('Tile published. <a href="%s">View Tile</a>', 'your_text_domain'), esc_url( get_permalink($post_ID) ) ),
				7 => __('Tile saved.', 'your_text_domain'),
				8 => sprintf( __('Tile submitted. <a target="_blank" href="%s">Preview Tile</a>', 'your_text_domain'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
				9 => sprintf( __('Tile scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Tile</a>', 'your_text_domain'),
						// translators: Publish box date format, see http://php.net/date
						date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
				10 => sprintf( __('Tile draft updated. <a target="_blank" href="%s">Preview Tile</a>', 'your_text_domain'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		);

		return $messages;
}
add_filter( 'post_updated_messages', 'tile_updated_messages' );

function tile_admin_bar() {
	global $wp_admin_bar;
	$wp_admin_bar->add_menu( array(
		'parent' => 'wp-logo', // use 'false' for a root menu, or pass the ID of the parent menu
		'id' => 'about_tile', // link ID, defaults to a sanitized title value
		'title' => __('About Brown Box'), // link title
		'href' => 'http://brownbox.net.au', // name of file
		'meta' => false // array of any of the following options: array( 'html' => '', 'class' => '', 'onclick' => '', target => '', title => '' );
	));
}
add_action( 'wp_before_admin_bar_render', 'tile_admin_bar' );

function tile_meta_box() {
	add_meta_box(
		'tile_meta_box',
		__( 'Tile Meta', 'myplugin_textdomain' ),
		'tile_meta_box_content',
		'tile',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'tile_meta_box' );


function tile_meta_box_content( $post ) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'tile_meta_box_content_nonce' );

	echo '<table class="form-table">'."\n";

	// each of the following must be referenced in ..._meta_box_save()

	// tile type
	if ( get_post_meta( $_GET[post], 'bb_tile_type', true )==false ) {
		$style = 'border: 1px solid rgba(255, 0, 50, 0.4);background:rgba(255, 0, 50, 0.1);';
	} else {
		$style = 'border: 1px solid rgba(0, 255, 50, 0.4);background:rgba(0, 255, 50, 0.1);';
	}
	echo '	<tr valign="top" >'."\n";
	echo '  	<td style="padding:5px 0px;">'."\n";
	echo '			<sub style="color:rgba(0,0,0,0.75);display:block;">Tile Type</sub>'."\n";
	echo '			<select name="bb_tile_type" style="'.$style.'">'."\n";
	echo '<option value="_" >Please Select...</option>'."\n";

	$tiles = fx_get_tile_templates();

	// sort tile templates before printing them to the list option

	function cmp($a, $b){

    return strcmp(ucfirst(strtolower(trim($a->name))), ucfirst(strtolower(trim($b->name))));
    }
    usort($tiles, "cmp");

	foreach ($tiles as $t => $tile) {

		$selected = ( get_post_meta( $_GET[post], 'bb_tile_type', true )==$tile->tile_id ) ? 'selected="selected"' : '' ;
		echo '<option value="'.$tile->tile_id .'" '.$selected.' >'.$tile->name .'</option>'."\n";

	}

	echo '			</select>'."\n";
	echo '  	</td>'."\n";
	echo '	</tr>'."\n";

	// get tile instructions
	//$tiles = fx_get_tile_templates();
	foreach ($tiles as $t => $tile) {
		if ( $tile->tile_id == get_post_meta( $_GET[post], 'bb_tile_type', true ) ) {
			echo '	<tr valign="top" >'."\n";
			echo '  	<td style="padding:10px 0;">'."\n";
			echo ' 		<p style="width: 90%;">'.$tile->instructions.'</p>'."\n";
			echo '  	</td>'."\n";
			echo '	</tr>'."\n";
		}
	}



	$tile_id = get_post_meta( $_GET[post], 'bb_tile_type', true );
	$tile_meta = fx_get_tile_meta($tile_id);

	foreach ( $tile_meta as $meta ) tile_meta_field( $meta->type, $meta->name, 'bb_tile_meta_'.$meta->name ,$meta->size, $meta->default, true );

	tile_meta_field('checkbox','paragraphs','bb_tile_paragraphs','100%','checkbox');
	// tile_meta_field('checkbox','featured','bb_tile_featured','100%','checkbox');
	tile_meta_field('text','priority','bb_tile_priority','65%','text');
	tile_meta_field('text','expires','bb_tile_expires','35%','dd/mm/yyy');

	echo '</table>'."\n";

}
add_action( 'save_post', 'tile_meta_box_save' );

function tile_meta_field($type,$title,$name,$size,$placeholder,$default=false){

	$meta_value = get_post_meta( $_GET[post], $name, true );
	if (strlen($meta_value)<1 && $default==true) {
		$meta_value = $placeholder;
		update_post_meta( $post_id, $name, $meta_value );
	}

	$title = ucfirst(strtolower($title));

	switch ($type) {

		case 'checkbox':

			$checked = ( $meta_value=='true') ? 'checked="'.get_option($name) .'"' : '' ;

			echo '	<tr valign="top" >'."\n";
			echo '  	<td style="padding:10px 0;">'."\n";
			echo ' 		<input type="checkbox" name="'.$name.'" value="true" '.$checked.' style="margin: 0 5px 0 0;"/><sub style="color:rgba(0,0,0,0.75);">'.$title.'</sub>'."\n";
			echo '  	</td>'."\n";
			echo '	</tr>'."\n";

			break;

		case 'text':
		default:

			echo '	<tr valign="top" >'."\n";
			echo '  	<td style="padding:5px 0px;">'."\n";
			echo '		<sub style="color:rgba(0,0,0,0.75);display:block;">'.$title.'</sub>'."\n";
			echo '   	   <input type="'.$type.'" id="'.$name.'" name="'.$name.'" style="display:block;width:'.$size.';" placeholder="'.$placeholder.'" value="'.esc_attr( $meta_value ).'" />'."\n";
			echo '  	</td>'."\n";
			echo '	</tr>'."\n";

			break;

	}
}

function tile_meta_box_save( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	if ( !wp_verify_nonce( $_POST['tile_meta_box_content_nonce'], plugin_basename( __FILE__ ) ) )
	return;

	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
		return;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ) )
		return;
	}

	$custom_meta_feilds = array('bb_tile_paragraphs','bb_tile_featured','bb_tile_priority','bb_tile_expires','bb_tile_type');
	foreach ( $custom_meta_feilds as $custom_meta_feild ) {
		$custom_meta_value = $_POST[$custom_meta_feild];
		update_post_meta( $post_id, $custom_meta_feild, $custom_meta_value );
	}

	$tile_id = $_POST['bb_tile_type'];
	$tile_meta = fx_get_tile_meta($tile_id);

		foreach ($tile_meta as $meta) {
			$custom_meta_feild = 'bb_tile_meta_'.$meta->name;
			$custom_meta_value = $_POST[$custom_meta_feild];
			//var_dump($custom_meta_feild);var_dump($custom_meta_value);
			update_post_meta( $post_id, $custom_meta_feild, $custom_meta_value );

		}


}

function get_tile_template( $single_template ) {
     global $post;

     if ($post->post_type == 'tile') {
          $single_template = dirname( __FILE__ ) . '/single-tile.php';
     }
     return $single_template;
}
add_filter( 'single_template', 'get_tile_template' );

?>