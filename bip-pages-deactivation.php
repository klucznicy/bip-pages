<?php
namespace BipPages;

// turn all bip pages to regular pages
function convert_page_types() {
  $converted = 0;

  $bip_pages = get_pages( ['post_type' => 'bip'] );
  $total = count( $bip_pages );

  foreach ( $bip_pages as $page ) {
    if ( post_exists( $page->post_title, '', '', 'page' ) ) {
      // rename first
      wp_update_post( array(
        'ID' => $page->ID,
        'post_status' => 'draft'
      ));
    }

    $res = set_post_type( $page->ID, 'page');
    $converted += $res;
  }

  return $converted;
}

function remove_widgets() {
  // remove widget data
  delete_option( 'widget_bip-logo' );
  $active_widgets = get_option( 'sidebars_widgets' );

  foreach ( $active_widgets as $key => $val ) {
    if ( empty( $val ) || !is_array( $val ) ) {
      continue;
    }

    $widget_ids = array_flip( $val );

    foreach ( $widget_ids as $widget => $id ) {
      if ( strpos( $widget, 'bip-logo-' ) === 0 ) {
        unset( $active_widgets[$key][$id] );
      }
    }
  }

  update_option( 'sidebars_widgets', $active_widgets );
}

add_action( 'admin_notices', __NAMESPACE__ . '\deactivation_notice' );

function deactivation_notice(){
  if( get_transient( 'bip-pages-deactivation-msg' ) ){
      ?>
      <div class="updated notice is-dismissible">
          <p>
            <?= esc_html__( 'BIP Pages plugin has been deactivated. Your BIP pages have been converted to standard pages (or drafts in case of a conflicting page title)', 'bip-pages' ) ?>
          </p>
      </div>
      <?php

      delete_transient( 'bip-pages-deactivation-msg' );
    }
}
