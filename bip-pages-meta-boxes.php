<?php
namespace BipPages;

function add_post_meta_boxes() {
    // see https://developer.wordpress.org/reference/functions/add_meta_box for a full explanation of each property
    add_meta_box(
        "bip_content_prepared_by_name", // div id containing rendered fields
        __( "Content prepared by" ), // section heading displayed as text
        __NAMESPACE__ . "\post_meta_box_content_prepared_by", // callback function to render fields
        "bip", // name of post type on which to render fields
        "side", // location on the screen
        "high" // placement priority
    );
}
add_action( "admin_init", __NAMESPACE__ . "\add_post_meta_boxes" );

function save_post_meta_boxes(){
    global $post;
    if ( empty( $post->ID ) )
      return;

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( get_post_status( $post->ID ) === 'auto-draft' ) {
        return;
    }
    update_post_meta( $post->ID, "_bip_prepared_by", sanitize_text_field( $_POST[ "_bip_prepared_by" ] ) );
}
add_action( 'save_post', __NAMESPACE__ . '\save_post_meta_boxes' );

function post_meta_box_content_prepared_by(){
    global $post;
    $custom = get_post_custom( $post->ID );
    $prepared_by = !empty( $custom[ "_bip_prepared_by" ] ) ? $custom[ "_bip_prepared_by" ][ 0 ] : '';

    ?>
    <label for="_bip_prepared_by" class="components-base-control__label">
      <?php _e( 'Enter name and surname of content author' ) ?>
    </label>
    <input name="_bip_prepared_by" class="components-text-control__input" value="<?= $prepared_by ?>" />
    <?php
}
