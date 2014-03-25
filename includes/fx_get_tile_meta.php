<?php

function fx_get_tile_meta($tile_id) {

	global $wpdb; //http://codex.wordpress.org/Class_Reference/wpdb

	$tableName = $wpdb->prefix . 'bb_tile_meta';
	$tile_meta = $wpdb->get_results(
	 		"
			SELECT *
			FROM `$tableName`
			WHERE `tile_id` = '$tile_id'
	 		"
	 	);

	return $tile_meta;

}

?>