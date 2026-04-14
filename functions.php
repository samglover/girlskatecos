<?php
/**
 * Global functions
 *
 * @file    functions.php
 * @package Girl_Skate_Cos
 * @since   1.0.0
 */

define( 'TEMPLATE_DIR_PATH', trailingslashit( get_template_directory() ) );
define( 'TEMPLATE_DIR_URI', trailingslashit( get_template_directory_uri() ) );
define( 'THEME_VERSION', wp_get_theme()->get( 'Version' ) );


add_action( 'init', 'unregister_tags' );
/**
 * Unregister post tags
 */
function unregister_tags() {
	register_taxonomy( 'post_tag', array() );
}


add_action( 'after_setup_theme', 'theme_setup' );
/**
 * Theme setup
 */
function theme_setup() {
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 110,
			'width'       => 110,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array( 'site-title', 'site-description' ),
		)
	);
	add_theme_support( 'editor-styles' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'wp-block-styles' );

	add_post_type_support( 'page', 'excerpt' );

	add_editor_style( 'editor-styles.css' );
}


add_action( 'wp_enqueue_scripts', 'register_stylesheets_scripts' );
/**
 * Register stylesheets & scripts
 */
function register_stylesheets_scripts() {
	wp_enqueue_style( 'normalize', TEMPLATE_DIR_URI . 'assets/css/normalize.css', array(), THEME_VERSION );
	wp_enqueue_style( 'stylesheet', TEMPLATE_DIR_URI . 'assets/css/style.css', array(), THEME_VERSION, 'all' );
	wp_enqueue_script( 'footer', TEMPLATE_DIR_URI . 'assets/js/footer.js', array(), THEME_VERSION, true );
}


add_action( 'init', 'register_menus' );
/**
 * Register footer menu
 */
function register_menus() {
	register_nav_menus( array( 'footer-menu' => 'Footer menu' ) );
}



/**
 * ≈crumbs
 */
function get_breadcrumbs() {
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

	ob_start();
	?>
		<div id="breadcrumbs" class="small">
			<?php echo wp_kses_post( implode( ' / ', $breadcrumbs ) ); ?>
		</div>
	<?php

	echo wp_kses_post( ob_get_clean() );
}


/**
 * Stickers
 */
function get_stickers() {
	$args = array(
		'meta_query'     => array(
			'relation' => 'OR',
			array(
				'key'     => 'show_in_brand_list',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => 'show_in_brand_list',
				'compare' => '=',
				'value'   => true,
			),
		),
		'orderby'        => 'rand',
		'post_type'      => 'page',
		'posts_per_page' => -1,
	);

	$brand_pages_query = new WP_Query( $args );

	ob_start();
	if ( $brand_pages_query->have_posts() ) :
		?>
		<div class="sort-search">
			<button id="sort-stickers">Sort A to Z</button>
			<button id="randomize-stickers">Randomize</button>
			<div class="search-cos-container">
				<label>Search brand names …</label>
				<input id="search-cos" name="search-cos" type="search" placeholder="Search brand names …" />
				<button id="clear-search-cos" class="close hidden">x</button>
			</div>
		</div>
		<div id="stickers" class="stickers grid-cols-4">
			<?php while ( $brand_pages_query->have_posts() ) : ?>
				<?php $brand_pages_query->the_post(); ?>
				<a <?php post_class( 'sticker' ); ?> href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">
					<?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'medium' );
					}
					?>
				</a>
			<?php endwhile; ?>
			<p id="no-stickers" class="hidden" title="<?php esc_attr_e( 'No cos. found.', 'girlskatecos' ); ?>">
				<?php esc_html_e( 'No cos. found.', 'girlskatecos' ); ?>
			</p>
		</div>
		<?php
	endif;

	echo wp_kses_post( ob_get_clean() );
}


/**
 * Get posts by category slug
 *
 * @param string $category The category slug.
 */
function get_posts_by_category_slug( $category = null ) {
	if ( $category ) {
		$args = array(
			'category_name' => $category,
		);
	}

	$posts_query = new WP_Query( $args );

	ob_start();
	if ( $posts_query->have_posts() ) :
		while ( $posts_query->have_posts() ) :
			$posts_query->the_post();
			?>
				<a <?php post_class( 'card page-card' ); ?> href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">
					<?php if ( has_post_thumbnail() ) { ?>
						<?php the_post_thumbnail( 'medium' ); ?>
					<?php } ?>
					<div class="headline-excerpt">
						<h2 class="headline"><?php the_title(); ?></h2>
						<?php if ( ! has_term( 'reviews', 'category' ) ) { ?>
							<p class="excerpt">
								<?php echo wp_kses_post( get_the_excerpt() ); ?>
							</p>
						<?php } ?>
					</div>
				</a>
					<?php
		endwhile;
	endif;

	echo wp_kses_post( ob_get_clean() );
}


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


