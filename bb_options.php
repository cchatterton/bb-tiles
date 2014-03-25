<?php

// create custom plugin settings menu
add_action('admin_menu', 'create_bb_tiles_options');

function create_bb_tiles_options() {

    // add_submenu_page( 'edit.php?post_type=tile', 'Tile Options', 'Options', 'manage_options', 'bb_tiles_options', 'bb_tiles_options_page' );
    // add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
    add_submenu_page( 'edit.php?post_type=tile', 'Add Video Tile', 'Add Video Tile', 'manage_options', 'bb_tiles_options_video', 'bb_tiles_options_video' );
    // add_submenu_page( 'edit.php?post_type=tile', 'Add Tile (New)', 'Add Tile', 'manage_options', 'bb_tiles_add_new', 'bb_tiles_add_new' );

    //call register settings function
    add_action( 'admin_init', 'register_bb_tiles_options' );

}

add_action('admin_menu', 'remove_redundant_links');
function remove_redundant_links() {
    global $submenu;
    unset($submenu['edit.php?post_type=tile'][16]);
    unset($submenu['edit.php?post_type=tile'][10]);
}





function bb_tiles_add_new() {
   ?>

    <div class='wrap'>
    <h2>What type of tile do you wish to add?</h2>

    <a href="/wp-admin/post-new.php?post_type=tile" class="button button-primary button-large">Text or Image Tile</a>
    <a href="/wp-admin/edit.php?post_type=tile&page=bb_tiles_options_video" class="button button-primary button-large">Video Tile</a>

    </div>

    <?php
}

function register_bb_tiles_options() {

    //register our settings
    $fields = array (
        'bb_tiles_level_1','bb_tiles_level_2','bb_tiles_level_3',
        'bb_tiles_sidebar_width_large','bb_tiles_sidebar_width_mobile',
        'bb_tiles_breakpoint1','bb_tiles_breakpoint2','bb_tiles_breakpoint3','bb_tiles_breakpoint4',
        'bb_tiles_active_templates'
        );
    foreach ($fields as $field) register_setting( 'bb-tiles-options-group', $field );

}

function bb_tag_show($showfor) {
    $showfor = explode(',', $showfor);

    $qs = $_SERVER["QUERY_STRING"]; parse_str($qs); parse_str($qs, $arr);

    if ( !in_array($arr[tag], $showfor) ) {
        $style = 'style="display:none;"';
        return $style;
    }
}

function bb_tiles_options_page() {
    $qs = $_SERVER["QUERY_STRING"]; parse_str($qs); parse_str($qs, $arr);
?>
<div class="wrap">
    <h2>Tiles Options</h2>
    <form method="post" action="options.php">

        <table class="form-table">

<?php bb_tiles_input('text','Level 1 tile Categories (IDs)','bb_tiles_level_1',10,'1,2,4'); ?>
<?php bb_tiles_input('text','Level 2 tile Categories (IDs)','bb_tiles_level_2',10,'1,2,4'); ?>
<?php bb_tiles_input('text','Level 3 tile Categories (IDs)','bb_tiles_level_3',10,'1,2,4'); ?>

<?php bb_tiles_input('text','Sidebar width large','bb_tiles_sidebar_width_large',10,'300'); ?>
<?php bb_tiles_input('text','Sidebar width mobile','bb_tiles_sidebar_width_mobile',10,'300'); ?>

<?php bb_tiles_input('text','Breakpoint #1','bb_tiles_breakpoint1',10,'768'); ?>
<?php bb_tiles_input('text','Breakpoint #2','bb_tiles_breakpoint2',10,'768'); ?>
<?php bb_tiles_input('text','Breakpoint #3','bb_tiles_breakpoint3',10,'768'); ?>
<?php bb_tiles_input('text','Breakpoint #4','bb_tiles_breakpoint4',10,'768'); ?>


        </table>

<?php submit_button(); ?>
<?php settings_fields( 'bb-tiles-options-group' ); ?>
    </form>
</div>
<?php }



