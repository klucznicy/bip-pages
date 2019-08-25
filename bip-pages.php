<?php
namespace BipPages;
/**
 * Plugin Name: BIP for WordPress
 * Description: A plugin adding BIP (Biuletyn Informacji Publicznej) functionality to WordPress
 * Version: 1.0
 * Author: Łukasz Garczewski
 * Author URI: http://klucznicy.org.pl/open-source/
 * Text Domain: bip-pages
 * Domain Path: /languages
 */

/** Basic Plugin Config **/
add_action('plugins_loaded', __NAMESPACE__ . '\plugin_init');
function plugin_init() {
  load_plugin_textdomain(
    'bip-pages',
    false,
    plugin_basename( __FILE__ ) . '/languages'
  );

  /** Include submodules **/
  include( 'bip-pages-settings.php' );
  include( 'bip-logo-widget.php' );
}

/** add settings link to plugins **/
add_filter('plugin_action_links_' . plugin_basename(__FILE__), __NAMESPACE__ . '\action_links', 10, 2);
function action_links( $links, $plugin_file ) {
  $settings_url = Settings\get_settings_url();
  $links[] = "<a href='{$settings_url}'>" . __( 'Settings', 'bip-pages' ) . "</a>";
  return $links;
}

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\register_css');
function register_css() {
  wp_enqueue_style( 'bip-pages', plugin_dir_url( __FILE__ ) . 'css/style.css' );
}

/** BIP Page activation flow **/
register_activation_hook( __FILE__, __NAMESPACE__ . '\activate' );
function activate() {
  add_option('Activated_Plugin','bip-pages');
  create_main_page();
  create_instructions_page();
  add_logo_widget();
}

register_deactivation_hook( __FILE__, __NAMESPACE__ . '\deactivate' );
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
  $instructions = file_get_contents( 'bip-usage-manual-pl.txt' );

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
    if( !is_admin() || get_option('Activated_Plugin') != 'bip-pages' ) {
      return;
    }

    flush_rewrite_rules(); // we're adding a new page type slug
    delete_option('Activated_Plugin');
    wp_redirect( Settings\get_settings_url( ['plugin-activated' => 1] ) );
}

