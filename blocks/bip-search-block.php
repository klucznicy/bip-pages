<?php

defined( 'ABSPATH' ) || exit;

function search_register_block() {

	if ( ! function_exists( 'register_block_type' ) ) {
		// Gutenberg is not active.
		return;
	}

	wp_register_script(
		'bip-search-block',
    plugins_url( 'bip-search-block.js', __FILE__ ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'bip-search-block.js' )
	);

	wp_register_style(
  		'bip-search-block-editor',
  		plugins_url( 'bip-search-block-editor.css', __FILE__ ),
  		array( 'wp-edit-blocks' ),
  		filemtime( plugin_dir_path( __FILE__ ) . 'bip-search-block-editor.css' )
  	);

	register_block_type( 'bip-pages/search', array(
		'editor_script' => 'bip-search-block',
		'editor_style' => 'bip-search-block-editor',
		'render_callback' => 'search_dynamic_render_callback'
	) );

  if ( function_exists( 'wp_set_script_translations' ) ) {
    /**
     * May be extended to wp_set_script_translations( 'my-handle', 'my-domain',
     * plugin_dir_path( MY_PLUGIN ) . 'languages' ) ). For details see
     * https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
     */
    wp_set_script_translations( 'bip-search-block', 'bip-pages' );
  }

}
add_action( 'init', 'search_register_block' );

function search_dynamic_render_callback( $block_attributes, $content ) {

	$options = get_option( BipPages\Settings\OPTION_NAME );

	ob_start();
	include( __DIR__ . '/../templates/bip-search-form.php' );
	$el = ob_get_clean();

    return $el;
}
