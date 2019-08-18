<?php
// @TODO 1) logo (znak graficzny) BIP, umieszczone w górnej części strony;

// @TODO automatically add logo widget on activation?

class BIP_Logo_Widget extends WP_Widget {

    function __construct() {

        parent::__construct(
            'bip-logo',  // Base ID
            'BIP Logo Widget'   // Name
        );

        // @TODO move widget init out of class definition
        add_action( 'widgets_init', function() {
            register_widget( 'BIP_Logo_Widget' );
        });
    }

    public $args = array(
        'before_widget' => '<div class="widget-wrap">',
        'after_widget'  => '</div></div>'
    );

    public function widget( $args, $instance ) {

        $options = get_option( BipPages\Settings\OPTION_NAME );
        // @TODO error handling
        $bip_main_page = get_post( $options['id'] );
        $bip_main_page_url = get_permalink( $bip_main_page );

        $alt = __('BIP naszej organizacji', 'bip-pages');

        echo $args['before_widget'];

        echo '<div class="textwidget">';
        echo "<a href='{$bip_main_page_url}'>";

        switch ($instance['image_type']) {
          case 1:
            // option 1: logo + text
            echo "<img src='" . plugin_dir_url( __FILE__ ) . "assets/bip-logos/bip-simple-color_min.png' alt='{$alt}' />";
            break;

          default:
            echo "<img src='" . plugin_dir_url( __FILE__ ) . "assets/bip-logos/bip-simple-color_min.png' alt='{$alt}' />";
            break;
        }

        echo '</a>';
        echo '</div>';

        echo $args['after_widget'];

    }

    public function form( $instance ) {

        $text = ! empty( $instance['image_type'] ) ? $instance['image_type'] : 0;
        ?>
        <p>
            <label for="<?= esc_attr( $this->get_field_id( 'image_type' ) ); ?>">
              <?= esc_html__( 'Image type:', 'bip-pages' ); ?>
            </label>
            <input type="radio"
              id="<?= esc_attr( $this->get_field_id( 'image_type' ) ); ?>"
              name="<?= esc_attr( $this->get_field_name( 'image_type' ) ); ?>"
            />
        </p>
        <?php

    }

    public function update( $new_instance, $old_instance ) {

        $instance = array();

        $instance['image_type'] = !empty( $new_instance['image_type'] ) ? $new_instance['image_type'] : 0;

        return $instance;
    }

}
$my_widget = new BIP_Logo_Widget();
