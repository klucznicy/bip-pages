<?php
namespace BipPages\Settings;

const PAGE_NAME = 'bip-pages-admin';
const OPTION_NAME = 'bip-pages';

function register_options_page() {
  add_submenu_page(
    'edit.php?post_type=bip',
    __( 'BIP Pages Settings', 'bip-pages' ),
    __( 'BIP Pages Settings', 'bip-pages' ),
    'manage_options',
    PAGE_NAME,
    __NAMESPACE__ . '\render_admin_page'
  );
}
add_action( 'admin_menu', __NAMESPACE__ . '\register_options_page' );

function render_admin_page() {
  include( "templates/bip-page-settings-template.php" );
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
    'bip-pages', // Option group
    OPTION_NAME, // Option name
    __NAMESPACE__ . '\sanitize'
  );

  register_setting(
    'bip-pages', // Option group
    'bip-pages-edit-access-role', // Option name
    __NAMESPACE__ . '\validate_access_role'
  );

  register_setting(
    'bip-pages', // Option group
    'bip-pages-publish-access-role', // Option name
    __NAMESPACE__ . '\validate_access_role'
  );

  register_setting(
    'bip-pages', // Option group
    'bip-pages-delete-access-role', // Option name
    __NAMESPACE__ . '\validate_access_role'
  );

  add_settings_section(
    'bip_pages_settings_main_page', // ID
    __('BIP Main Page settings', 'bip-pages'), // Title
    '', // Callback (for help text if needed)
    'bip-pages-admin' // Page
  );

  add_settings_field(
    'bip_pages_main_page_id', // ID
    __('BIP Main Page', 'bip-pages'), // Title
    __NAMESPACE__ . '\main_page_id_callback', // Callback
    PAGE_NAME, // Page
    'bip_pages_settings_main_page' // Section
  );

  add_settings_field(
    'bip_pages_main_page_address',
    __('Organization Address', 'bip-pages'),
    __NAMESPACE__ . '\main_page_address_callback',
    PAGE_NAME,
    'bip_pages_settings_main_page'
  );

  add_settings_field(
    'bip_pages_main_page_email',
    __('E-Mail Address', 'bip-pages'),
    __NAMESPACE__ . '\main_page_email_callback',
    PAGE_NAME,
    'bip_pages_settings_main_page'
  );

  add_settings_field(
    'bip_pages_main_page_phone',
    __('Phone Number', 'bip-pages'),
    __NAMESPACE__ . '\main_page_phone_callback',
    PAGE_NAME,
    'bip_pages_settings_main_page'
  );

  add_settings_field(
    'bip_pages_main_page_rep',
    __('Name of representative', 'bip-pages'),
    __NAMESPACE__ . '\main_page_rep_callback',
    PAGE_NAME,
    'bip_pages_settings_main_page'
  );

  add_settings_section(
    'bip_pages_settings_instruction_page', // ID
    __('BIP instruction page settings', 'bip-pages'), // Title
    '', // Callback (for help text if needed)
    'bip-pages-admin' // Page
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
*
* @param array $input Contains all settings fields as array keys
*/
function sanitize( $input ) {
  $sanitized_input = array();

  foreach ( $input as $option => $value ) {
    switch ( $option ) {
      case 'id':
      case 'instruction_id':
        if ( is_numeric( $value ) && /* post exists? */ get_post_status( $value ) !== false ) {
          $sanitized_input[$option] = $value;
        } else {
          /* translators: %s is internal option identifired, either "id" or "instruction_id" */
          $msg = sprintf( esc_html__('Invalid page ID given for %s', 'bip-pages'), $option);
          add_settings_error( OPTION_NAME, $option, $msg );
        }
        break;
      case 'address':
        $sanitized_input['address'] = sanitize_textarea_field( $input['address'] );
        break;
      case 'email':
        $sanitized_input['email'] = sanitize_email( $input['email'] );
        break;
      case 'phone':
        $phone = filter_var( $value, FILTER_SANITIZE_NUMBER_INT );
        $phone = str_replace("-", "", $phone );

        $length = strlen( $phone );

        if ( $length == 9 ) {
          // assume Polish number for now
          $sanitized_input['phone'] = '+48' . $phone;
        } elseif ( $length == 12 && strpos( $phone, '+' ) === 0 ) {
          $sanitized_input['phone'] = $phone;
        } elseif ( $length == 13 && strpos( $phone, '00' ) === 0 ) {
          $sanitized_input['phone'] = $phone;
        } else {
          add_settings_error( OPTION_NAME, $option, esc_html__('Invalid phone number given.', 'bip-pages') );
        }
        break;
      default:
        $sanitized_input[$option] = sanitize_text_field( $value );
    }
  }

  return $sanitized_input;
}

function validate_access_role( $role ) {
  return get_role( $role ) ? $role : '';
}

function main_page_id_callback() {
  $args = [
    'show_option_none' => esc_html__('Not selected', 'bip-pages'),
    'option_none_value' => 0,
    'name' => OPTION_NAME . "[id]",
    'id' => OPTION_NAME . "[id]",
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
    'name' => OPTION_NAME . "[instruction_id]",
    'id' => OPTION_NAME . "[instruction_id]",
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

function main_page_address_callback() {
  $id = 'address';
  $option = OPTION_NAME;
  $values = get_option( $option );
  $id_safe = esc_attr( $id );
  $placeholder = esc_attr__( 'The address of your organization', 'bip-pages' );

  $element = "<textarea
               id='{$option}[{$id_safe}]' name='{$option}[{$id_safe}]'
               placeholder='{$placeholder}' >";
  $element .= '%s';
  $element .= '</textarea>';

  printf( $element, !empty( $values[$id] ) ? esc_textarea( $values[$id] ) : '' );
}

function main_page_rep_callback() {
  build_input('rep', 'text', esc_attr__('Full name of a BIP editor', 'bip-pages'));
}

function main_page_email_callback() {
  build_input('email',
    'email',
    esc_attr__('Email to a BIP editor', 'bip-pages')
  );
}

function main_page_phone_callback() {
  build_input(
    'phone',
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

function build_input( $id, $type = 'text', $placeholder = '', $pattern = false ) {
  $option = OPTION_NAME;
  $values = get_option( $option );
  $id_safe = esc_attr($id);

  $element = "<input type='{$type}' value='%s'
               id='{$option}[{$id_safe}]' name='{$option}[{$id_safe}]'
               placeholder='{$placeholder}' ";
  $element .= $pattern ? "pattern='{$pattern}'" : '';
  $element .= "/>";

  printf( $element, !empty( $values[$id] ) ? $values[$id] : '' );
}

function get_option_value( $opt ) {
  $options = get_option( OPTION_NAME );
  return isset( $options[$opt] ) ? $options[$opt] : false;
}

function set_option_value( $opt, $value ) {
  $option = get_option( OPTION_NAME, array() );
  $option[$opt] = $value;
  return update_option( OPTION_NAME, $option );
}
