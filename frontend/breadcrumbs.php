<?php
/**
 * Breadcrumbs
 *
 * @file    frontend/breadcrumbs.php
 * @package Girl_Skate_Cos
 * @since   1.1.0
 */

/**
 * Outputs breadcrumbs
 */
function breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	if ( is_singular() ) {
		$post_id       = get_the_ID();
		$breadcrumbs[] = '<span class="breadcrumb last-breadcrumb">' . get_the_title( $post_id ) . '</span>';

		if ( is_singular( 'page' ) ) {
			$parent_id = wp_get_post_parent_id( $post_id );

			while ( $parent_id ) {
				$parent_url    = get_the_permalink( $parent_id );
				$parent_title  = get_the_title( $parent_id );
				$breadcrumbs[] = '<span class="breadcrumb"><a href="' . $parent_url . '">' . $parent_title . '</a></span>';
				$parent_id     = wp_get_post_parent_id( $parent_id );
			}
		}

		if ( is_singular( 'post' ) ) {
			$categories      = get_the_terms( $post_id, 'category' );
			$category_id     = $categories[0]->term_id;
			$category_url    = get_term_link( $category_id );
			$category_title  = $categories[0]->name;
			$breadcrumbs[]   = '<span class="breadcrumb"><a href="' . $category_url . '">' . $category_title . '</a></span>';
			$category_parent = $categories[0]->parent;

			while ( $category_parent ) {
				$category        = get_term( $category_parent );
				$category_id     = $category->term_id;
				$category_url    = get_term_link( $category_id );
				$category_title  = $category->name;
				$breadcrumbs[]   = '<span class="breadcrumb"><a href="' . $category_url . '">' . $category_title . '</a></span>';
				$category_parent = $category->parent;
			}
		}
	} elseif ( is_archive() ) {
		$breadcrumbs[] = '<span class="breadcrumb last-breadcrumb">' . single_term_title( '', false ) . '</span>';
	}

	$breadcrumbs[] = '<span class="breadcrumb home-breadcrumb"><a href="' . home_url() . '">Home</a></span>';
	$breadcrumbs   = array_reverse( $breadcrumbs );

	?>
	<div id="breadcrumbs" class="small">
		<?php echo wp_kses_post( implode( ' / ', $breadcrumbs ) ); ?>
	</div>
	<?php
}