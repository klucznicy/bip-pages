<?php
namespace BipPages;

function create_page( $title, $content = '' ) {
  $page_id = \post_exists( $title );

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
  // initialize widget properties if not set yet
  if ( empty( get_option( 'widget_bip-logo' ) ) ) {
    $widget_options = array(
      1 => array( 'image_type' => 1 ),
    );

    update_option( 'widget_bip-logo', $widget_options );
  }

  $active_widgets = get_option( 'sidebars_widgets' );

  next( $active_widgets ); // skip first element, which is wp_inactive_widgets

  $first_sidebar = current( $active_widgets );
  $first_sidebar_key = key( $active_widgets );

  array_unshift( $first_sidebar, 'bip-logo-1' );

  $active_widgets[$first_sidebar_key] = $first_sidebar;

  update_option( 'sidebars_widgets', $active_widgets );
}

add_action('admin_init', __NAMESPACE__ . '\post_activation_flow');
function post_activation_flow() {
  if( is_admin() && get_option('Activated_Plugin') == 'bip-pages' ) {
    flush_rewrite_rules(); // we're adding a new page type slug
    delete_option('Activated_Plugin');
    wp_redirect( Settings\get_settings_url( ['plugin-activated' => 1] ) );

    // @TODO add info post-activation
  }
}