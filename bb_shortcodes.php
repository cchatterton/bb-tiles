<?php

/* Here's where we need to get tiles based on shortcode and meta */
// use: [tiles id='' categories='' menus='' images='' children='1' type='']
// children=1 ... 1 level
// childrem=all ... all levels

function bb_tiles_get_tiles( $atts ) {

	// var_dump($atts);

	


	extract( $atts );
	
	// echo '<pre>';
	// var_dump( $children );
	// echo '</pre>';

	if (!$id && !$categories && !$menus && !$images && !$children) return;

	// So we need to get a set of tiles from the DB, let's do that first....
	$tiles_array = array();

	// $id
	if($id && $id!='') {

		if ( !is_array( $id ) ) { // accepts id = array(1,2,3)
			if ( strpos( $id , ',' ) ) {
				$id = explode( ',', $id ); // accepts id='1,2,3'
			} else {
				$id = array( $id ); // accepts id='1'
			}

		}
		foreach ( $id as $p_id ) {
			// var_dump($p_id);
			$tile_post = get_post( $p_id ); 
			$tile_post->for = 'id';
			array_push($tiles_array, $tile_post);
		}
	}

	// $categories
	if($categories && $categories!='') {
		if (!$args) {
			$args = array(
				'posts_per_page'   => -1,
				'tile_category'	   => $categories,
				'post_type'        => 'tile',
				'post_status'      => 'publish',
				); 
		}
		$tile_posts = get_posts( $args );
		// var_dump( count( $tile_posts ) );
		foreach ($tile_posts as $tile ) {
			$tile->for = 'categories';
			array_push( $tiles_array, $tile );
		}
	}
	// var_dump( count( $tiles_array ) );

	// $images
	if($images && $images!='') {
		// var_dump('images: '.$images);
		if (!$args) {
			$args = array(
				'posts_per_page'   	=> -1,
				'image_category'	=> $images,
				'post_type'        	=> 'attachment',
				'post_status'      	=> 'null',
				); 
		}
		$tile_posts = get_posts( $args );

		// echo '<pre>';
		// var_dump( $tile_posts );
		// echo '</pre>';

		//var_dump( count( $tile_posts ) );
		foreach ($tile_posts as $tile ) {
			$tile->for = 'images';
			array_push( $tiles_array, $tile );
		}
	}
	// var_dump( count( $tiles_array ) );

	// $menus
    $menu_name = $menus;
    if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
		$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );

		$menu_items = wp_get_nav_menu_items($menu->term_id);

		foreach ($menu_items as $menu_item) {
			$menu_page = get_post( $menu_item->object_id );
			$menu_page->for = 'menus';
			array_push( $tiles_array, $menu_page );
		}

	}

	// $children
	$all_wp_pages = get_posts( array('post_type' => 'page', 'posts_per_page' => -1));
	$child_posts = get_page_children( get_the_id(), $all_wp_pages );

	// var_dump( $children );

	$child_tile_ids = array();

	if ( $children == '1' ) {
		foreach ( $child_posts as $child ) if ( $child->post_parent == get_the_id() ) array_push( $child_tile_ids, $child->ID );
	} 

	if ( $children == 'all' ) {
		foreach ( $child_posts as $child) array_push( $child_tile_ids, $child->ID );
	} 

	foreach ( $child_tile_ids as $child_tile_id ) {
		$child_page = get_post( $child_tile_id );
		$child_page->for = 'children';
		array_push( $tiles_array, $child_page );
	}

	remove_filter( 'the_content', 'wpautop' );
	remove_filter( 'the_excerpt', 'wpautop' );

	//Call function that sorts tiles

	$tiles_array = Extend_tiles::fx_bb_sort_tiles($tiles_array);


	// create array of ids
	// sort ids
	// sort mutlidimensiona array by retunred array?

	

	if ( !$pos || $pos == 'start' ) echo '<div class="" id="container">'."\n";

	
	
	foreach ($tiles_array as $tile) {
		

		// $p_id = $tiles_array->$tiles_id
		$p_id = $tile[0];
		

		switch ($tile[1]) {
			case 'menus':
				$tile_id = 3325;
				break;

			case 'images':
				$tile_id = 3326;
				break;

			case 'children':
				$tile_id = 3325;
				break;
			
			default:
				$tile_id = get_post_meta( $p_id, 'bb_tile_type', true );
				break;
		}

		if( $type ) $tile_id = $type;
		
		$filename = fx_get_tile_filename( $tile_id );
		$dir = $_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/bb-tiles/templates';
		$template = file_get_contents($dir.'/'.$filename);


		// extract meta and post attributes
		$header = bb_extract_content_shortcodes('',$template);
		foreach ($header as $line) {
			$line = explode(':', $line);
			if ( strtolower( $line[0] ) == 'meta') $get_meta = $line[1];
			if ( strtolower( $line[0] ) == 'post') $get_post = $line[1];
		}

		$get_meta = explode(',', $get_meta);  // this is a array
		$get_post = explode(',', $get_post);  // this is a array


		// extract css
		$css = bb_extract_content_shortcodes('css',$template); // this is an array
		// extract html
		$html = bb_extract_content_shortcodes('html',$template);  // this is an array

		// replace meta attributes
		foreach ($get_meta as $attribute) {
			$attribute = trim( $attribute );

			$css = bb_replace_attributes_shortcodes($p_id,$tile_id,$css,$attribute);
			$html = bb_replace_attributes_shortcodes($p_id,$tile_id,$html,$attribute);
		}
		$dump=array();
		// replace post attributes


		foreach ($get_post as $attribute) {
			$attribute = trim( $attribute );
			$post = get_post($p_id);
			switch ($attribute) {
				case 'featured_image':
					$meta = wp_get_attachment_image_src( get_post_thumbnail_id($p_id), 'large' );
					$meta = $meta[0];
					break;

				case 'permalink':
					$meta = get_permalink( $p_id );
					break;

				case 'guid':
					// var_dump($tile_id);
					$meta = ( $tile_id == 3325 ) ? get_permalink( $p_id ) : $post->$attribute ;
					break;

				default:
					$meta = $post->$attribute;
					break;
			}
			$css = bb_replace_attributes_shortcodes($p_id,$tile_id,$css,$attribute,$meta);
			$html = bb_replace_attributes_shortcodes($p_id,$tile_id,$html,$attribute,$meta);

		}
		// var_dump($dump);
		// die();

		// var_dump($tile_id.'<br>');

		// display tile

		echo '<div class="tile element tile-'.$tile_id.'-'.$p_id.'" style="width: 300px;margin:0px;">'."\n";
		echo '	<style>'."\n";
		foreach ($css as $line) echo '	 .tile-'.$tile_id.'-'.$p_id.' '.$line."\n";
		echo '	</style>'."\n";
		echo '	<p style="display:none;" class="number"></p>'."\n";
		echo '	<!-- HTML -->'."\n";
		$paragraphs = get_post_meta( $p_id, 'bb_tile_paragraphs', true );

		foreach ($html as $line) {

			if (strpos( $line, '[' ) == 0 ) {

				// $shortcode = substr( $meta, strpos( $meta, '[' )+1 , ( strpos( $meta, ']' ) - strpos( $meta, '[' ) - 1 ) );
				echo do_shortcode( $line );

			} else {

				if ( $paragraphs=='true' ) echo '	'.wpautop($line)."\n";
				if ( $paragraphs!='true' ) echo '	'.$line."\n";

			}

		}

		echo '	<!-- HTML END-->'."\n";
		echo '</div><!-- close elementy div -->'."\n"; 


	} // END ..................................

	if ( !$pos || $pos == 'end' ) echo '</div> <!-- close container div -->'."\n";

	// var_dump($output);
	// die();
	// return $output;

	// var_dump($tiles_array);
	// echo "</pre>";
	// return "id = {$id} & cats = {$cats}";
}
add_shortcode( 'tiles', 'bb_tiles_get_tiles' );




