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


add_filter( 'the_content', 'open_external_links_in_new_tab' );
add_filter( 'the_excerpt', 'open_external_links_in_new_tab' );
add_filter( 'widget_text', 'open_external_links_in_new_tab' );
add_filter( 'widget_custom_html', 'open_external_links_in_new_tab' );
add_filter( 'wp_nav_menu', 'open_external_links_in_new_tab' );
add_filter( 'comment_text', 'open_external_links_in_new_tab' );
/**
 * Adds `target="_blank"` to external links in the supplied HTML.
 *
 * @param string $content An HTML string.
 * @return string
 */
function open_external_links_in_new_tab( $content ) {
	// Exit if there are no links.
	if ( stripos( $content, '<a ' ) === false ) {
		return $content;
	}

	$this_website = wp_parse_url( home_url(), PHP_URL_HOST );

	// Suppress HTML parsing errors.
	libxml_use_internal_errors( true );

	$doc = new DOMDocument();
	// Load $doc as a UTF-8 HTML fragment.
	$doc->loadHTML(
		'<?xml encoding="UTF-8">' . $content,
		LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
	);

	$links = $doc->getElementsByTagName( 'a' );

	foreach ( $links as $link ) {
		$href = $link->getAttribute( 'href' );
		if ( empty( $href ) || strpos( $href, 'http' ) !== 0 ) {
			continue; // Skip non-http links (anchors, mailto, etc.).
		}

		$link_website = wp_parse_url( $href, PHP_URL_HOST );

		// Add `target="_blank"` if the link is external.
		if ( $link_website && $link_website !== $this_website && strpos( $link_website, $this_website ) === false ) {
			$link->setAttribute( 'target', '_blank' );

			// Add or merge `rel="noopener, noreferrer"` attributes.
			$existing_rel  = $link->getAttribute( 'rel' );
			$required_rels = array( 'noopener', 'noreferrer' );

			$rels = array_unique(
				array_filter(
					array_merge(
						preg_split( '/\s+/', $existing_rel, -1, PREG_SPLIT_NO_EMPTY ),
						$required_rels
					)
				)
			);

			$link->setAttribute( 'rel', implode( ' ', $rels ) );
		}
	}

	$html = $doc->saveHTML();

	libxml_clear_errors();

	return $html;
}
