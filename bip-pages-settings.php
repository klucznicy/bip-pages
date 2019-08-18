<?php
namespace BipPages\Settings;

const PAGE_NAME = 'bip-pages-admin';
const OPTION_NAME = 'bip-pages';

function register_options_page() {

  add_submenu_page(
    'edit.php?post_type=bip',
    __( 'BIP Pages Settings' ),
    __( 'BIP Pages Settings' ),
    'manage_options',
    PAGE_NAME,
    __NAMESPACE__ . '\create_admin_page'
  );

}
add_action( 'admin_menu', __NAMESPACE__ . '\register_options_page' );

function create_admin_page() {
  include( "templates/bip-page-settings-template.php" );
}

/**
* Register and add settings
*/
function page_init() {
  register_setting(
    'bip-pages', // Option group
    OPTION_NAME, // Option name
    'bip_pages_settings_sanitize' // Sanitize
  );

  add_settings_section(
    'bip_pages_settings_main_page', // ID
    __('BIP Main Page settings'), // Title
    '', // Callback (for help text if needed)
    'bip-pages-admin' // Page
  );

  add_settings_field(
    'bip_pages_main_page_id', // ID
    __('BIP Main Page'), // Title
    __NAMESPACE__ . '\main_page_id_callback', // Callback
    PAGE_NAME, // Page
    'bip_pages_settings_main_page' // Section
  );

  add_settings_field(
    'bip_pages_main_page_address',
    __('Organization Address'),
    __NAMESPACE__ . '\main_page_address_callback',
    PAGE_NAME,
    'bip_pages_settings_main_page'
  );

  add_settings_field(
    'bip_pages_main_page_email',
    __('E-Mail Address'),
    __NAMESPACE__ . '\main_page_email_callback',
    PAGE_NAME,
    'bip_pages_settings_main_page'
  );

  add_settings_field(
    'bip_pages_main_page_phone',
    __('Telephone Number'),
    __NAMESPACE__ . '\main_page_phone_callback',
    PAGE_NAME,
    'bip_pages_settings_main_page'
  );

  add_settings_field(
    'bip_pages_main_page_rep',
    __('Name of representative'),
    __NAMESPACE__ . '\main_page_rep_callback',
    PAGE_NAME,
    'bip_pages_settings_main_page'
  );

}
add_action( 'admin_init', __NAMESPACE__ . '\page_init' );

/**
* Sanitize each setting field as needed
*
* @param array $input Contains all settings fields as array keys
*/
function sanitize( $input ) {
  $new_input = array();
  $options = array( /** @TODO **/ );
  foreach ( $options as $opt ) {
    if ( isset( $input[$opt] ) ) {
      $new_input[$opt] = boardpress_sanitize_id( $input[$opt] );
    }
  }

  return $new_input;
}

function main_page_id_callback() {
  $args = [
    'show_option_none' => __('Not selected'),
    'option_none_value' => 0,
    'name' => OPTION_NAME . "[id]",
    'id' => OPTION_NAME . "[id]",
    'selected' => \BipPages\get_bip_main_page(),
    'post_type' => 'bip'
  ];
  wp_dropdown_pages( $args );
}

function main_page_address_callback() {
  build_input('address', 'text', __('The address of your organization'));
}

function main_page_rep_callback() {
  build_input('rep', 'text', __('Full name of a BIP editor'));
}

function main_page_email_callback() {
  build_input('email', 'email', __('Email to a BIP editor'));
}

function main_page_phone_callback() {
  build_input('phone', 'tel', __('Phone number to your organization'));
}


function get_settings_url( Array $options = array() ) {
  $query = http_build_query(
    array_merge( ['post_type' => 'bip', 'page' => PAGE_NAME], $options )
  );

  return admin_url( 'edit.php?' . $query );
}

/** auxiliary functions **/
function build_input( $id, $type = 'text', $placeholder = '', $pattern = false ) {
  $option = OPTION_NAME;
  $values = get_option( $option );
  $id_safe = esc_attr($id);

  $element = "<input type='{$type}' value='%s'
               id='{$option}[{$id_safe}]' name='{$option}[{$id_safe}]'
               placeholder='{$placeholder}' ";
  $element .= $pattern ? "pattern='${pattern}'" : '';
  $element .= "/>";

  printf( $element, !empty( $values[$id] ) ? $values[$id] : '' );
}
