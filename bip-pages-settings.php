<?php
namespace BipPages\Settings;

const PAGE_NAME = 'bip-pages-admin';
const OPTION_GROUP = 'bip-pages';
const OPT_LAST_VERSION = 'bip_pages_last_version';

function register_options_page() {
  add_submenu_page(
    'edit.php?post_type=bip',
    __( 'BIP Pages Settings', 'bip-pages' ),
    __( 'BIP Pages Settings', 'bip-pages' ),
    'manage_options',
    PAGE_NAME,
    __NAMESPACE__ . '\render_admin_page'
  );

  add_submenu_page(
    'edit.php?post_type=bip',
    __( 'BIP Help & Checklist', 'bip-pages' ),
    __( 'BIP Help & Checklist', 'bip-pages' ),
    'manage_options',
    'bip-pages-checklist',
    __NAMESPACE__ . '\render_checklist_page'
  );
}
add_action( 'admin_menu', __NAMESPACE__ . '\register_options_page' );

function render_admin_page() {
  include( "templates/bip-page-settings-template.php" );
}

function render_checklist_page() {
  // @TODO: Possibly translate into English. Polish version only for now
  include( "templates/bip-page-checklist-template.php" );
}


function notice_success() {
  $screen = get_current_screen();

  if ( $screen->id === 'bip_page_bip-pages-admin' ) {
    if (isset($_GET['settings-updated']) && empty( get_settings_errors() ) ) {
  ?>
  <div class="notice notice-success is-dismissible">
    <p><?php esc_html_e( 'Settings saved successfully.', 'bip-pages' ); ?></p>
  </div>
  <?php
    }
  }
}
add_action( 'admin_notices', __NAMESPACE__ . '\notice_success' );

/**
* Register and add settings
*/
function page_init() {
  register_setting(
    OPTION_GROUP,
    'bip_pages_org_name', // Option name
    array(
      'type' => 'string',
      'sanitize_callback' => 'sanitize_text_field',
    )
  );

  register_setting(
    OPTION_GROUP,
    'bip_pages_address', // Option name
    array(
      'type' => 'string',
      'sanitize_callback' => 'sanitize_textarea_field',
    )
  );

  register_setting(
    OPTION_GROUP,
    'bip_pages_editor', // Option name
    array(
      'type' => 'string',
      'sanitize_callback' => 'sanitize_text_field',
    )
  );

  register_setting(
    OPTION_GROUP,
    'bip_pages_email', // Option name
    array(
      'type' => 'string',
      'sanitize_callback' => 'sanitize_email',
    )
  );

  register_setting(
    OPTION_GROUP,
    'bip_pages_phone', // Option name
    array(
      'type' => 'string',
      'sanitize_callback' => __NAMESPACE__ . '\sanitize_phone',
    )
  );

  register_setting(
    OPTION_GROUP,
    'bip_pages_main_page_id', // Option name
    array(
      'type' => 'integer',
      'sanitize_callback' => __NAMESPACE__ . '\sanitize_main_page_id',
    )
  );

  register_setting(
    OPTION_GROUP,
    'bip_pages_instruction_id', // Option name
    array(
      'type' => 'integer',
      'sanitize_callback' => __NAMESPACE__ . '\sanitize_instruction_id',
    )
  );

  add_settings_section(
    'bip_pages_settings_main_page', // ID
    __('BIP Main Page settings', 'bip-pages'), // Title
    '', // Callback (for help text if needed)
    PAGE_NAME // Page
  );

  add_settings_field(
    'bip_pages_main_page_id', // ID
    __('BIP Main Page', 'bip-pages'), // Title
    __NAMESPACE__ . '\main_page_id_callback', // Callback
    PAGE_NAME, // Page
    'bip_pages_settings_main_page' // Section
  );

  add_settings_field(
    'bip_pages_org_name',
    __('Organization Name', 'bip-pages'),
    __NAMESPACE__ . '\main_page_org_name_callback',
    PAGE_NAME,
    'bip_pages_settings_main_page'
  );

  add_settings_field(
    'bip_pages_address',
    __('Organization Address', 'bip-pages'),
    __NAMESPACE__ . '\main_page_address_callback',
    PAGE_NAME,
    'bip_pages_settings_main_page'
  );

  add_settings_field(
    'bip_pages_email',
    __('E-Mail Address', 'bip-pages'),
    __NAMESPACE__ . '\main_page_email_callback',
    PAGE_NAME,
    'bip_pages_settings_main_page'
  );

  add_settings_field(
    'bip_pages_phone',
    __('Phone Number', 'bip-pages'),
    __NAMESPACE__ . '\phone_callback',
    PAGE_NAME,
    'bip_pages_settings_main_page'
  );

  add_settings_field(
    'bip_pages_editor',
    __('Name of representative', 'bip-pages'),
    __NAMESPACE__ . '\main_page_rep_callback',
    PAGE_NAME,
    'bip_pages_settings_main_page'
  );

  add_settings_section(
    'bip_pages_settings_instruction_page', // ID
    __('BIP instruction page settings', 'bip-pages'), // Title
    '', // Callback (for help text if needed)
    PAGE_NAME
  );

  add_settings_field(
    'bip_pages_instruction_id',
    __('Usage instruction page', 'bip-pages'),
    __NAMESPACE__ . '\instruction_page_callback',
    PAGE_NAME,
    'bip_pages_settings_instruction_page'
  );

}
add_action( 'admin_init', __NAMESPACE__ . '\page_init' );

