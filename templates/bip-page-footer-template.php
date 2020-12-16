<footer class="entry-footer bip-footer">
  <p>
    <?= sprintf(
      esc_html__( 'Information prepared by: %s', 'bip-pages' ), $prepared_by
    ) ?>
  </p>
  <p>
    <?= sprintf( esc_html__( 'Published by: %s', 'bip-pages' ), get_the_author_link() ) ?>
  </p>
  <p>
    <?= sprintf( esc_html__( 'Page created: %s', 'bip-pages' ), $creation_tag ) ?>
  </p>
  <p>
    <?= sprintf( esc_html__( 'Last updated: %s', 'bip-pages' ), $last_modification_tag ) ?>
  </p>
</footer>
