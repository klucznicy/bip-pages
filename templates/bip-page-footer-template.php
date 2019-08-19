<footer class="entry-footer bip-footer">
  <p>
    <?= sprintf(
      __( 'Information prepared by: %s', 'bip-pages' ), get_the_author_link() ) ?>
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