add_shortcode( 'brand-details', 'gsc_brand_details' );
/**
 * Adds a shortcode for outputting the brand details
 */
function gsc_brand_details() {
	global $post;

	if ( get_field( 'show_brand_details' ) ) {
		$brand                  = get_the_title();
		$schema                 = new stdClass();
		$schema->{ '@context' } = 'https://schema.org';
		$schema->{ '@type' }    = 'Brand';
		$schema->name           = $brand;

		$schema_json  = '<script type="application/ld+json">' . PHP_EOL;
		$schema_json .= wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . PHP_EOL;
		$schema_json .= '</script>' . PHP_EOL;

		ob_start();
		?>
			<div id="brand-detail-box" class="card
			<?php
			if ( has_post_thumbnail() ) {
				echo ' has-brand-logo'; }
			?>
			">
				<?php if ( has_post_thumbnail() ) { ?>
					<div id="brand-logo">
						<div class="sticker">
							<?php the_post_thumbnail( 'medium' ); ?>
						</div>
					</div>
				<?php } ?>

				<div id="brand-details">
					<h1 class="headline">
						<span itemprop="name">
							<?php esc_html_e( $brand ); ?>
						</span>
					</h1>
					<?php if ( get_field( 'owner' ) == true ) { ?>
						<h4>Owner(s)</h4>
						<p><?php echo esc_html( get_field( 'owner' ) ); ?></p>
					<?php } ?>

					<h4>Links</h4>
					<ul>
						<?php if ( get_field( 'shop_url' ) == true ) { ?>
							<li>
								<a href="<?php echo esc_url( get_field( 'shop_url' ) ); ?>">
									<?php echo esc_html__( 'Website/shop', 'girl-skate-cos' ); ?>
								</a>
							</li>
						<?php } ?>

						<?php if ( get_field( 'instagram' ) == true ) { ?>
							<?php $ig = get_field( 'instagram' ); ?>
							<li>
								<a href="<?php echo esc_attr( get_field( 'instagram' ) ); ?>">
									<?php echo esc_html__( 'Instagram', 'girl-skate-cos' ); ?>
								</a>
							</li>
						<?php } ?>
					</ul>

					<h4>Products</h3>
					<?php if ( get_field( 'products' ) == true ) { ?>
						<?php $products = get_field( 'products' ); ?>
						<ul>
							<?php foreach ( $products as $product ) { ?>
								<li>
									<?php echo esc_html( $product ); ?>
								</li>
							<?php } ?>
						</ul>
					<?php } ?>
					<?php echo $schema_json; ?>
				</div>
			</div>
		<?php

		return ob_get_clean();
	}
}


add_filter( 'the_content', 'gsc_brand_details_default_shortcode' );
/**
 * Adds the [brand-details] shortcode if it it isn't already in the content.
 *
 * @param string $content The post content.
 */
function gsc_brand_details_default_shortcode( $content ) {
	if ( get_field( 'show_brand_details' ) && ! has_shortcode( $content, 'brand-details' ) ) {
		$content = do_shortcode( '[brand-details]' ) . $content;
	}

	return $content;
}



/**
 * Renders the star rating overlay.
 *
 * @param string $star_width The width of star rating as a percentage.
 */
function gsc_star( $star_width = '100%' ) {
	$star = '<svg class="star" style="width: ' . $star_width . '%" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="40" viewBox="0 0 24 24" width="40"><g><path d="M0,0h24v24H0V0z" fill="none"/><path d="M0,0h24v24H0V0z" fill="none"/></g><g><path d="M12,17.27l4.15,2.51c0.76,0.46,1.69-0.22,1.49-1.08l-1.1-4.72l3.67-3.18c0.67-0.58,0.31-1.68-0.57-1.75l-4.83-0.41 l-1.89-4.46c-0.34-0.81-1.5-0.81-1.84,0L9.19,8.63L4.36,9.04c-0.88,0.07-1.24,1.17-0.57,1.75l3.67,3.18l-1.1,4.72 c-0.2,0.86,0.73,1.54,1.49,1.08L12,17.27z"/></g></svg>';

	return $star;
}

