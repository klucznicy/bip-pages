<form role="search" method="get" class="search-form" action="<?= esc_url( home_url( '/' ) ) ?>">
	<label>
  	<span class="screen-reader-text"><?= _x( 'Search for:', 'label' ) ?></span>
		<input type="search" class="search-field"
			placeholder="<?= esc_attr_x( 'Search BIP pages&hellip;', 'placeholder' ) ?>"
			value="<?= get_search_query() ?>" name="s" />
	</label>
	<input type="submit" class="search-submit" value="<?= esc_attr_x( 'Search', 'submit button' ) ?>" />
	<input type="hidden" name="post_type" value="bip" />
</form>
