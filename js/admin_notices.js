jQuery(document).ready(function(){
  jQuery( '.bip-pages-display-username-invalid-notice.is-dismissible button' ).click(function() {
    jQuery.post({
      url: ajaxurl,
      data: {
        action: 'bip_pages_dismiss_notice',
        notice: 'display-username-invalid'
      }
    });
  });
});
