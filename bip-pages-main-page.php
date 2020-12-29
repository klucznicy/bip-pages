<?php
namespace BipPages;

// fallback for BIP main page in case block editor is disabled
function add_basic_main_page_data_fallback( $content ) {
  $post = get_post();

  if ( $post->ID == get_bip_main_page() && !has_block( 'bip-pages/org-info' ) ) {
    $options = get_option( Settings\OPTION_NAME );
    $bip_logo_url = plugin_dir_url( __FILE__ ) . 'assets/bip-logos/bip-full-color-pl_min.png';
    $bip_instruction_url = get_permalink( get_bip_instruction_page() );

    ob_start();
    include( __DIR__ . '/templates/bip-main-template.php' );
    echo $content;
    $content = ob_get_clean();
  }

  return $content;
}
add_filter('the_content', __NAMESPACE__ . '\add_basic_main_page_data_fallback' );

function is_bip_main_page_edit_screen() {
  return isset( $_GET['action'] ) &&
      $_GET['action'] == 'edit' &&
      isset( $_GET['post'] ) &&
      $_GET['post'] == get_bip_main_page();
}
