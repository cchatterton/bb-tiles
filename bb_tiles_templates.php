<?php

// create custom plugin settings menu
add_action('admin_menu', 'create_bb_tiles_templates');

function create_bb_tiles_templates() {

    add_submenu_page( 'edit.php?post_type=tile', 'Tile Templates', 'Tile Templates', 'manage_options', 'bb_tiles_templates', 'bb_tiles_templates_page' );
    // add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );

    //call register settings function
    add_action( 'admin_init', 'register_bb_tiles_templates' );
}

function register_bb_tiles_templates() {

}

function bb_tiles_templates_page() {
    $qs = $_SERVER["QUERY_STRING"]; parse_str($qs); parse_str($qs, $arr);

    if ( $arr['action']=='register' ) require_once('bb_register_types.php');

    // get tiles
    $tiles = fx_get_tile_templates();
?>
<div class="wrap">
    <div class="icon32 icon32-posts-tile" id="icon-edit"><br></div>
    <h2>Tile Templates</h2>

    <ul class="subsubsub">
        <li class="all"><a href="admin.php?page=bb_tiles_templates&tile_status=all">All <span class="count">(tbd)</span></a> |</li>
        <li class="active"><a href="admin.php?page=bb_tiles_templates&tile_status=active">Active <span class="count">(tbd)</span></a> |</li>
        <li class="inactive"><a href="admin.php?page=bb_tiles_templates&tile_status=inactive">Inactive <span class="count">(tbd)</span></a> |</li>
        <li class="inactive"><a href="admin.php?page=bb_tiles_templates&action=register">Re-register Tiles <span class="count"></a></li>
    </ul>

<!--
    <form action="" method="get">
        <p class="search-box">
            <label for="plugin-search-input" class="screen-reader-text">Search Tile Templates:</label>
            <input type="search" value="" name="s" id="tile-search-input">
            <input type="submit" value="Search Tile Templates" class="button" id="search-submit" name="">
        </p>
    </form>
-->

    <form action="" method="post">
        <input type="hidden" id="page" value="bb_tiles_templates">
        <table cellspacing="0" class="wp-list-table widefat">
            <thead>
                <tr>
                    <th style="" class="manage-column column-name" id="name" scope="col">Tile Template</th>
                    <th style="" class="manage-column column-description" id="description" scope="col">Description</th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th style="" class="manage-column column-name" id="name" scope="col">Tile Template</th>
                    <th style="" class="manage-column column-description" id="description" scope="col">Description</th>
                </tr>                </tfoot>
<?php foreach ($tiles as $t => $tile) {

// var_dump();

    ?>
            <tbody id="the-list">
                <tr class="active" >
                    <td class="title">
                        <strong><?php echo $tile->name ?></strong>
                        <div class="row-actions visible">
                            <span class="filename"><?php echo $tile->filename ?></span>
                        </div>
                        <p><?php echo $tile->tile_id ?></p>
                    </td>
                    <td class="column-description desc">
                        <div class="plugin-description">
                            <p><?php echo $tile->description ?></p>
                            <p><?php echo $tile->instructions ?></p>
                            <p><?php echo $tile->meta ?></p>
                            <p><?php echo $tile->post ?></p>
                        </div>
                        <div class="active second">Version <?php echo $tile->version ?> | Variation <?php echo $tile->variation ?></div>
                    </td>
                </tr>
            </tbody>
<?php } ?>
        </table>
        <div class="tablenav bottom">
            <br class="clear">
        </div>
    </form>
</div>

<?php

}
?>