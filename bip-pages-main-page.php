<?php
namespace BipPages;

// fallback for BIP main page in case block editor is disabled
function add_basic_main_page_data_fallback( $content ) {
  $post = get_post();

  if ( $post->ID == get_bip_main_page() && !use_block_editor_for_post( $post->ID ) ) {
    $options = get_option( Settings\OPTION_NAME );
    $bip_logo_url = plugin_dir_url( __FILE__ ) . 'assets/bip-logos/bip-full-color-pl_min.png';
    $bip_instruction_url = get_permalink( get_bip_instruction_page() );

    ob_start();
    include( __DIR__ . '/templates/bip-main-template.php' );
    include( __DIR__ . '/templates/bip-search-form.php' );
    echo $content;
    $content = ob_get_clean();
  }

  return $content;
}
add_filter('the_content', __NAMESPACE__ . '\add_basic_main_page_data_fallback' );

function main_page_edit_notice() {
  if ( is_bip_main_page_edit_screen() ) {
    $message = '<p>' . esc_html__( 'You are editing the BIP main page.', 'bip-pages' ) . '</p>' .
      '<p>' . esc_html__( "This page needs to include the BIP logo, your organization's address, a BIP search module, and a link to the instruction page.", 'bip-pages' ) . '</p>';
    echo "<div class='notice is-info is-dismissible'>{$message}</div>";
  }
}
add_action( 'admin_notices', __NAMESPACE__ . '\main_page_edit_notice' );

function is_bip_main_page_edit_screen() {
  return isset( $_GET['action'] ) &&
      $_GET['action'] == 'edit' &&
      isset( $_GET['post'] ) &&
      $_GET['post'] == get_bip_main_page();
}
