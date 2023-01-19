<h2><?= esc_html__( 'Recently updated BIP pages', 'bip-pages' ) ?></h2>
<ul>
<?php wp_list_pages( [
	'title_li' => '',
	'post_type' => 'bip',
	'sort_column' => 'post_modified',
	'sort_order' => 'desc',
	'number' => 10
] ) ?>
</ul>
