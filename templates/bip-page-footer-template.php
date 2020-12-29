<footer class="entry-footer bip-footer">
  <p itemprop="author" itemscope itemtype="http://schema.org/Person">
    <?php
      $prepared_by_tag = "<span itemprop='name'>{$prepared_by}</span>";
      /* translators: %s is the name of the original author of page contents */
      printf( esc_html__( 'Information prepared by: %s', 'bip-pages' ), $prepared_by_tag );
    ?>
  </p>
  <p itemprop="publisher" itemscope itemtype="http://schema.org/Person">
    <?php
      $author_tag = "<span itemprop='name'>" . get_the_author_link() . '</span>';
      /* translators: %s is the name of the user who published the page (may be a link) */
      printf( esc_html__( 'Published by: %s', 'bip-pages' ), $author_tag );
    ?>
  </p>
  <p itemprop="datePublished">
    <?php
      /* translators: %s is the date and time of page creation */
      printf( esc_html__( 'Page created: %s', 'bip-pages' ), $creation_tag )
    ?>
  </p>
  <p itemprop="dateModified">
    <?php
      /* translators: %s is the date and time of last page modification */
      printf( esc_html__( 'Last updated: %s', 'bip-pages' ), $last_modification_tag ) ?>
  </p>
</footer>
