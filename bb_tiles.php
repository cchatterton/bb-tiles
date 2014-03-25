<?php
/*

Plugin Name: BB Tiles
Plugin URI: http://brownbox.net.au/
Version: 1.0
Author: Chris Chatterton
Author URI: http://brownbox.net.au/
Description: Engine for isotope tiles
License: GPL2

Copyright 2013. This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation. This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

*/

// Include Core Theme Includes
require_once('bb_functions.php'); // includes fx_install_database_tables.php
// require_once('bb_options.php');
require_once('bb_tiles_templates.php');
require_once('bb_video_tiles.php');
require_once('bb_shortcodes.php');

// on activations
register_activation_hook( __FILE__, 'fx_install_database_tables' );

// link isotope
if (!function_exists('fx_isotope_head')) {
	function fx_isotope_head() {
		echo '<!-- isotope -->'."\n";
		echo '<link rel="stylesheet" href="'.plugins_url().'/bb-tiles/isotope/isotope.css" />'."\n";
		echo '<script src="'.plugins_url().'/bb-tiles/isotope/jquery.isotope.min.js"></script>'."\n";
	}
	add_action('wp_head', 'fx_isotope_head', 0 );
}

if (!function_exists('fx_isotope_footer')) {
	function fx_isotope_footer() {
		include('includes/bb_footer.php');
	}
	add_action('wp_footer', 'fx_isotope_footer');
}

// Include Custom Post Types
require_once('cpt_tiles.php');

// Include Custom Taxonomies
require_once('tax_tile_category.php');

?>