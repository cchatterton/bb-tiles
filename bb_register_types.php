<?php $dev = true;

global $wpdb; //http://codex.wordpress.org/Class_Reference/wpdb

// get available templates
$dir = $_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/bb-tiles/templates';
$templates = scandir($dir);

$active_tiles = array();

// get tile defintions
foreach ($templates as $template) {
	if(substr($template, strlen($template)-3)==='php') {
		include('templates/'.$template);

		// define tile -- get the attributes
		$template_name = substr($template, 0,strlen($template)-4);
		$c1 = file_get_contents($dir.'/'.$template);

		// maybe we should find the header and just run the following on that?

		$c2 = explode("|", preg_replace('/\r\n|\r|\n/m','|', $c1));

		$c4 = array();
		foreach ($c2 as $c3) if (strpos($c3, ':')>0) array_push($c4, $c3);

		$meta = array();
		$meta[filename] = $template;

	    foreach ($c4 as $c5 ) {

	    	$c6 = substr($c5, 0, strpos($c5, ':'));
	    	$c7 = substr($c5, strlen($c6)+1);

	    	$tile_meta = array('tile','version','variation','description','instructions','post','meta');
	    	foreach ($tile_meta as $m) if (strtolower($c6) == strtolower($m) ) $meta[$m] = $c7 ;

	 	}

	 	$tile_id = set_tile($meta);
	 	array_push($active_tiles, $tile_id);

	 	// set custom meta
			$c8 = array();
			foreach ($c2 as $c3) if (strpos($c3, 'set_tile_meta') !== false && (strpos($c3, '//') === false || strpos($c3, '//') > strpos($c3, 'set_tile_meta') ) ) array_push($c8, $c3);

			foreach ($c8 as $c9) {

				$c10 = strpos($c9,'(')+1;
				$c11 = strpos($c9,')');
				$c12 = substr($c9, $c10, ($c11-$c10) );

				$c12 = explode(',', str_replace("'", "", $c12) );
				set_tile_meta($c12[0],$c12[1],$c12[2],$c12[3],$tile_id);

			}

	}

}

// remove template no longer required
$tiles = fx_get_tile_templates();
foreach ($tiles as $t => $tile) {

	if ( !in_array($tile->tile_id, $active_tiles) ) {

		$wpdb->delete(
		$wpdb->prefix . 'bb_tiles',
			array(

			'tile_id' 	=> $tile->tile_id,

			)
		);

	}

}

$tableName = $wpdb->prefix . 'bb_tile_meta';
$tile_meta = $wpdb->get_results(
 		"
		SELECT *
		FROM `$tableName`
 		"
 	);
// var_dump($tile_meta);

foreach ($tile_meta as $m => $meta) {
	
	if ( !in_array($meta->tile_id, $active_tiles) ) {

		$wpdb->delete(
		$wpdb->prefix . 'bb_tile_meta',
			array(

			'tile_id' 	=> $meta->tile_id,

			)
		);

	}

}


function set_tile($meta){

 	global $wpdb; //http://codex.wordpress.org/Class_Reference/wpdb

 	$args = array(

		'filename' 	=> $meta[filename],
		'name' 		=> $meta[tile],
		'version' 	=> $meta[version],
		'variation' => $meta[variation]

 	);
 	$template_id = fx_get_tile_template_id( $args );
 	if ($template_id == NULL) {

		$wpdb->delete(
		$wpdb->prefix . 'bb_tiles',
			array(

				'filename' 	=> $meta[filename],
				'name' 		=> $meta[tile],
				'version' 	=> $meta[version],
				'variation' => $meta[variation],

			)
		);

		$wpdb->insert(
		$wpdb->prefix . 'bb_tiles',
			array(

				'filename' 		=> $meta[filename],
				'name' 			=> $meta[tile],
				'version' 		=> $meta[version],
				'variation' 	=> $meta[variation],
				'description' 	=> $meta[description],
				'instructions' 	=> $meta[instructions],
				'status' 		=> 0

			)
		);

		// get tile ID
		$tableName = $wpdb->prefix . 'bb_tiles';
		$tile_id = $wpdb->get_results(
		 		"
				SELECT `tile_id`
				FROM `$tableName`
				WHERE `filename` = '$meta[filename]'
		 		"
		 	);

		return intval($tile_id[0]->tile_id);

	} else {

		return $template_id;

	}

}

function set_tile_meta($name,$type,$size,$default,$tile_id=0){

	if($tile_id != 0) {

 	global $wpdb; //http://codex.wordpress.org/Class_Reference/wpdb

		$wpdb->delete(
		$wpdb->prefix . 'bb_tile_meta',
			array(

				'tile_id' 	=> $tile_id,
				'name' 		=> $name

			)
		);

		$wpdb->insert(
		$wpdb->prefix . 'bb_tile_meta',
			array(

				'tile_id' 	=> $tile_id,
				'name' 		=> $name,
				'type' 		=> $type,
				'size' 		=> $size,
				'default' 	=> $default

			)
		);

	 // get meta ID
	 $tableName = $wpdb->prefix . 'bb_tile_meta';
	 $meta_id = $wpdb->get_results(
	 		"
			SELECT `meta_id`
			FROM `$tableName`
			WHERE `tile_id` = $tile_id
			AND `name` = '$name'
	 		"
	 	);

		// echo '<pre>';
		// var_dump( intval( $meta_id[0]->meta_id ) );
		// echo ' '.$name.', '.$type.', '.$size.', '.$default.', '.$tile_id.'<br>';
		// echo '</pre>';

	}

}

?>