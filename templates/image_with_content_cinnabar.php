<?php

/*

Tile: Image Tile with Content - CINNABAR
Version: 1.12
Variation: 1.06 for nwcc
Author: BROWN BOX
Author URI: http://brownbox.net.au/
Description: A tile that displays the featured image with post content.
Instructions: This tile uses the post_title, featured_image and the post_content. Styles meta can be changed as required.

Meta: link_target
Post: post_content, post_title, featured_image

*/

// Tile Meta
// set_tile_meta($tile,$name,$type,$size,$default);

set_tile_meta('link_target','text','90%','http://www.norwest.nsw.edu.au/');

// CSS
/*
h1 {font-size: 24px; color: #fff; font-weight: 300;}
p {color: #fff; font-size: 14px;}
.arrow-up {width: 0; height: 0; position: relative; margin-top: -19px; margin-left: 20px; border-left: 20px solid transparent;  border-right: 20px solid transparent; border-bottom: 20px solid #E94E3C;}
.tile-content {background-color: #E94E3C; padding: 10px 30px; white-space: pre-line;}
.content-with-img {background-color: #E94E3C;}

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