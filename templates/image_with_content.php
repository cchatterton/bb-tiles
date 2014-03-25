<?php

/*

Tile: Image Tile with content
Version: 1.1
Variation: 1.0
Author: BROWN BOX
Author URI: http://brownbox.net.au/
Description: A tile that displays the featured image with post content.
Instructions: This tile uses the post_title, featured_image and the post_content. Styles meta can be changed as required.

Meta: header_size, header_color, link_color, link_color_hover, backgroud_color, link_target
Post: post_content, post_title, featured_image

*/

// Tile Meta
// set_tile_meta($tile,$name,$type,$size,$default);

set_tile_meta('header_size','text','40%','18px');
set_tile_meta('header_color','text','40%','#ffffff');
set_tile_meta('link_color','text','40%','blue');
set_tile_meta('link_color_hover','text','40%','red');
set_tile_meta('backgroud_color','text','40%','#de564a');
set_tile_meta('link_target','text','40%','http://...');

// CSS
/*

h1 { font-size:*|header_size|*; color: *|header_color|*; font-weight: 400; }
p { color: #fff; font-size: 14px; }
a {color:*|link_color|*;}
a:hover {color:*|link_color_hover|*;}
.arrow-up { width: 0; height: 0; position: relative; margin-top: -20px; margin-left: 20px; border-left: 20px solid transparent; border-right: 20px solid transparent; border-bottom: 20px solid *|backgroud_color|*; }
{ background-color: *|backgroud_color|*; }
.tile-content {	padding: 0 30px; white-space: pre-line; }

*/

// HTML
/*
<a href="*|link_target|*">
<div class="content-with-img">
	<img src="*|featured_image|*">
	<div class="arrow-up"></div>
	<div class="tile-content">
		<h1>*|post_title|*</h1>
		<p>*|post_content|*</p>
	</div>
</div>
</a>
*/


?>