<?php namespace BipPages; ?>
<div class="bip-org-info" itemscope itemtype="https://schema.org/Organization">
	<p itemprop="name">
		<?= esc_html( get_option('bip_pages_org_name') ) ?>
	</p>
	<address>
		<?= esc_html__( 'Address:', 'bip-pages' ) ?>
		<span itemprop="address"><?= esc_html( get_option( 'bip_pages_address' ) ) ?></span>
	</address>
	<div itemprop="employee" itemscope itemtype="https://schema.org/Person">
		<p>
			<?= esc_html__( 'Editor:', 'bip-pages' ) ?>
			<a itemprop="email" href="mailto:<?= esc_attr( get_option( 'bip_pages_email' ) ) ?>">
				<span itemprop="name"><?= esc_html( get_option( 'bip_pages_editor' ) ) ?></span>
			</a>
		</p>
	</div>
	<p>
		<?= esc_html__( 'E-mail address:', 'bip-pages' ) ?>
		<a itemprop="email" href="mailto:<?= esc_attr( get_option( 'bip_pages_email' ) ) ?>">
			<?= esc_html( get_option( 'bip_pages_email' ) ) ?>
		</a>
	</p>
	<p>
		<?= esc_html__( 'Phone number:', 'bip-pages' ) ?>
		<a itemprop="telephone" href="tel:<?= esc_attr( get_option( 'bip_pages_phone' ) ) ?>">
			<?= esc_html( get_option( 'bip_pages_phone' ) ) ?>
		</a>
	</p>
</div>
