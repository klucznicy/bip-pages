<?php
namespace BipPages;

function add_basic_main_page_data( $content ) {
  $post = get_post();
  if ( $post->ID == get_bip_main_page() ) {
    $options = get_option( Settings\OPTION_NAME );
    $bip_main_page_content = $content;
    $bip_logo_url = plugin_dir_url( __FILE__ ) . 'assets/bip-logos/bip-full-color-pl_min.png';
    $bip_instruction_url = get_permalink( get_bip_instruction_page() );

    ob_start();
    include( __DIR__ . '/templates/bip-main-template.php' );
    $content = ob_get_clean();
  }

  return $content;
}
add_filter('the_content', __NAMESPACE__ . '\add_basic_main_page_data' );

function main_page_edit_notice() {
  if ( is_bip_main_page_edit_screen() ) {
    $message = '<p>' . esc_html__( 'You are editing the BIP main page.', 'bip-pages' ) . '</p>' .
      '<p>' . esc_html__( 'Parts of this page are automatically generated. The text you enter below will be displayed between the automatic BIP header and footer.', 'bip-pages' ) . '</p>';
    echo "<div class='notice notice-info is-dismissible'>{$message}</div>";
  }
}
add_action( 'admin_notices', __NAMESPACE__ . '\main_page_edit_notice' );

function enqueue_editor_notices() {
  // only proceed if user is editing BIP main page
  if ( !is_bip_main_page_edit_screen() ) {
    return;
  }

  wp_enqueue_script(
        'bip-editor-notices',
        plugin_dir_url( __FILE__ ) . '/js/editor_notices.js',
        array( 'wp-notices', 'wp-i18n', 'wp-editor' )
    );

  wp_set_script_translations( 'bip-editor-notices', 'bip-pages' );
}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\enqueue_editor_notices' );

function is_bip_main_page_edit_screen() {
  return isset( $_GET['action'] ) &&
      $_GET['action'] == 'edit' &&
      isset( $_GET['post'] ) &&
      $_GET['post'] == get_bip_main_page();
}
