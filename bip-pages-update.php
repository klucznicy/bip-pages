<?php
namespace BipPages;

function update_plugin() {
  switch ( get_option( Settings\OPT_LAST_VERSION, '1.1.1' ) ) {
    case '1.1.1':
      update_settings_scheme();
      update_main_page();
  }

  update_option( Settings\OPT_LAST_VERSION, CURRENT_VERSION );
}

function update_settings_scheme() {
  $option_name = 'bip-pages';
  $old_option = get_option( $option_name, array() );

  $update_map = array(
    'id' => 'bip_pages_main_page_id',
    'instruction_id' => 'bip_pages_instruction_id',
    'address' => 'bip_pages_address',
    'email' => 'bip_pages_email',
    'phone' => 'bip_pages_phone',
    'rep' => 'bip_pages_editor',
  );

  foreach ( $old_option as $opt => $val ) {
    update_option( $update_map[$opt], $val );
  }

  delete_option( $option_name );

  return true;
}

function update_main_page() {
  // check if block editor is enabled
  if ( false ) {
    return null;
  }

  $post = get_post( get_bip_main_page() );

  $post->post_content = get_main_page_default_content();

  $res = wp_update_post( $post );

  return $res == get_bip_main_page();
}

update_plugin();
