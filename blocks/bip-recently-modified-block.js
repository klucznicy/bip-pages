( function( blocks, i18n, element ) {
	var el = element.createElement;
	var __ = i18n.__;

	blocks.registerBlockType( 'bip-pages/recently-modified', {
		title: __( 'BIP Recently Modified', 'bip-pages' ),
		icon: 'clock',
		category: 'bip',
		example: {},
    supports: {multiple: false},
    save: function() { return null; },
		edit: function() {
			return el( 'p', {}, 'Recently Modified' );
		}
	} );
} )( window.wp.blocks, window.wp.i18n, window.wp.element );
