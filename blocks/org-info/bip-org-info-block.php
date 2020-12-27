<?php

defined( 'ABSPATH' ) || exit;

function org_info_register_block() {

	if ( ! function_exists( 'register_block_type' ) ) {
		// Gutenberg is not active.
		return;
	}

	wp_register_script(
		'bip-org-info-block',
    plugins_url( 'bip-org-info-block.js', __FILE__ ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'bip-org-info-block.js' )
	);

  wp_register_style(
  		'bip-org-info-block-editor',
  		plugins_url( 'bip-org-info-block-editor.css', __FILE__ ),
  		array( 'wp-edit-blocks' ),
  		filemtime( plugin_dir_path( __FILE__ ) . 'bip-org-info-block-editor.css' )
  	);

	register_block_type( 'bip-pages/org-info', array(
		'editor_script' => 'bip-org-info-block',
    'editor_style' => 'bip-org-info-block-editor',
		'render_callback' => 'org_info_dynamic_render_callback'
	) );

  if ( function_exists( 'wp_set_script_translations' ) ) {
    /**
     * May be extended to wp_set_script_translations( 'my-handle', 'my-domain',
     * plugin_dir_path( MY_PLUGIN ) . 'languages' ) ). For details see
     * https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
     */
    wp_set_script_translations( 'bip-org-info-block', 'bip-pages' );
  }

}
add_action( 'init', 'org_info_register_block' );

function org_info_dynamic_render_callback( $block_attributes, $content ) {
		$options = get_option( BipPages\Settings\OPTION_NAME );
		$bip_logo_url = plugin_dir_url( __FILE__ ) . '../../assets/bip-logos/bip-full-color-pl_min.png';

		ob_start();
		include( __DIR__ . '/../../templates/bip-org-info-template.php' );
		$el = ob_get_clean();

    return $el;
}
