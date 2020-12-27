( function( blocks, i18n, element ) {
	var el = element.createElement;
	var __ = i18n.__;

	blocks.registerBlockType( 'bip-pages/search', {
		title: __( 'BIP Search', 'bip-pages' ),
		icon: 'search',
		category: 'bip',
		example: {},
    save: function() { return null; },
		edit: function() {
			return el(
				'form',
				{className: 'bip-search'},
				[
          el( 'label', {}, [
            el( 'input', {type: 'search', placeholder: __('Search BIP pages…', 'placeholder', 'bip-pages'), disabled: 'disabled' } ),
          ]),
          el( 'input', {type: 'submit', value: __( 'Search', 'submit button', 'bip-pages' ), disabled: 'disabled' } )
        ]
			);
		}
	} );
} )( window.wp.blocks, window.wp.i18n, window.wp.element );
