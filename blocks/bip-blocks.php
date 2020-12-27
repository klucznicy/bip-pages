<?php
namespace BipPages;
defined( 'ABSPATH' ) || exit;

function add_block_category( $categories, $post ) {
    if ( $post->post_type !== 'bip' ) {
        return $categories;
    }
    return array_merge(
        $categories,
        array(
            array(
                'slug' => 'bip',
                'title' => __( 'BIP Pages', 'bip-pages' )
            ),
        )
    );
}
add_filter( 'block_categories', __NAMESPACE__ . '\add_block_category', 10, 2 );

include( 'org-info/bip-org-info-block.php' );
include( 'search/bip-search-block.php' );
include( 'recently-modified/bip-recently-modified-block.php' );
