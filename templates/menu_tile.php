<?php

/*

Tile: Menu Tile
Version: 1
Variation: 1.01 for NWCC
Author: BROWN BOX
Author URI: http://brownbox.net.au/
Description: Basic tile that displays post content. 
Instructions: This tile uses the post_tite

Post: post_title, featured_image, guid

*/

// Tile Meta
// set_tile_meta($tile,$name,$type,$size,$default);

// CSS
/*
h1 {font-size: 24px; color: #706258; font-weight: 300;}
p {color: #706258; font-size: 14px;}
.arrow-up {width: 0; height: 0; position: relative; margin-top: -19px; margin-left: 20px; border-left: 20px solid transparent;  border-right: 20px solid transparent; border-bottom: 20px solid #D8D1C9;}
.tile-content {background-color: #D8D1C9; padding: 10px 30px; white-space: pre-line;}
.content-with-img {background-color: #D8D1C9;}

*/

// HTML
/*
<a href="*|guid|*">
<div class="content-with-img">
	<img src="*|featured_image|*">
	<div class="arrow-up"></div>
	<div class="tile-content">
		<h1>*|post_title|*</h1>
	</div>
</div>
</a>
*/


?>