<?php
/**
 * Search form template
 *
 * @file    searchform.php
 * @package Girl_Skate_Cos
 * @since   1.0.0
 */

?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="search-field">
		<?php echo wp_kses_post( __( 'Enter search terms.', 'label' ) ); ?>
	</label>
	<input id="search-field" type="search" name="s" placeholder="<?php esc_attr_e( 'Enter search terms.', 'placeholder' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" />
	<input type="submit" value="<?php esc_attr_e( 'Search', 'submit button' ); ?>" />
</form>