/** BIP page type registration **/
function register_bip_page_type() {
    $labels = array(
        'name'                  => _x( 'BIP pages', 'Post type general name', 'bip-pages' ),
        'singular_name'         => _x( 'BIP page', 'Post type singular name', 'bip-pages' ),
        'menu_name'             => _x( 'BIP Pages', 'Admin Menu text', 'bip-pages' ),
        'name_admin_bar'        => _x( 'BIP page', 'Add New on Toolbar', 'bip-pages' ),
        'add_new'               => __( 'Add New', 'bip-pages' ),
        'add_new_item'          => __( 'Add New BIP page', 'bip-pages' ),
        'new_item'              => __( 'New BIP page', 'bip-pages' ),
        'edit_item'             => __( 'Edit BIP page', 'bip-pages' ),
        'view_item'             => __( 'View BIP page', 'bip-pages' ),
        'all_items'             => __( 'All BIP pages', 'bip-pages' ),
        'search_items'          => __( 'Search BIP pages', 'bip-pages' ),
        'parent_item_colon'     => __( 'Parent BIP pages:', 'bip-pages' ),
        'not_found'             => __( 'No BIP pages found.', 'bip-pages' ),
        'not_found_in_trash'    => __( 'No BIP pages found in Trash.', 'bip-pages' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => true,
        'menu_icon'          => plugin_dir_url( __FILE__ ) . 'assets/bip-settings-icon.png',
        'menu_position'      => 21,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions' ),
        'delete_with_user'   => false,
        'show_in_rest'       => true
    );

    register_post_type( 'bip', $args );
}
add_action( 'init', __NAMESPACE__ . '\register_bip_page_type' );

/** main page **/
function get_bip_main_page() {
  $options = get_option( 'bip-pages' );
  return $options['id'];
}

function set_bip_main_page( $id ) {
  $option = get_option( Settings\OPTION_NAME, array() );
  $option['id'] = $id;
  update_option( Settigs\OPTION_NAME, $option );
}

function get_bip_instruction_page() {
  $options = get_option( 'bip-pages' );
  return isset( $options['instruction_id'] ) ? $options['instruction_id'] : false;
}

function set_bip_instruction_page( $id ) {
  $option = get_option( Settings\OPTION_NAME, array() );
  $option['instruction_id'] = $id;
  update_option( Settigs\OPTION_NAME, $option );
}

function add_basic_main_page_data( $content ) {
  global $bip_main_page_content, $bip_instruction_url;

  $bip_main_page_content = $content;

  $bip_instruction_url = get_permalink( get_bip_instruction_page() );

  $post = get_post();
  if ( $post->ID == get_bip_main_page() ) {
    ob_start();
    require( __DIR__ . '/templates/bip-main-template.php' );
    $content = ob_get_clean();
  }

  return $content;
}
add_filter('the_content', __NAMESPACE__ . '\add_basic_main_page_data' );

/** display and styling **/
function add_title_container( $title, $id = null ) {
    $post = get_post( $id );
    if ( is_singular( 'bip' ) || ( is_search() && $post->post_type == 'bip' ) ) {
        $title = "<span class='bip-title-container'>" . $title . "</span>";
    }

    return $title;
}
add_filter( 'the_title', __NAMESPACE__ . '\add_title_container', 10, 2 );

function add_post_class( $classes, $class = '', $post_id = '' ) {
  $post = get_post();
  if ( $post->post_type == 'bip' ) {
    $classes[] = 'type-page';
  }

  return $classes;
}
add_filter( 'post_class', __NAMESPACE__ . '\add_post_class' );

/** auxiliary **/
add_filter( 'display_post_states', __NAMESPACE__ . '\mark_bip_page_states', 10, 2 );
function mark_bip_page_states( $post_states, $post ) {
  switch ( $post->ID ) {
    case get_bip_main_page():
      $post_states[] = __( 'BIP Main Page', 'bip-pages' );
      break;
    case get_bip_instruction_page():
      $post_states[] = __( 'BIP Instruction Page', 'bip-pages' );
      break;
  }

	return $post_states;
}

function add_footer( $content ) {
  $post = get_post();

  if ( $post->post_type == 'bip' && is_single() ) {
    ob_start();
    include( __DIR__ . '/templates/bip-page-footer-template.php' );
    $content .= ob_get_clean();
  }

  return $content;
}
add_filter('the_content', __NAMESPACE__ . '\add_footer' );

add_filter('single_template', __NAMESPACE__ . '\change_bip_template');
function change_bip_template( $single_template ) {
  global $post;

	if ( 'bip' === $post->post_type ) {
    $page_template = get_template_directory() . '/page.php';
    if ( file_exists( $page_template ) ) {
		  $single_template = $page_template;
    }
	}

	return $single_template;
}

function is_bip_main_page_edit_screen() {
  return isset( $_GET['action'] ) &&
      $_GET['action'] == 'edit' &&
      isset( $_GET['post'] ) &&
      $_GET['post'] == get_bip_main_page();
}

function main_page_edit_notice() {
  if ( is_bip_main_page_edit_screen() ) {
    $message = '<p>' . __( 'You are editing the BIP main page.', 'bip-pages' ) . '</p>' .
      '<p>' . __( 'Parts of this page are automatically generated. The text you enter below will display beetween the automatic header and footer.', 'bip-pages' ) . '</p>';
    echo "<div class='notice notice-info is-dismissible'>{$message}</div>";
  }
}
add_action( 'admin_notices', __NAMESPACE__ . '\main_page_edit_notice' );

function enqueue_editor_notices() {
  if ( is_bip_main_page_edit_screen() ) {
    wp_enqueue_script(
          'bip-editor-notices',
          plugin_dir_url( __FILE__ ) . '/js/editor_notices.js',
          array( 'wp-notices', 'wp-i18n', 'wp-editor' )
      );
      $script_params = [
        'currently_edited_post' => $_GET['post'],
        'bip_main_page_id' => get_option( Settings\OPTION_NAME )['id']
      ];
      wp_localize_script( 'bip-editor-notices', 'scriptParams', $script_params );
      wp_set_script_translations( 'bip-editor-notices', 'bip-pages' );
    }
}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\enqueue_editor_notices' );
