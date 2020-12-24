( function( wp ) {
  if ( bip_pages_vars['bip_current_id'] == bip_pages_vars['bip_main_page'] ) {
    msg = wp.i18n.__( 'You are editing the BIP main page.', 'bip-pages' ) + ' ' +
        wp.i18n.__( 'Parts of this page are automatically generated. The text you enter below will be displayed between the automatic BIP header and footer.', 'bip-pages' );

    wp.data.dispatch('core/notices').createInfoNotice(
      msg,
      { isDismissible: true, }
    );
  }
} )( window.wp );

window.onload = (event) => {
    el = '<div class="bip-footer-container components-notice is-info is-dismissible">' +
      wp.i18n.__( 'A footer containing the author and publication date will be added here automatically.', 'bip-pages' ) +
      '</div>';
    jQuery('.edit-post-visual-editor').append( el );
    wf = jQuery('.editor-writing-flow');
    pt = jQuery('.editor-post-title');
    footer = jQuery('.bip-footer-container');
};
