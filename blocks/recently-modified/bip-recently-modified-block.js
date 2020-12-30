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
			return el( 'div', {}, [
				el( 'h3', { className: 'bip-recently-modified-header' }, __( 'Recently updated BIP pages', 'bip-pages' ) ),
				el( 'ul', { className: 'bip-recently-modified-list' }, [
					el( 'li', {},
						el( 'span', { className: 'bip-recently-modified-placeholder' }, '1' )
					),
					el( 'li', {},
						el( 'span', { className: 'bip-recently-modified-placeholder' }, '2' )
					),
					el( 'li', {},
						el( 'span', { className: 'bip-recently-modified-placeholder' }, '3' )
					)
				])
			])


		}
	} );
} )( window.wp.blocks, window.wp.i18n, window.wp.element );
