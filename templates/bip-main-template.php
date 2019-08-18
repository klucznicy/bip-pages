<?php $options = get_option( BipPages\Settings\OPTION_NAME ); ?>
<img src="<?= plugin_dir_url( __FILE__ ) ?>../assets/bip-logos/bip-color-pl_min.png"
	alt="<?= _e( 'Biuletyn Informacji Publicznej' ) ?>"
/>

<?php require( 'bip-search-form.php' ); ?>

<address>
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

<p>
	<a href="<?= $bip_manual ?>"><?= _e( 'BIP pages usage manual') ?></a>
</p>
