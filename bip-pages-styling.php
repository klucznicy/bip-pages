<?php
namespace BipPages;

function add_title_container( $title, $id = null ) {
    $post = get_post( $id );
    if ( is_singular( 'bip' ) || ( is_search() && $post->post_type == 'bip' ) ) {
        $title = "<span class='bip-title-container'>" . $title . "</span>";
    }

    return $title;
}
add_filter( 'the_title', __NAMESPACE__ . '\add_title_container', 10, 2 );

function add_post_class( $classes, $class = '', $post_id = '' ) {
  $post = get_post();
  if ( $post->post_type == 'bip' ) {
    $classes[] = 'type-page';
  }

  return $classes;
}
add_filter( 'post_class', __NAMESPACE__ . '\add_post_class' );

function mark_bip_page_states( $post_states, $post ) {
  switch ( $post->ID ) {
    case get_bip_main_page():
      $post_states[] = esc_html__( 'BIP Main Page', 'bip-pages' );
      break;
    case get_bip_instruction_page():
      $post_states[] = esc_html__( 'BIP Instruction Page', 'bip-pages' );
      break;
  }

	return $post_states;
}
add_filter( 'display_post_states', __NAMESPACE__ . '\mark_bip_page_states', 10, 2 );
