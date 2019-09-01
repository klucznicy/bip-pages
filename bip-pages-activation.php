<?php
namespace BipPages;

function activate() {
  add_option('Activated_Plugin','bip-pages'); // deleted later in post_activation_flow
  create_main_page();
  create_instructions_page();
  add_logo_widget();
}

function create_page( $title, $content = '' ) {
  $page_id = post_exists( $title );
  if ( empty( $page_id ) ) {
    // create page with bip post_type
    $page_args = array(
      'post_title'    => wp_strip_all_tags( $title ),
      'post_content'  => $content,
      'post_status'   => 'publish',
      'post_author'   => get_current_user_id(),
      'post_type'     => 'bip',
    );

    $page_id = wp_insert_post( $page_args, true );
  } else {
    $page_args = array(
      'ID' => $page_id,
      'post_type' => 'bip'
    );

    $page_id = wp_update_post( $page_args, true );
  }

  return $page_id;
}

function create_main_page() {
  $title = __( 'BIP Main Page', 'bip-pages' );

  $main_page_id = create_page( $title );

  if ( !is_wp_error( $main_page_id ) ) {
    set_bip_main_page( $main_page_id );
  }
}

function create_instructions_page() {
  $title = __( 'BIP usage manual', 'bip-pages' );

  // Polish only for now
  $instructions = file_get_contents( __DIR__ . '/boilerplate-text/bip-usage-manual-pl.txt' );

  $instruction_page_id = create_page( $title, $instructions );

  if ( !is_wp_error( $instruction_page_id ) ) {
    $option = get_option( Settings\OPTION_NAME, array() );
    $option['instruction_id'] = $instruction_page_id;
    update_option( Settings\OPTION_NAME, $option );
  }
}

function add_logo_widget() {
  if ( !empty( get_option( 'widget_bip-logo' ) ) ) {
    $widget_options = array(
      1 => array( 'image_type' => 1 ),
    );

    update_option( 'widget_bip-logo', $widget_options );
  }

  $active_widgets = get_option( 'sidebars_widgets' );

  $first_sidebar = array_slice( $active_widgets, 1, 1 );

  array_unshift( $first_sidebar, 'bip-logo-1' );

  $updated_widgets = array_merge( $active_widgets, $first_sidebar );

  update_option( 'sidebars_widgets', $updated_widgets );
}

add_action('admin_init', __NAMESPACE__ . '\post_activation_flow');
function post_activation_flow() {
  if( is_admin() && get_option('Activated_Plugin') == 'bip-pages' ) {
    flush_rewrite_rules(); // we're adding a new page type slug
    delete_option('Activated_Plugin');
    wp_redirect( Settings\get_settings_url( ['plugin-activated' => 1] ) );
  }
}

function deactivate() {
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

  // turn all bip pages to regular pages
  $bip_pages = get_pages( ['post_type' => 'bip'] );
  foreach ( $bip_pages as $id ) {
    wp_update_post( $id, ['post_type' => 'page' ] );
  }
}
