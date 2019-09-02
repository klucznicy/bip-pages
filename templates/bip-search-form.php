<form role="search" method="get" action="<?= esc_url( home_url( '/' ) ) ?>">
	<label>
  	<span class="screen-reader-text">
			<?= esc_html_x( 'Search for BIP pages:', 'label', 'bip-pages' ) ?>
		</span>
		<input type="search"
			placeholder="<?= esc_attr_x( 'Search BIP pages&hellip;', 'placeholder', 'bip-pages' ) ?>"
			value="<?= get_search_query() ?>" name="s" />
	</label>
	<input type="submit" value="<?= esc_attr_x( 'Search', 'submit button', 'bip-pages' ) ?>" />
	<input type="hidden" name="post_type" value="bip" />
</form>
