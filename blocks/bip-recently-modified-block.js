( function( blocks, i18n, element ) {
	var el = element.createElement;
	var __ = i18n.__;

	blocks.registerBlockType( 'bip-pages/recently-modified', {
		title: __( 'Recently Modified BIP Pages', 'bip-pages' ),
		icon: 'clock',
		category: 'bip',
		example: {},
    save: function() { return null; },
		edit: function() {
			return el( 'p', {}, __( 'Recently updated BIP pages', 'bip-pages' ) );
		}
	} );
} )( window.wp.blocks, window.wp.i18n, window.wp.element );
