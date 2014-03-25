<?php

/*

Tile: Content Tile - GREEN
Version: 1.04
Variation: 1.04 for NWCC
Author: BROWN BOX
Author URI: http://brownbox.net.au/
Description: Basic tile that displays post content. Some style element available including Header size and color, and tile background color.
Instructions: This tile uses the post_tite and the post_content. Styles meta can be changed as required.

Meta: subheader, link_text, link_url
Post: post_content, post_title

*/

// Tile Meta
// set_tile_meta($tile,$name,$type,$size,$default);

set_tile_meta('subheader','text','40%','Norwest Christ...');
set_tile_meta('link_text','text','40%','Action Button');
set_tile_meta('link_url','text','40%','http://www....');

// CSS
/*

.s-texttile{background:#A3D55D;padding:30px;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;box-sizing: border-box;}
.s-texttile h1, .s-texttile h2{font-weight:100;color:rgba(255,255,255,0.95);text-shadow: rgba(0,0,0,0.1) 0px 1px 1px;margin:0px;padding:0px;}
.s-texttile h1{font-size:38px;}
.s-texttile h2{font-size:26px;}
.s-texttile p{margin-top:20px; color:white !important; text-shadow: rgba(0,0,0,0.1) 0px 1px 1px; line-height:2;font-weight:300;}
.s-texttile a{width: 100%;text-align: center;display: block;padding:20px;background: rgba(255,255,255,0.4);font-size: 20px;color:rgba(255,255,255,1);font-weight: 100;text-shadow: rgba(0,0,0,0.1) 0px 1px 1px;border: 1px solid rgba(131,164,77,0.8);border-radius: 3px;-webkit-border-radius: 3px;-webkit-transition: all 0.3s ease-in;-moz-transition: all 0.3s ease-in;-ms-transition: all 0.3s ease-in;-o-transition: all 0.3s ease-in;}
.s-texttile a:hover {background: rgba(255,255,255,0.2);color: rgba(255,255,255,1);border: 1px solid rgba(131,164,77,0.5);}
*/

// HTML
/*

<div class="s-texttile">
    <h1>*|post_title|*</h1>
    <h2>*|subheader|*</h2>
    <p>*|post_content|*</p>
    <a href="*|link_url|*">*|link_text|*</a>
</div>

*/


?>