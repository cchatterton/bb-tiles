<?php

function fx_get_tile_filename( $tile_id ) {

 	global $wpdb; //http://codex.wordpress.org/Class_Reference/wpdb
	
	$tableName = $wpdb->prefix . 'bb_tiles';
	$template = $wpdb->get_results(
	 		"
			SELECT `filename`
			FROM `$tableName`
			WHERE `tile_id` 	= '$tile_id'
	 		"
	 	);

	return $template[0]->filename;

}

?>