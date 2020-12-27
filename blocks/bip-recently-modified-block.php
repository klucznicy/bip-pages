<?php

defined( 'ABSPATH' ) || exit;

function recently_modified_register_block() {

	if ( ! function_exists( 'register_block_type' ) ) {
		// Gutenberg is not active.
		return;
	}

	wp_register_script(
		'bip-recently-modified-block',
    plugins_url( 'bip-recently-modified-block.js', __FILE__ ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'bip-recently-modified-block.js' )
	);

	register_block_type( 'bip-pages/recently-modified', array(
		'editor_script' => 'bip-recently-modified-block',
		'render_callback' => 'recently_modified_dynamic_render_callback'
	) );

  if ( function_exists( 'wp_set_script_translations' ) ) {
    /**
     * May be extended to wp_set_script_translations( 'my-handle', 'my-domain',
     * plugin_dir_path( MY_PLUGIN ) . 'languages' ) ). For details see
     * https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
     */
    wp_set_script_translations( 'bip-recently-modified-block', 'bip-pages' );
  }

}
add_action( 'init', 'recently_modified_register_block' );

function recently_modified_dynamic_render_callback( $block_attributes, $content ) {
		ob_start();
		include( __DIR__ . '/../templates/bip-recently-modified-template.php' );
		$el = ob_get_clean();

    return $el;
}