function bb_tiles_options_video() {
        // ********** STEP ONE ********* //
    ?>
    <form method="post" action="#">
        <?php if(!$_POST){ ?>
        <h2>Add a YouTube Video Tile</h2>
            <p>Use a standard YouTube your format ie: <em>http://www.youtube.com/watch?v=ABCDEFGH</em></p><p>
            <input type="hidden" name="youtube_tile_request" value="youtube_tile_request" />
            <label for="youtube_url">Youtube URL: </label><input type="text" name="youtube_url" value="<?php echo $_POST["youtube_url"] ?>" size="75"/><br/><br/>
            <label></label><input type="submit" name="Configure Tile" value="Configure Tile" class="button button-primary button-large"/><br/>
            </p>
    <?php
        }

    // ********** STEP TWO ********* //

    if($_POST["youtube_url"]){

        echo "<h2>Select Thumbnail</h2>";

        $youtube_id = explode("=", $_POST["youtube_url"]);
        // Make sure even the first file is there, if not display an error

        $json_output = file_get_contents("http://gdata.youtube.com/feeds/api/videos/".$youtube_id[1]."?v=2&alt=json");
        $json = json_decode($json_output, true);

        $title = $json['entry']['title']['$t'];
        $thumbnails = $json['entry']['media$group']['media$thumbnail'];
        $video_description = $json['entry']['media$group']['media$description']['$t'];
        $video_id = $json['entry']['media$group']['yt$videoid'];

        $embed_url = 'http://www.youtube.com/embed/'.reset($video_id);

        if($thumbnails){
            echo '<table class="wp-list-table widefat fixed pages"><thead><tr><th style="width:25px;""></th><th>Thumbnail</th></tr></thead>';
            echo '<input type="hidden" name="youtube_tile_selection" value="youtube_tile_selection" />';
            echo '<label for="youtube_title">Title:</label><br/><input type="text" name="youtube_title" id="youtube_title" value="'.$title.'" size="75"/><br/>';
            echo '<label for="youtube_description">Description</label><br/><textarea name="youtube_description" rows="7" cols="75" style="border:1px solid #dedede;">'.$video_description.'</textarea><br/>';
            echo '<input type="hidden" name="youtube_url_for_submission" value="'.$embed_url.'" />';
            echo '<input type="hidden" name="youtube_id" value="'.$video_id.'" />';
            $i = 0;
            foreach($thumbnails as $thumbnail) {
                echo "<tr><td><input type='radio' name='youtube_thumbnail_id' value='".$thumbnail['url']."' id='thumbnail_".$i."''></td>";
                echo "<td><label for='thumbnail_".$i."'><img src='".$thumbnail['url']."'/></label></td></tr>";
                $i++;
            }
            // for ($i = 0; $i <= 3; $i++) {
            //     echo "<tr><td><input type='radio' name='youtube_thumbnail_id' value='http://img.youtube.com/vi/".$youtube_id[1]."/".$i.".jpg' id='thumbnail_".$i."''></td>";
            //     echo "<td><label for='thumbnail_".$i."'><img src='http://img.youtube.com/vi/".$youtube_id[1]."/".$i.".jpg'/></label></td></tr>";
            // }

            echo '</table>';
            echo '<label></label><br/><input type="submit" name="Next Step" value="Next" class="button button-primary button-large" />';
        }else{
            echo "<em style='color:red'>Sorry there doesn't appear to be a youtube thumbnail associated with that url, please enter a new one and try again.</em>";
        }
    }

    // ********** STEP THREE ********* //

    if($_POST["youtube_tile_selection"]){

        // Fetch and Store the Image
        $thumbnail = $_POST['youtube_thumbnail_id'];
        $get = wp_remote_get($thumbnail);
        $type = wp_remote_retrieve_header( $get, 'content-type' );
        $mirror = wp_upload_bits(rawurldecode(basename( $thumbnail )), '', wp_remote_retrieve_body( $get ) );


        //Attachment options
        $attachment = array(
        'post_title'=> basename($_POST['youtube_title']),
        'post_mime_type' => $type,
        'post_content' => $_POST['youtube_description']
        );

        // Add the image to media library
        $attach_id = wp_insert_attachment($attachment, $mirror['file']);
        $attach_data = wp_generate_attachment_metadata( $attach_id, $mirror['file'] );
        $result = wp_update_attachment_metadata( $attach_id, $attach_data );

        // Then get our result
        $meta_result = update_post_meta($attach_id, 'media_type', 'video_youtube');
        // $meta_result = update_post_meta($attach_id, 'sidebar_tiles_link', $_POST['youtube_url_for_submission']);
        // $meta_result = update_post_meta($attach_id, 'attachment_content', $_POST['youtube_description']);
        $meta_result = wp_set_post_categories( $attach_id, array('4') );

        // Now we need to ALSO insert a post type 'tile' and add the featured image (which is the thumbnail attachment)
        // as well as any other information
        $post_args = array(
            'post_title' => basename($_POST['youtube_title']),
            'post_type' => 'tile',
            'post_status' => 'publish',
            'post_excerpt' => basename($_POST['youtube_description'])
            );
        $new_post_id = wp_insert_post( $post_args, $wp_error );

        // Now set some of the custom meta that Chris has configured in the sidebar meta box


        $update_video_post = array('ID' => $new_post_id, 'post_content' => '[video_tile id='.$new_post_id.' url="'.$_POST['youtube_url_for_submission'].'"]' );
        // Update the post into the database
        wp_update_post( $update_video_post );

        // $meta_result = update_post_meta($new_post_id, 'youtube_id', $_POST['youtube_id']);
        $meta_result = update_post_meta($new_post_id, 'bb_tile_type', '3323');
        $meta_result = update_post_meta($new_post_id, 'bb_tile_meta_resource_url', $_POST['youtube_url_for_submission']);

        // Also set the thumbnail/featured image
        $set_thumb_return = set_post_thumbnail( $new_post_id, $attach_id );


        if($set_thumb_return){
            echo "<h2>Tile configured!</h2>";
            echo "<a href='/wp-admin/post.php?post=".$new_post_id."&action=edit'>Click here to review and finalise your tile</a>";
        }else{
            echo "<h2>Whoops</h2>";
            echo "Seems we had an issue.";
        }

    }
    ?>
    </form>
    <?php

}

function bb_tiles_input($type,$title,$name,$size,$placeholder) {

        echo '<tr valign="top" >'."\n";
        echo '  <th scope="row">'.$title.'</th>'."\n";
        echo '  <td>'."\n";
        echo '      <input type="'.$type.'" name="'.$name.'" size="'.$size.'" placeholder="'.$placeholder.'" value="'.get_option($name,true).'" />'."\n";
        echo '  </td>'."\n";
        echo '</tr>'."\n";
}

?>