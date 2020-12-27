<?php namespace BipPages; ?>

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
