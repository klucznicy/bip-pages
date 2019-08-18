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

const PAGE_TEMPLATE_NAME = 'bip-page-template';
// @TODO create i18n file
// @TODO translate into Polish

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

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\register_css');
function register_css() {
  wp_enqueue_style( PAGE_TEMPLATE_NAME, plugin_dir_url( __FILE__ ) . 'css/style.css' );
}

/** BIP Page activation flow **/
register_activation_hook( __FILE__, __NAMESPACE__ . '\activate' );
function activate() {
  add_option('Activated_Plugin','bip-pages');
  create_main_page();
  add_logo_widget();
}

function create_main_page() {
  $title = 'BIP Main Page';

  // @TODO check if main page already exists

  $current_user = wp_get_current_user();

  // create main page for BIP
  $page_args = array(
    'post_title'    => wp_strip_all_tags( $title ),
    'post_content'  => '',
    'post_status'   => 'publish',
    'post_author'   => $current_user->ID,
    'post_type'     => 'bip',
  );

  $main_page_id = wp_insert_post( $page_args );
  if ( empty( $main_page_id ) ) {
    // @FIXME throw error, something went horribly wrong
  }

  set_bip_main_page( $main_page_id );

  return true;
}

function add_logo_widget() {
  // define widget options
  // @TODO check if option already exists
  $widget_options = array(
    1 => array(
      'image_type' => 1,
    ),
  );
  update_option( 'widget_bip-logo', $widget_options );

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
        'name'                  => _x( 'BIP pages', 'Post type general name', 'textdomain' ),
        'singular_name'         => _x( 'BIP page', 'Post type singular name', 'textdomain' ),
        'menu_name'             => _x( 'BIP Pages', 'Admin Menu text', 'textdomain' ),
        'name_admin_bar'        => _x( 'Book', 'Add New on Toolbar', 'textdomain' ),
        'add_new'               => __( 'Add New', 'textdomain' ),
        'add_new_item'          => __( 'Add New BIP page', 'textdomain' ),
        'new_item'              => __( 'New BIP page', 'textdomain' ),
        'edit_item'             => __( 'Edit BIP page', 'textdomain' ),
        'view_item'             => __( 'View Book', 'textdomain' ),
        'all_items'             => __( 'All BIP pages', 'textdomain' ),
        'search_items'          => __( 'Search BIP pages', 'textdomain' ),
        'parent_item_colon'     => __( 'Parent BIP pages:', 'textdomain' ),
        'not_found'             => __( 'No BIP pages found.', 'textdomain' ),
        'not_found_in_trash'    => __( 'No BIP pages found in Trash.', 'textdomain' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'capability_type'    => 'post', // @TODO fix this access control – replace with bip
        'has_archive'        => true,
        'hierarchical'       => true,
        'menu_icon'          => plugin_dir_url( __FILE__ ) . 'assets/bip-settings-icon.png',
        'menu_position'      => 21,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
        'delete_with_user'   => false
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
  $option = get_option( OPTION_NAME, array() );
  $option['id'] = $id;
  update_option( OPTION_NAME, $option );
}

function add_basic_main_page_data( $content ) {
  $post = get_post();
  if ( $post->ID == get_bip_main_page() ) {
    ob_start();
    require( __DIR__ . '/templates/bip-main-template.php' );
    $content = ob_get_clean() . $content;
  }

  return $content;
}
add_filter('the_content', __NAMESPACE__ . '\add_basic_main_page_data' );

/** auxiliary **/
add_filter( 'display_post_states', __NAMESPACE__ . '\mark_bip_main_page', 10, 2 );
function mark_bip_main_page( $post_states, $post ) {
  if( $post->ID == get_bip_main_page() ) {
  	$post_states[] = __( 'BIP Main Page' );
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
