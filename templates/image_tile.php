<?php

/*

Tile: Image Tile
Version: 1.0
Variation: 1.0
Author: BROWN BOX
Author URI: http://brownbox.net.au/
Description: A tile that displays the featured image. If clicked this tile takes to  user to a new page as defined in post_content
Instructions: This tile uses the featured_image. Add a URL to post content. 

Meta: background
Post: featured_image, post_content

*/

// Tile Meta
// set_tile_meta($tile,$name,$type,$size,$default);

set_tile_meta('background','text','40%','#de564a');

// CSS
/*

a {display:block;background:*|background|*;text-align:center;}

*/

// HTML
/*
<a href="*|post_content|*">
	<img src="*|featured_image|*">
</a>

*/


?>