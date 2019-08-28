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
    if (isset($_GET['settings-updated'])) {
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
  foreach ( $input as $option => $value ) {
    switch ( $option ) {
      case 'id':
      case 'instruction_id':
        if ( is_numeric( $value ) && /* post exists? */ get_post_status( $value ) !== false ) {
          $sanitized_input[$option] = $value;
        } else {
          // @TODO display error message here
        }
        break;
      case 'email':
        $sanitized_input['email'] = sanitize_email( $input['email'] );
        break;
      case 'phone':
        // @TODO add sanitize function for phone number
      default:
        $sanitized_input[$option] = sanitize_text_field( $value );
    }
  }

  return $sanitized_input;
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
  build_input('address', 'text', esc_html__('The address of your organization', 'bip-pages'));
}

function main_page_rep_callback() {
  build_input('rep', 'text', esc_html__('Full name of a BIP editor', 'bip-pages'));
}

function main_page_email_callback() {
  build_input('email',
    'email',
    esc_html__('Email to a BIP editor', 'bip-pages')
  );
}

function main_page_phone_callback() {
  build_input(
    'phone',
    'tel',
    esc_html__('Phone number to your organization', 'bip-pages'),
    '[0-9 +]+'
  );
}

/** add settings link to plugins **/
add_filter('plugin_action_links_' . plugin_basename(__FILE__), __NAMESPACE__ . '\action_links' );
function action_links( $links, $plugin_file ) {
  $settings_url = get_settings_url();
  $links[] = "<a href='{$settings_url}'>" . __( 'Settings', 'bip-pages' ) . "</a>";
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
