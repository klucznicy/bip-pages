<?php

$custom = get_post_custom( $post->ID );
// @TODO: sanitize output properly
// @TODO: move business logic out of template
if ( !empty( $custom[ "_bip_prepared_by" ] ) && !empty( $custom[ "_bip_prepared_by" ][ 0 ] ) ) {
  $prepared_by = $custom[ "_bip_prepared_by" ][ 0 ];
} else {
  $prepared_by = get_the_author_link();
}

?>

<footer class="entry-footer bip-footer">
  <p>
    <?= sprintf(
      __( 'Information prepared by: %s', 'bip-pages' ), $prepared_by
    ) ?>
  </p>
  <p>
    <?= sprintf( __( 'Published by: %s', 'bip-pages' ), get_the_author_link() ) ?>
  </p>
  <p>
    <?= sprintf(
      __( 'Page created: <time>%s at %s</time>', 'bip-pages' ),
      get_the_date(), get_the_time()
    ) ?>
  </p>
  <p>
    <?= sprintf(
      __( 'Last updated: <time>%s at %s</time>', 'bip-pages' ),
      get_the_modified_date(), get_the_modified_time()
    ) ?>
  </p>
</footer>
