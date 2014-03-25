<?php get_header(); ?>

<?php 

remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );

// echo '<style>'."\n";
// echo '	.tile p a:parent {margin-bottom: 0;}'."\n";
// echo '</style>'."\n";

$p_id = get_the_id();
$tile_id = get_post_meta( $p_id, 'bb_tile_type', true );
$filename = fx_get_tile_filename( $tile_id );
$dir = $_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/bb-tiles/templates';
$template = file_get_contents($dir.'/'.$filename);

// extract meta and post attributes
$header = bb_extract_content('',$template);
foreach ($header as $line) {
	$line = explode(':', $line);
	if ( strtolower( $line[0] ) == 'meta') $get_meta = $line[1];
	if ( strtolower( $line[0] ) == 'post') $get_post = $line[1];
}

$get_meta = explode(',', $get_meta);  // this is a array
$get_post = explode(',', $get_post);  // this is a array

// extract css
$css = bb_extract_content('css',$template); // this is a array

// extract html
$html = bb_extract_content('html',$template);  // this is a array

// replace meta attributes
foreach ($get_meta as $attribute) {
	$attribute = trim( $attribute );

	$css = bb_replace_attributes($p_id,$tile_id,$css,$attribute);
	$html = bb_replace_attributes($p_id,$tile_id,$html,$attribute);
}

// replace post attributes
foreach ($get_post as $attribute) {
	$attribute = trim( $attribute );

	$post = get_post($p_id);
	// echo '<pre>';
	// var_dump(strpos( $meta, '[' ));
	// echo '</pre>';

	switch ($attribute) {
		
		case 'featured_image':
			$meta = wp_get_attachment_image_src( get_post_thumbnail_id($p_id), 'full' );
			$meta = $meta[0];
			break;

		default:
			$meta = $post->$attribute;
			break;

	}

	// var_dump($meta);
	$css = bb_replace_attributes($p_id,$tile_id,$css,$attribute,$meta);
	$html = bb_replace_attributes($p_id,$tile_id,$html,$attribute,$meta);

}

function bb_extract_content($data,$template) {

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

function bb_replace_attributes($p_id,$tile_id,$source,$attribute,$meta=''){

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

// display tile
echo '<style>'."\n";
foreach ($css as $line) echo '	.tile-'.$tile_id.'-'.$p_id.' '.$line."\n";
echo '</style>'."\n";
echo '<div class="tile tile-'.$tile_id.'-'.$p_id.'" style="width: 600px;margin:20px;border:1px solid#cecece;">'."\n";
echo '	<p style="display:none;" class="number"></p>'."\n";

$paragraphs = get_post_meta( $p_id, 'bb_tile_paragraphs', true );

foreach ($html as $line) {

	if (strpos( $line, '[' ) == 0 ) {

		// $shortcode = substr( $meta, strpos( $meta, '[' )+1 , ( strpos( $meta, ']' ) - strpos( $meta, '[' ) - 1 ) );
		$line = _e( do_shortcode( $line ) );

	} else {

		if ( $paragraphs=='true' ) echo '	'.wpautop($line)."\n";
		if ( $paragraphs!='true' ) echo '	'.$line."\n";

	}

}
echo '</div>'."\n";

?>

<?php get_footer(); ?>
