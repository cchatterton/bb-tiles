<?php

/*

Tile: Content Tile
Version: 1.02
Variation: 1.04 for NWCC
Author: BROWN BOX
Author URI: http://brownbox.net.au/
Description: Basic tile that displays post content. Some style element available including Header size and color, and tile background color.
Instructions: This tile uses the post_tite and the post_content. Styles meta can be changed as required.

Meta: header_size, header_color, link_color, link_color_hover, tile_padding, 
Post: post_content, post_title

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

*/

// HTML
/*

<div>
<h1>*|post_title|*</h1>
<p>*|post_content|*</p>
</div>

*/


?>