<?php

/*

Tile: Video Tile
Version: 1.0
Variation: 1.0
Author: BROWN BOX
Author URI: http://brownbox.net.au/
Description: Basic video tile

Meta: media_type, resource_url
Post: post_content, post_title, featured_image, post_id

*/

// Tile Meta
// set_tile_meta($tile,$name,$type,$size,$default);

set_tile_meta('media_type','text','40%','Media Type');
set_tile_meta('resource_url','text','40%','Resource URL');

// CSS
/*

.s-video{width:600px;height:300px;background:url(*|featured_image|*) no-repeat center center fixed; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover;  background-size: cover; }

*/

// HTML
/*

<div class="s-video">
<a href="#" data-reveal-id="myModal" data-reveal style="width:100%;height:100%;display:block;">
</a>
</div>

<div id="myModal" class="reveal-modal">
  <h2>Awesome. I have it.</h2>
  <p class="lead">Your couch.  It is mine.</p>
  <p>Im a cool paragraph that lives inside of an even cooler modal. Wins</p>
  <p>Im a cool paragraph that lives inside of an even cooler modal. Wins</p>
  <a class="close-reveal-modal">&#215;</a>
</div>

*/


?>