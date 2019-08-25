<footer class="entry-footer bip-footer">
  <p>
    <?= sprintf(
      esc_html__( 'Information prepared by: %s', 'bip-pages' ), get_the_author_link() ) ?>
  </p>
  <p>
    <?= sprintf( esc_html__( 'Published by: %s', 'bip-pages' ), get_the_author_link() ) ?>
  </p>
  <p>
    <?= sprintf(
      esc_html__( 'Page created: <time>%s at %s</time>', 'bip-pages' ),
      get_the_date(), get_the_time()
    ) ?>
  </p>
  <p>
    <?= sprintf(
      esc_html__( 'Last updated: <time>%s at %s</time>', 'bip-pages' ),
      get_the_modified_date(), get_the_modified_time()
    ) ?>
  </p>
</footer>
