<?php

function fx_get_tile_templates() {

 	global $wpdb; //http://codex.wordpress.org/Class_Reference/wpdb
	$tableName = $wpdb->prefix . 'bb_tiles';
	$tiles = $wpdb->get_results(
	 		"
			SELECT *
			FROM `$tableName`
	 		"
	 	);

	return $tiles;

}

?>