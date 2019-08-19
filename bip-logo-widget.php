<?php
class BIP_Logo_Widget extends WP_Widget {

    function __construct() {

        parent::__construct(
            'bip-logo',  // Base ID
            'BIP Logo Widget'   // Name
        );
    }

    public $args = array(
        'before_widget' => '<div class="widget-wrap">',
        'after_widget'  => '</div></div>'
    );

    public function widget( $args, $instance ) {
        $bip_main_page_url = get_permalink( BipPages\get_bip_main_page() );

        $alt = __('BIP naszej organizacji', 'bip-pages');

        echo $args['before_widget'];

        echo '<div class="textwidget">';
        echo "<a href='{$bip_main_page_url}'>";

        switch ($instance['image_type']) {
          default:
            echo "<img src='" . plugin_dir_url( __FILE__ ) . "assets/bip-logos/bip-small-2-color-pl_500px.png' alt='{$alt}' />";
            break;
        }

        echo '</a>';
        echo '</div>';

        echo $args['after_widget'];
    }

    public function form( $instance ) {
        echo '<p>' . __( 'This widget displays the BIP logo with a link to your BIP main page, as shown below.') . '</p>';

        $alt = __('BIP naszej organizacji', 'bip-pages');
        echo "<img src='" . plugin_dir_url( __FILE__ ) . "assets/bip-logos/bip-small-2-color-pl_500px.png' alt='{$alt}'  width='166' />";
    }

    public function update( $new_instance, $old_instance ) {

        $instance = array();

        $instance['image_type'] = !empty( $new_instance['image_type'] ) ? $new_instance['image_type'] : 0;

        return $instance;
    }

}
add_action( 'widgets_init', function() {
    register_widget( 'BIP_Logo_Widget', 'bip-pages' );
});
