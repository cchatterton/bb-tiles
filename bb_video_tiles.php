<?php

function fx_bb_video_tile_wizard() {
    add_submenu_page( 'edit.php?post_type=tile', 'Add Video Tile', 'Add Video Tile', 'manage_options', 'bb_video_tile_wizard_page', 'fx_bb_video_tile_wizard_page');
}
add_action('admin_menu', 'fx_bb_video_tile_wizard' );

function fx_bb_video_tile_wizard_page() {
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
            echo '<script>'."\n";
            echo '  window.location = "/wp-admin/post.php?post='.$new_post_id.'&action=edit"'."\n";
            echo '</script>'."\n";
            // echo "<h2>Tile configured!</h2>";
            // echo "<a href='/wp-admin/post.php?post=".$new_post_id."&action=edit'>Click here to review and finalise your tile</a>";
        }else{
            echo "<h2>Whoops</h2>";
            echo "Seems we had an issue.";
        }

    }
    ?>
    </form>
    <?php

}

?>
