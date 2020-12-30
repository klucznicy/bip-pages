<!-- wp:image {"align":"right"} -->
<div class="wp-block-image">
	<figure class="alignright">
		<img
			src="/assets/bip-logos/bip-full-color-pl_min.png"
			alt="<?= esc_attr( __( 'Biuletyn Informacji Publicznej', 'bip-pages' ) ) ?>"/>
	</figure>
</div>
<!-- /wp:image -->

<!-- wp:paragraph -->
<p>
	<?= esc_html( sprintf(
		/* translators: %s is substituted with blog name */
		__( '%s: Biuletyn Informacji Publicznej', 'bip-pages' ),
		get_bloginfo( 'name' )
	)); ?>
</p>
<!-- /wp:paragraph -->

<!-- wp:bip-pages/org-info /-->

<!-- wp:bip-pages/search /-->

<!-- wp:bip-pages/recently-modified /-->

<!-- wp:paragraph -->
<p>
	<a href="<?= esc_url( $bip_instruction_url ) ?>">
		<?= esc_html__( 'BIP pages usage manual', 'bip-pages' ) ?>
	</a>
</p>
<!-- /wp:paragraph -->
