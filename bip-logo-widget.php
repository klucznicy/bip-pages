<?php
class BIP_Logo_Widget extends WP_Widget {

    var $image_variants = array(
      'full',
      'simple'
    );

    var $image_colors = array(
      'color',
      'gray',
      'mono'
    );

    var $image_languages = array( 'pl', 'en' );

    function __construct() {

        parent::__construct(
            'bip-logo',  // Base ID
            __( 'BIP Logo Widget', 'bip-pages' )   // Name
        );

    }

    public $args = array(
        'before_widget' => '<div class="widget-wrap">',
        'after_widget'  => '</div>'
    );

    public function widget( $args, $instance ) {
        $bip_main_page_url = esc_url( get_permalink( BipPages\get_bip_main_page() ) );

        $alt = esc_attr__('BIP naszej organizacji', 'bip-pages');

        echo $args['before_widget'];

        echo "<a href='{$bip_main_page_url}'>";

        $instance = $this->set_defaults( $instance );

        $image = 'bip-' . $instance['variant'] . '-' . $instance['color'] . '-' . $instance['language'];

        echo "<img src='" . plugin_dir_url( __FILE__ ) . "assets/bip-logos/{$image}_500px.png' alt='{$alt}' />";
        echo '</a>';

        echo $args['after_widget'];
    }

    public function form( $instance ) {
        echo '<p>' . esc_html__( 'This widget displays the BIP logo with a link to your BIP main page.', 'bip-pages') . '</p>';

        $instance = $this->set_defaults( $instance );

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
      <?= $instance['language'] == $language ? 'checked' : '' ?>
    >
    <label><?= esc_html__( $language, 'bip-pages' ); ?></label>
  <?php } ?>
</fieldset>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();

        if ( in_array( $new_instance['variant'], $this->image_variants ) ) {
          $instance['variant'] = $new_instance['variant'];
        } else {
          $instance['variant'] = $old_instance['variant'];
        }

        if ( in_array( $new_instance['color'], $this->image_colors ) ) {
          $instance['color'] = $new_instance['color'];
        } else {
          $instance['color'] = $old_instance['color'];
        }

        if ( in_array( $new_instance['language'], $this->image_languages ) ) {
          $instance['language'] = $new_instance['language'];
        } else {
          $instance['language'] = $old_instance['language'];
        }

        return $instance;
    }

    private function set_defaults( $instance ) {
      // @TODO this is silly, hope there's a more sensible way to set defaults

      if ( empty( $instance['variant'] ) ) {
        $instance['variant'] = $this->image_variants[0];
      }

      if ( empty( $instance['color'] ) ) {
        $instance['color'] = $this->image_colors[0];
      }

      if ( empty( $instance['language'] ) ) {
        $instance['language'] = $this->image_languages[0];
      }

      return $instance;
    }
}
add_action( 'widgets_init', function() {
    register_widget( 'BIP_Logo_Widget', 'bip-pages' );
});
