<p>
  <?= esc_html__( 'This widget displays the BIP logo with a link to your BIP main page.', 'bip-pages') ?>
</p>

<?php
    include( 'bip-logo-widget-template.php' );
?>

<fieldset>
  <legend><?= esc_html__('Variant', 'bip-pages'); ?></legend>

<?php
  foreach ( $this->image_variants as $variant ) {
?>
  <input type="radio" value="<?= esc_attr( $variant ); ?>"
    id="<?= esc_attr( $this->get_field_id( 'variant' ) ); ?>"
    name="<?= esc_attr( $this->get_field_name( 'variant' ) ); ?>"
    <?= $instance['variant'] == $variant ? 'checked' : '' ?>
  >
  <label><?= esc_html__( $variant, 'bip-pages' ); ?></label>
<?php } ?>
</fieldset>

<fieldset>
  <legend><?= esc_html__('Color','bip-pages') ?></legend>

  <?php
    foreach ( $this->image_colors as $color ) {
  ?>
    <input type="radio" value="<?= esc_attr( $color ); ?>"
      id="<?= esc_attr( $this->get_field_id( 'color' ) ); ?>"
      name="<?= esc_attr( $this->get_field_name( 'color' ) ); ?>"
      <?= $instance['color'] == $color ? 'checked' : '' ?>
    >
    <label><?= esc_html__( $color, 'bip-pages' ); ?></label>
  <?php } ?>
</fieldset>

<fieldset>
  <legend><?= esc_html__('Language','bip-pages'); ?></legend>

  <?php
    foreach ( $this->image_languages as $language ) {
  ?>
    <input type="radio" value="<?= esc_attr( $language ); ?>"
      id="<?= esc_attr( $this->get_field_id( 'language' ) ); ?>"
      name="<?= esc_attr( $this->get_field_name( 'language' ) ); ?>"
      <?= !empty( $instance['language'] ) && $instance['language'] == $language ? 'checked' : '' ?>
    >
    <label><?= esc_html__( $language, 'bip-pages' ); ?></label>
  <?php } ?>
</fieldset>
