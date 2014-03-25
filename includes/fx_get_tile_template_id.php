<?php

function fx_get_tile_template_id($args) {

	extract($args);

	if (!$filename || !$name || !$version || !$variation ) return false;

 	global $wpdb; //http://codex.wordpress.org/Class_Reference/wpdb
	 $tableName = $wpdb->prefix . 'bb_tiles';
	 $template = $wpdb->get_results(
	 		"
			SELECT `tile_id`
			FROM `$tableName`
			WHERE `filename` 	= '$filename'
			AND   `name` 		= '$name'
			AND   `version` 	= '$version'
			AND   `variation` 	= '$variation'	
	 		"
	 	);

	return $template[0]->tile_id;

}

?>