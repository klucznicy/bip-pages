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
        $instance = $this->set_defaults( $instance );

        $image_url = plugin_dir_url( __FILE__ ) . $this->get_image_filename( $instance );

        echo $args['before_widget'];

        include( 'templates/bip-logo-widget-template.php' );

        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $instance = $this->set_defaults( $instance );

        $image_url = plugin_dir_url( __FILE__ ) . $this->get_image_filename( $instance );

        include( 'templates/bip-logo-widget-options-template.php' );
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

        if ( $instance['variant'] == 'simple' ) {
          // simple variant does not support language
          // $instance['language'] = '';
        } elseif ( in_array( $new_instance['language'], $this->image_languages ) ) {
          $instance['language'] = $new_instance['language'];
        } else {
          $instance['language'] = $old_instance['language'];
        }

        if ( !file_exists( plugin_dir_path( __FILE__ ) . '/' . $this->get_image_filename( $instance ) ) ) {
          // @TODO display error to user
          return $old_instance;
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

      if ( $instance['variant'] != 'simple' && empty( $instance['language'] ) ) {
        $instance['language'] = $this->image_languages[0];
      }

      return $instance;
    }

    private function get_image_filename( $instance ) {
      return "assets/bip-logos/bip-" . implode( '-', $instance ) . "_500px.png";
    }
}
add_action( 'widgets_init', function() {
    register_widget( 'BIP_Logo_Widget', 'bip-pages' );
});
