<?php

/*

Tile: Content Tile with Hero
Version: 1.0
Variation: 1.0
Author: BROWN BOX
Author URI: http://brownbox.net.au/
Description: Basic tile that displays post content with a Hero. Some style element available including Header size and color, and tile background color.
Instructions: This tile uses the post_title, featured_image and the post_content. Styles meta can be changed as required.

Meta: header_size, header_color, link_color, link_color_hover, tile_padding, 
Post: post_content, post_title, featured_image

*/

// Tile Meta
// set_tile_meta($tile,$name,$type,$size,$default);

set_tile_meta('header_size','text','40%','20px');
set_tile_meta('header_color','text','40%','#346025');
set_tile_meta('link_color','text','40%','blue');
set_tile_meta('link_color_hover','text','40%','red');
set_tile_meta('tile_padding','text','40%','10px');

// CSS
/*

h1 {font-size:*|header_size|*; color: *|header_color|*;}
a {color:*|link_color|*;}
a:hover {color:*|link_color_hover|*;}
div {padding:*|tile_padding|*;}
img {width:100%;}
.content-with-img {background-color:#de564a;padding:0 !important}
.content-with-img h1 {color:#FFFFFF}
.content-with-img p {color:#FFFFFF !important;}
.content-with-img p a {color:#FFFFFF !important;text-decoration:underline;}
.arrow-up {width: 0;height: 0;position: relative;margin-top: -20px;margin-left: 20px;border-left: 20px solid transparent;border-right: 20px solid transparent;border-bottom: 20px solid #df564a;}
*/

// HTML
/*

<div class="content-with-img">
	<img src="*|featured_image|*">
	<div class="arrow-up"></div>
	<div class="tile-content">
		<h1>*|post_title|*</h1>
		<p>*|post_content|*</p>
	</div>
</div>

*/


?>