/**
* Sanitize each setting field as needed
*/
function sanitize_id( $value, $option ) {
  if ( is_numeric( $value ) && /* post exists? */ get_post_status( $value ) !== false ) {
    return $value;
  } else {
    /* translators: %s is internal option identifired, either "id" or "instruction_id" */
    $msg = sprintf( esc_html__('Invalid page ID given for %s', 'bip-pages'), $option);

    add_settings_error( $option, 'invalid-id', $msg );
  }
}

function sanitize_main_page_id( $value ) {
  return sanitize_id( $value, 'bip_pages_main_page_id' );
}

function sanitize_instruction_id( $value ) {
  return sanitize_id( $value, 'bip_pages_instruction_id' );
}

function sanitize_phone( $value ) {
        $phone = filter_var( $value, FILTER_SANITIZE_NUMBER_INT );
        $phone = str_replace("-", "", $phone );

        $length = strlen( $phone );

        if ( $length == 9 ) {
          // assume Polish number for now
          return '+48' . $phone;
        } elseif ( $length == 12 && strpos( $phone, '+' ) === 0 ) {
          return $phone;
        } elseif ( $length == 13 && strpos( $phone, '00' ) === 0 ) {
          return $phone;
        } else {
          add_settings_error( 'bip_pages_phone', $option, esc_html__('Invalid phone number given.', 'bip-pages') );
        }
}

function validate_access_role( $role ) {
  return get_role( $role ) ? $role : '';
}

function main_page_id_callback() {
  $args = [
    'show_option_none' => esc_html__('Not selected', 'bip-pages'),
    'option_none_value' => 0,
    'name' => 'bip_pages_main_page_id',
    'id' => 'bip_pages_main_page_id',
    'selected' => \BipPages\get_bip_main_page(),
    'post_type' => 'bip'
  ];
  wp_dropdown_pages( $args );

  if ( !empty( \BipPages\get_bip_main_page() ) ) {
    edit_post_link(
      __('Edit BIP main page', 'bip-pages' ),
      '',
      '',
      \BipPages\get_bip_main_page(),
      'button button-secondary'
    );
  }
}

function instruction_page_callback() {
  $args = [
    'show_option_none' => esc_html__('Not selected', 'bip-pages'),
    'option_none_value' => 0,
    'name' => 'bip_pages_instruction_id',
    'id' => 'bip_pages_instruction_id',
    'selected' => \BipPages\get_bip_instruction_page(),
    'post_type' => 'bip'
  ];
  wp_dropdown_pages( $args );

  if ( !empty( \BipPages\get_bip_instruction_page() ) ) {
    edit_post_link(
      __('Edit BIP instruction page', 'bip-pages' ),
      '',
      '',
      \BipPages\get_bip_instruction_page(),
      'button button-secondary'
    );
  }
}

function main_page_org_name_callback() {
  build_input('bip_pages_org_name', 'text', esc_attr__('Association...', 'bip-pages'));
}

function main_page_address_callback() {
  $option = 'bip_pages_address';
  $placeholder = esc_attr__( 'The address of your organization', 'bip-pages' );

  $element = "<textarea
               id='{$option}' name='{$option}'
               placeholder='{$placeholder}' >";
  $element .= '%s';
  $element .= '</textarea>';

  printf( $element, get_option( $option, '' ) );
}

function main_page_rep_callback() {
  build_input('bip_pages_editor', 'text', esc_attr__('Full name of a BIP editor', 'bip-pages'));
}

function main_page_email_callback() {
  build_input('bip_pages_email',
    'email',
    esc_attr__('Email to a BIP editor', 'bip-pages')
  );
}

function phone_callback() {
  build_input(
    'bip_pages_phone',
    'tel',
    esc_attr__('Phone number to your organization', 'bip-pages'),
    '[0-9 +]+'
  );
}

/** add settings link to plugins **/
add_filter('plugin_action_links_' . plugin_basename(__FILE__), __NAMESPACE__ . '\action_links' );
function action_links( $links, $plugin_file ) {
  $settings_url = get_settings_url();
  $links[] = "<a href='{$settings_url}'>" . esc_html__( 'Settings', 'bip-pages' ) . "</a>";
  return $links;
}

/** auxiliary functions **/
function get_settings_url( Array $options = array() ) {
  $query = http_build_query(
    array_merge( ['post_type' => 'bip', 'page' => PAGE_NAME], $options )
  );

  return admin_url( 'edit.php?' . $query );
}

function build_input( $option, $type = 'text', $placeholder = '', $pattern = false ) {
  $value = esc_attr( get_option( $option ) );
  $id_safe = esc_attr( $option );

  $element = "<input type='{$type}' value='{$value}'
               id='{$id_safe}' name='{$id_safe}'
               placeholder='{$placeholder}' ";
  $element .= $pattern ? "pattern='{$pattern}'" : '';
  $element .= "/>";

  echo $element;
}
