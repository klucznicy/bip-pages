<footer class="entry-footer bip-footer">
  <p>
    <?= _e( sprintf('Information prepared by: %s', get_the_author_link() ), 'bip-pages' ) ?>
  </p>
  <p>
    <?= _e( sprintf('Published by: %s', get_the_author_link() ), 'bip-pages' ) ?>
  </p>
  <p>
    <?= _e( sprintf(
      'Page created: <time>%s at %s</time>', get_the_date(), get_the_time()
    ), 'bip-pages' ) ?>
  </p>
  <p>
    <?= _e( sprintf(
      'Last updated: <time>%s at %s</time>', get_the_modified_date(), get_the_modified_time()
    ), 'bip-pages' ) ?>
  </p>
</footer>
