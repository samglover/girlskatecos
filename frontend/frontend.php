<?php
/**
 * Frontend functions
 *
 * @file    frontend/frontend.php
 * @package Girl_Skate_Cos
 * @since   1.0.0
 */

/**
 * Get the post date
 */
function get_date_meta() {
	$published_date = get_the_time( 'F jS, Y' );
	$updated_date   = get_the_modified_date( 'F jS, Y' );

	$date = '<p class="date">Published on ' . $published_date . '.';
	if ( $published_date !== $updated_date ) {
		$date .= ' Last updated ' . $updated_date . '.';
	}
	$date .= '</p>';

	echo wp_kses_post( $date );
}