/**
 * Renders the star rating container.
 *
 * @param string $rating The rating (out of 5 stars).
 */
function gsc_star_rating( $rating = null ) {
	if ( is_null( $rating ) ) {
		return;
	}

	ob_start();
	?>
	<div class="star-rating-wrapper" itemprop="reviewCount" content="1">
		<div class="stars">
			<?php
			$i = 0;

			while ( $i < 5 ) {
				echo gsc_star();
				++$i;
			}
			?>

			<div class="star-rating" style="width: <?php echo esc_attr( $rating / 5 * 100 ); ?>%">
				<?php
				$stars        = explode( '.', $rating );
				$full_stars   = $stars[0];
				$partial_star = isset( $stars[1] ) ? $stars[1] / 1 * 100 . '%' : 0;

				$i = 0;

				while ( $i < $full_stars ) {
					echo gsc_star();
					++$i;
				}

				echo gsc_star( $partial_star );
				?>
			</div>
		</div>
		<span class="star-rating-label entry-meta">
			<span itemprop="ratingValue">
				<?php echo $rating; ?>
			</span>/5 stars
		</span>
	</div>
	<?php

	return ob_get_clean();
}


add_filter( 'the_content', 'gsc_product_review' );
/**
 * Adds product reviews.
 *
 * @param string $content The post content.
 */
function gsc_product_review( $content ) {
	if (
		is_single()
		&& get_field( 'gsc_show_product_rating' )
		&& have_rows( 'gsc_product_rating' )
	) :
		$product_rating = array();

		while ( have_rows( 'gsc_product_rating' ) ) {
			the_row();
			$product_rating['brand']        = get_sub_field( 'gsc_brand_name' ); // Page ID.
			$product_rating['name']         = get_sub_field( 'gsc_product_name' );
			$product_rating['type']         = get_sub_field( 'gsc_product_type' );
			$product_rating['manufacturer'] = get_sub_field( 'gsc_product_manufacturer' );
			$product_rating['rating']       = get_sub_field( 'gsc_product_rating' );
			$product_rating['pros']         = get_sub_field( 'gsc_product_pros' );
			$product_rating['cons']         = get_sub_field( 'gsc_product_cons' );
		}

		$schema                 = new stdClass();
		$schema->{ '@context' } = 'https://schema.org';
		$schema->{ '@type' }    = 'Product';
		$schema->name           = $product_rating['name'];
		$schema->description    = $product_rating['type'];

		$schema->brand             = new stdClass();
		$schema->brand->{ 'type' } = 'Organization';
		$schema->brand->name       = get_the_title( $product_rating['brand'] );

		$schema->review              = new stdClass();
		$schema->review->{ '@type' } = 'Review';
		$schema->review->reviewBody  = 'Pros: ' . wp_strip_all_tags( $product_rating['pros'] ) . ' Cons: ' . wp_strip_all_tags( $product_rating['cons'] );

		$schema->review->author              = new stdClass();
		$schema->review->author->{ '@type' } = 'Person';
		$schema->review->author->name        = get_the_author_meta( 'display_name' );

		$schema->review->reviewRating              = new stdClass();
		$schema->review->reviewRating->{ '@type' } = 'Rating';
		$schema->review->reviewRating->ratingValue = $product_rating['rating'];

		$schema_json  = '<script type="application/ld+json">' . PHP_EOL;
		$schema_json .= wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . PHP_EOL;
		$schema_json .= '</script>' . PHP_EOL;

		ob_start();
		?>
		<h2>Rating</h2>
		<div id="product-rating-box" class="card">
			<h3 class="rating-title" itemprop="description">
				<a href="<?php echo esc_url( get_the_permalink( $product_rating['brand'] ) ); ?>">
					<?php echo esc_html( get_the_title( $product_rating['brand'] ) ); ?>
				</a>
				<?php echo wp_kses_post($product_rating['name']); ?>
			</h3>
			<?php echo gsc_star_rating( $product_rating['rating'] ); ?>
			<h4>Manufacturer</h4>
			<p><?php echo $product_rating['manufacturer']; ?></p>
				<h4>Pros</h4>
				<?php echo $product_rating['pros']; ?>
				<h4>Cons</h4>
				<?php echo $product_rating['cons']; ?>
			<?php echo $schema_json; ?>
		</div>
		<?php

		$rating   = ob_get_clean();
		$content .= $rating;
	endif;

	return $content;
}