// HERE ARE THE FUNCTIONS WE'RE HAVING TO REPEAT HERE
if(!function_exists('bb_extract_content_shortcodes')) {
    function bb_extract_content_shortcodes($data,$template) {
		if ($data=='') {
			$start = strpos( $template, '' );
		} else {
			$start = strpos( $template, '// '.strtoupper($data) );
		}
		$start = strpos( $template, '/*', $start );
		$end = strpos( $template, '*/', $start );
		$temp = substr( $template, $start, $end-$start );
		$temp = explode( "~~", preg_replace( '/\r\n|\r|\n/m','~~', $temp) );

		$data = array();
		foreach ( $temp as $line ) if ( strlen( $line )>0 && substr( $line,0,2 ) != '/*' && substr( $line,0,2 ) != '*/' ) array_push($data, $line);
		
		return $data;

	}
}



if(!function_exists('bb_replace_attributes_shortcodes')) {
    
	    function bb_replace_attributes_shortcodes($p_id,$tile_id,$source,$attribute,$meta=''){

		if ( $meta=='' ) {
			$attribute_value = get_post_meta( $p_id, 'bb_tile_meta_'.$attribute, true );
		} else {
			$attribute_value = $meta;
		}
			
		// source is an array!
		$results = array();
		foreach ($source as $line) array_push( $results, str_replace( '*|'.$attribute.'|*', $attribute_value, $line ) );
			
		return $results;


	}
}
/************************************************  Class Area  *******************************************************************/
//Class to contain all extension functionalities to tiles engine.
class Extend_tiles{

function fx_bb_sort_tiles($tiles_array=array()){

// set up temp arrays
// these will be discarded later


$priorities =array();
$metas=array();
$shuffledmetas=array();



$featuredtile_data=$tiles_array;


// build priority_key array & asign prioty meta to $post

foreach ($featuredtile_data as $post) {

 // Initialize an array to hold tomporarly each meta value of a tile and its tile type.
// priority not assigned, then 99999 will be the default
 	$metaValue=array();

 	$meta = ( get_post_meta( $post->ID, 'bb_tile_priority', true ) ) ? get_post_meta( $post->ID, 'bb_tile_priority', true ) : '99999' ;
 	
 	$metaValue["".$meta.""][0]=$post->ID;
 	$metaValue["".$meta.""][1]=$post->for;
 	
 	//push the temporal array of meta data into metas array
 	array_push($metas, $meta);
   
   //keep together each meta with its parent post id and meta type
    $priorities[]=$metaValue;
}


//Filter meta values to keep unique values
$metas=array_unique($metas);

//Initialize an array to hold ordered meta values, sort the meta values and then push one by one into the array with ordered metas 
$metasorder=array();
if(asort($metas)){
foreach ($metas as $value) {
	array_push($metasorder,$value);
}
}


//loop into the ordered metas, keep the current meta into a temporal array declared in ordered metas loop so that it initializes for
// every new cursor,



	foreach ($metasorder as $value) {
	

		//loop into priorities array to search for all post ids associated to the current meta
//var_dump($value);
			$tmp=array();
	foreach ($priorities as $priority) {	

		
		// remove null values
		if($priority[$value] !=null && $priority[$value] !=""){
    
	//push the fetched post ids into the temporal array
			
			
		array_push($tmp,$priority[$value]);


		}
		 
		 
	}

//Shuffle or randomize the temporal array before it gets re-initialized.
 shuffle($tmp);



	//push the shuffled temporal array into shuffled array to keep them permanently before re-initialization of the temp array
	foreach ($tmp as $value) {
	array_push($shuffledmetas, $value);
	}
	
	
	

	}
//var_dump($shuffledmetas);
	return $shuffledmetas;


}

} // End of Class Extend_tiles



?>