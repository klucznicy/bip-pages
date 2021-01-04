<?php
namespace BipPages;

function add_post_meta_boxes() {
    // see https://developer.wordpress.org/reference/functions/add_meta_box for a full explanation of each property
    add_meta_box(
        "bip_content_prepared_by_name", // div id containing rendered fields
        __( "Content prepared by", 'bip-pages' ), // section heading displayed as text
        __NAMESPACE__ . "\post_meta_box_content_prepared_by", // callback function to render fields
        "bip", // name of post type on which to render fields
        "side", // location on the screen
        "high" // placement priority
    );
}
add_action( "admin_init", __NAMESPACE__ . "\add_post_meta_boxes" );

function save_post_meta_boxes(){
    global $post;

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    } elseif ( empty( $post->ID ) && get_post_status( $post->ID ) === 'auto-draft' ) {
        return;
    } elseif ( !isset( $_POST[ "_bip_prepared_by" ] ) ) {
      // check added to avoid errors later
      // should not happen unless request is fiddled with
      return;
    }

    $sanitized_meta_value = sanitize_text_field( $_POST[ '_bip_prepared_by' ] );

    /**
     * validate meta value, i.e.:
     * (1) disallow multiline content (handled by sanitization above),
     * (2) disallow long content
      * see input element definition in post_meta_box_content_prepared_by()
     */
    if ( strlen( $sanitized_meta_value ) > 100 ) {
      return;
    }

    update_post_meta( $post->ID, '_bip_prepared_by', $sanitized_meta_value );
}
add_action( 'save_post', __NAMESPACE__ . '\save_post_meta_boxes' );

function post_meta_box_content_prepared_by(){
    global $post;
    $custom = get_post_custom( $post->ID );
    $prepared_by = !empty( $custom[ "_bip_prepared_by" ] ) ? $custom[ "_bip_prepared_by" ][ 0 ] : '';

    ?>
    <label for="_bip_prepared_by" class="components-base-control__label">
      <?php _e( 'Enter name and surname of content author', 'bip-pages' ) ?>
    </label>
    <input name="_bip_prepared_by" class="components-text-control__input"
      value="<?= esc_attr( $prepared_by ) ?>" maxlength="100"
      />
    <?php
}
