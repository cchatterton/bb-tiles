<?php

/*

Tile: Testimonial Tile - Buff
Version: 1.0
Variation: 1.01 for NWCC
Author: BROWN BOX
Author URI: http://brownbox.net.au/
Description: Basic tile that displays post content. Some style element available including Header size and color, and tile background color.
Instructions: This tile uses the post_tite and the post_content. Styles meta can be changed as required.

Meta: 
Post: post_content, post_title

*/

// Tile Meta
// set_tile_meta($tile,$name,$type,$size,$default);

// CSS
/*
div{background:#F3DAB1;padding:30px;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;box-sizing: border-box;}
p{margin-top:20px; color:#706258 !important; text-shadow: rgba(0,0,0,0.1) 0px 1px 1px;}
sub {font-weight:400; font-style:normal !important;margin-top:20px; color:#706258 !important; text-shadow: rgba(0,0,0,0.1) 0px 1px 1px;}
*/

// HTML
/*

<div>
    <p>*|post_content|*</p>
        <sub>*|post_title|*</sub>
</div>

*/


?>