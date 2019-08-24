<?php $options = get_option( BipPages\Settings\OPTION_NAME ); ?>
<img src="<?= plugin_dir_url( __FILE__ ) ?>../assets/bip-logos/bip-color-pl_min.png"
	class="bip-main-logo"
	alt="<?= __( 'Biuletyn Informacji Publicznej', 'bip-pages' ) ?>"
/>
<p>
<?= sprintf(
	__( 'Biuletyn Informacji Publicznej organizacji %s', 'bip-pages' ),
	get_bloginfo( 'name' )
); ?>
</p>

<address class="bip-address">
	<p><?= _e( 'Address:', 'bip-pages' ) ?> <?= $options['address'] ?></p>
	<p><?= _e( 'Editor:', 'bip-pages' ) ?>
		<a href="mailto:<?= $options['email'] ?>"><?= $options['rep'] ?></a>
	</p>
	<p><?= _e ( 'E-mail address:', 'bip-pages' ) ?>
		<a href="mailto:<?= $options['email'] ?>"><?= $options['email'] ?></a>
	</p>
	<p><?= _e( 'Phone number:', 'bip-pages' ) ?>
		<a href="tel:<?= $options['phone'] ?>"><?= $options['phone'] ?></a>
	</p>
</address>

<div>
<?php require( 'bip-search-form.php' ); ?>
</div>

<?= $bip_main_page_content ?>

<h2><?= __( 'Recently updated BIP pages', 'bip-pages' ) ?></h2>
<ul>
<?php wp_list_pages( [
	'title_li' => '',
	'post_type' => 'bip',
	'sort_column' => 'post_modified',
	'number' => 10
] ) ?>
</ul>

<p>
	<a href="<?= $bip_instruction_url ?>"><?= _e( 'BIP pages usage manual', 'bip-pages') ?></a>
</p>
