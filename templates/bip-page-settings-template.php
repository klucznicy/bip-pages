<div class="wrap">
  <h1><?= esc_html__('BIP Pages Settings', 'bip-pages') ?></h1>
  <form method="post" action="options.php">
    <?php
    settings_fields( 'bip-pages' );
    do_settings_sections( 'bip-pages-admin' );
    submit_button();
    ?>
  </form>
</div>
