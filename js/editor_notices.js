( function( wp ) {
  msg = wp.i18n.__( 'You are editing the BIP main page.', 'bip-pages' ) + ' ' +
      wp.i18n.__( 'Parts of this page are automatically generated. The text you enter below will be displayed between the automatic BIP header and footer.', 'bip-pages' );

  wp.data.dispatch('core/notices').createWarningNotice(
    msg,
    { isDismissible: true, }
  );
} )( window.wp );
