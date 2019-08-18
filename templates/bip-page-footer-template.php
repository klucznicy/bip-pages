<!-- bip footer -->
<footer class="entry-footer">
  <p>
    <?php // @TODO change prep by link to be distinct from author link ?>
    <?= _e( sprintf('Information prepared by: %s', get_the_author_link() ), 'bip-pages' ) ?>
  </p>
  <p>
    <?= _e( sprintf('Published by: %s', get_the_author_link() ), 'bip-pages' ) ?>
  </p>
  <p>
    <?= _e( sprintf(
      'Date of page creation: %s at %s', get_the_date(), get_the_time()
    ), 'bip-pages' ) ?>
  </p>
  <p>
    <?= _e( sprintf(
      'Date last updated: %s at %s', get_the_modified_date(), get_the_modified_time()
    ), 'bip-pages' ) ?>
  </p>
</footer>
