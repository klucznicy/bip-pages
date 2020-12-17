<?php
namespace BipPages;
/**
 * BIP for Wordpress
 *
 * @package     bip-for-wordpress
 * @author      Łukasz Garczewski
 * @copyright   2019-2020 Łukasz Garczewski
 * @license     GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: BIP for WordPress
 * Plugin URI: https://klucznicy.org.pl/oprogramowanie/bip-for-wordpress/
 * Description: A plugin adding BIP (Biuletyn Informacji Publicznej) functionality to WordPress
 * Version: 1.1.0
 * Author: Stowarzyszenie Klucznicy
 * Author URI: https://klucznicy.org.pl/
 * Text Domain: bip-pages
 * Domain Path: /languages
 * License: GPL v3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 */

function plugin_init() {
  load_plugin_textdomain(
    'bip-pages',
    false,
    basename( dirname( __FILE__ ) ) . '/languages'
  );

  include_submodules();

  add_action('wp_enqueue_scripts', __NAMESPACE__ . '\register_css');
  add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\register_css' );
}
add_action('plugins_loaded', __NAMESPACE__ . '\plugin_init');

register_activation_hook( __FILE__, __NAMESPACE__ . '\activate' );
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\deactivate' );

function include_submodules() {
  include( 'bip-pages-main-page.php' );
  include( 'bip-pages-settings.php' );
  include( 'bip-pages-styling.php' );
  include( 'bip-logo-widget.php' );
  include( 'bip-pages-meta-boxes.php' );
  include( 'bip-pages-activation.php' );
}

function activate() {
  add_option('Activated_Plugin','bip-pages'); // deleted later in post_activation_flow

  add_option( 'bip-pages-edit-access-role', 'contributor' );
  add_option( 'bip-pages-publish-access-role', 'editor' );
  add_option( 'bip-pages-delete-access-role', 'editor' );

  include_submodules();

  create_main_page();
  create_instructions_page();
  add_logo_widget();
}

function deactivate() {
  include( 'bip-pages-deactivation.php' );

  set_transient( 'bip-pages-deactivation-msg', true, 5 );

  remove_widgets();
  convert_page_types();
}

function register_css() {
  wp_enqueue_style( 'bip-pages', plugin_dir_url( __FILE__ ) . 'css/style.css' );
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

function change_bip_template( $single_template ) {
  $post = get_post();

	if ( 'bip' == $post->post_type ) {
    $page_template = get_template_directory() . '/page.php';
    if ( file_exists( $page_template ) ) {
		  $single_template = $page_template;
    }
	}

	return $single_template;
}
add_filter('single_template', __NAMESPACE__ . '\change_bip_template');

function add_footer( $content ) {
  $post = get_post();

  $custom = get_post_custom( $post->ID );

  if ( !empty( $custom[ "_bip_prepared_by" ] ) && !empty( $custom[ "_bip_prepared_by" ][ 0 ] ) ) {
    $prepared_by = $custom[ "_bip_prepared_by" ][ 0 ];
  } else {
    $prepared_by = get_the_author_link();
  }

  $creation_time = get_the_date( 'c' );
  $last_mod_time = get_the_modified_date( 'c' );

  $creation_tag = "<time datetime='{$creation_time}'>";
  $creation_tag .= sprintf(
    esc_html( '%s at %s', 'bip-pages' ),
    get_the_date(),
    get_the_time()
  );
  $creation_tag .= '</time>';

  $last_modification_tag = "<time datetime='{$last_mod_time}'>";
  $last_modification_tag .= sprintf(
    esc_html( '%s at %s', 'bip-pages' ),
    get_the_modified_date(),
    get_the_modified_time()
  );
  $last_modification_tag .= '</time>';

  if ( is_single() && $post->post_type == 'bip' && $post->ID != get_bip_main_page() ) {
    ob_start();
    include( __DIR__ . '/templates/bip-page-footer-template.php' );
    $content .= ob_get_clean();
  }

  return $content;
}
add_filter('the_content', __NAMESPACE__ . '\add_footer' );

/** main page **/
function get_bip_main_page() {
  return Settings\get_option_value( 'id' );
}

function set_bip_main_page( $id ) {
  return Settings\set_option_value( 'id', $id );
}

/** instruction page **/
function get_bip_instruction_page() {
  return Settings\get_option_value( 'instruction_id' );
}

function set_bip_instruction_page( $id ) {
  return Settings\set_option_value( 'instruction_id', $id );
}
