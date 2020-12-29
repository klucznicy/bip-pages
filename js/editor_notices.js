window.onload = (event) => {
    el = '<div class="bip-footer-container components-notice is-info is-dismissible">' +
      wp.i18n.__( 'A footer containing the author and publication date will be added here automatically.', 'bip-pages' ) +
      '</div>';
    jQuery('.edit-post-visual-editor').append( el );
    wf = jQuery('.editor-writing-flow');
    pt = jQuery('.editor-post-title');
    footer = jQuery('.bip-footer-container');
};
