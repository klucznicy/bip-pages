<footer class="entry-footer bip-footer">
  <p>
    <?= sprintf(
      /* translators: %s is the name of the original author of page contents */
      esc_html__( 'Information prepared by: %s', 'bip-pages' ), $prepared_by
    ) ?>
  </p>
  <p>
    <?php
      /* translators: %s is the name of the user who published the page (may be a link) */
      echo sprintf( esc_html__( 'Published by: %s', 'bip-pages' ), get_the_author_link() );
    ?>
  </p>
  <p>
    <?php
      /* translators: %s is the date and time of page creation */
      echo sprintf( esc_html__( 'Page created: %s', 'bip-pages' ), $creation_tag )
    ?>
  </p>
  <p>
    <?php
      /* translators: %s is the date and time of last page modification */
      echo sprintf( esc_html__( 'Last updated: %s', 'bip-pages' ), $last_modification_tag ) ?>
  </p>
</footer>
