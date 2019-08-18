<?php $options = get_option( BipPages\Settings\OPTION_NAME ); ?>
<img src="<?= plugin_dir_url( __FILE__ ) ?>../assets/bip-logos/bip-color-pl_min.png"
	class="bip-main-logo"
	alt="<?= _e( 'Biuletyn Informacji Publicznej' ) ?>"
/>
<p>
<?= sprintf(
	__( 'Biuletyn Informacji Publicznej organizacji %s' ),
	get_bloginfo( 'name' )
); ?>
</p>

<address class="bip-address">
	<p><?= _e( 'Address:' ) ?> <?= $options['address'] ?></p>
	<p><?= _e( 'Editor:' ) ?>
		<a href="mailto:<?= $options['email'] ?>"><?= $options['rep'] ?></a>
	</p>
	<p><?= _e ( 'E-mail address:' ) ?>
		<a href="mailto:<?= $options['email'] ?>"><?= $options['email'] ?></a>
	</p>
	<p><?= _e( 'Numer telefonu:' ) ?>
		<a href="tel:<?= $options['phone'] ?>"><?= $options['phone'] ?></a>
	</p>
</address>

<div>
<?php require( 'bip-search-form.php' ); ?>
</div>

<?= $bip_main_page_content ?>

<h2><?= __( 'Recently updated BIP pages' ) ?></h2>
<ul>
<?php wp_list_pages( [
	'title_li' => '',
	'post_type' => 'bip',
	'sort_column' => 'post_modified',
	'number' => 10
] ) ?>
</ul>

<p>
	<a href="<?= $bip_manual ?>"><?= _e( 'BIP pages usage manual') ?></a>
</p>
