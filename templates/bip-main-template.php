<?php namespace BipPages; ?>
<img src="<?= esc_url( $bip_logo_url ) ?>"
	class="bip-main-logo"
	alt="<?= esc_attr__( 'Biuletyn Informacji Publicznej', 'bip-pages' ) ?>"
/>
<p>
<?= esc_html( sprintf(
	/* translators: %s is substituted with blog name */
	__( '%s: Biuletyn Informacji Publicznej', 'bip-pages' ),
	get_bloginfo( 'name' )
)); ?>
</p>

<?php include( 'bip-org-info-template.php' ); ?>
<?php include( 'bip-recently-modified-template.php' ); ?>

<p>
	<a href="<?= esc_url( $bip_instruction_url ) ?>">
		<?= esc_html__( 'BIP pages usage manual', 'bip-pages' ) ?>
	</a>
</p>
