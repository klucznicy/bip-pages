( function( blocks, i18n, element ) {
	var el = element.createElement;
	var __ = i18n.__;

	blocks.registerBlockType( 'bip-pages/org-info', {
		title: __( 'BIP Org info', 'bip-pages' ),
		icon: 'id-alt',
		category: 'bip',
		example: {},
    supports: {multiple: false},
    save: function() { return null; },
		edit: function() {
			return el(
				'address',
				{ className: 'bip-address' },
				[
					el( 'p', {}, [
						__( 'Address:', 'bip-pages' ),
						el(
							'span',
							{ className: 'bip-org-info-placeholder' },
							'&nbsp;'
						)
					]),
					el( 'p', {}, [
						__( 'Editor:', 'bip-pages' ),
						el(
							'span',
							{ className: 'bip-org-info-placeholder' },
							'&nbsp;'
						)
					]),
					el( 'p', {}, [
						__( 'E-mail address:', 'bip-pages' ),
						el(
							'span',
							{ className: 'bip-org-info-placeholder' },
							'&nbsp;'
						)
					]),
					el( 'p', {}, [
						__( 'Phone number:', 'bip-pages' ),
						el(
							'span',
							{ className: 'bip-org-info-placeholder' },
							'&nbsp;'
						)
					]),
				]
			);
		}
	} );
} )( window.wp.blocks, window.wp.i18n, window.wp.element );
