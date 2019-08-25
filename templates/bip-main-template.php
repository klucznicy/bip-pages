<?php $options = get_option( BipPages\Settings\OPTION_NAME ); ?>
<img src="<?= plugin_dir_url( __FILE__ ) ?>../assets/bip-logos/bip-color-pl_min.png"
	class="bip-main-logo"
	alt="<?= esc_attr__( 'Biuletyn Informacji Publicznej', 'bip-pages' ) ?>"
/>
<p>
<?= esc_html( sprintf(
	__( 'Biuletyn Informacji Publicznej organizacji %s', 'bip-pages' ),
	get_bloginfo( 'name' )
)); ?>
</p>

<address class="bip-address">
	<p>
		<?= esc_html__( 'Address:', 'bip-pages' ) ?>
		<?= esc_html( $options['address'] ) ?>
	</p>
	<p>
		<?= esc_html__( 'Editor:', 'bip-pages' ) ?>
		<a href="mailto:<?= esc_attr( $options['email'] ) ?>">
			<?= esc_html( $options['rep'] ) ?>
		</a>
	</p>
	<p>
		<?= esc_html__( 'E-mail address:', 'bip-pages' ) ?>
		<a href="mailto:<?= esc_attr( $options['email'] ) ?>">
			<?= esc_html( $options['email'] ) ?>
		</a>
	</p>
	<p>
		<?= esc_html__( 'Phone number:', 'bip-pages' ) ?>
		<a href="tel:<?= esc_attr( $options['phone'] ) ?>">
			<?= esc_html( $options['phone'] ) ?>
		</a>
	</p>
</address>

<div>
<?php require( 'bip-search-form.php' ); ?>
</div>

<?= $bip_main_page_content ?>

<h2><?= esc_html__( 'Recently updated BIP pages', 'bip-pages' ) ?></h2>
<ul>
<?php wp_list_pages( [
	'title_li' => '',
	'post_type' => 'bip',
	'sort_column' => 'post_modified',
	'number' => 10
] ) ?>
</ul>

<p>
	<a href="<?= esc_url( $bip_instruction_url ) ?>">
		<?= esc_html__( 'BIP pages usage manual', 'bip-pages') ?>
	</a>
</p>
