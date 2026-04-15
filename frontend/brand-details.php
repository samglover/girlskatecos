<?php
/**
 * Brand details
 *
 * @file    frontend/brand-details.php
 * @package Girl_Skate_Cos
 * @since   1.1.0
 */

add_shortcode( 'brand-details', 'brand_details' );
/**
 * Adds a shortcode for outputting the brand details
 */
function brand_details() {
	global $post;

	if ( get_field( 'show_brand_details' ) ) {
		$brand = get_the_title();

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
							<?php echo esc_html( $brand ); ?>
						</span>
					</h1>
					<?php if ( get_field( 'owner' ) ) { ?>
						<h4>Owner(s)</h4>
						<p><?php echo esc_html( get_field( 'owner' ) ); ?></p>
					<?php } ?>

					<h4>Links</h4>
					<ul>
						<?php if ( get_field( 'shop_url' ) ) { ?>
							<li>
								<a href="<?php echo esc_url( get_field( 'shop_url' ) ); ?>">
									<?php echo esc_html__( 'Website/shop', 'girlskatecos' ); ?>
								</a>
							</li>
						<?php } ?>

						<?php if ( get_field( 'instagram' ) ) { ?>
							<?php $ig = get_field( 'instagram' ); ?>
							<li>
								<a href="<?php echo esc_attr( get_field( 'instagram' ) ); ?>">
									<?php echo esc_html__( 'Instagram', 'girlskatecos' ); ?>
								</a>
							</li>
						<?php } ?>
					</ul>

					<h4>Products</h3>
					<?php if ( get_field( 'products' ) ) { ?>
						<?php $products = get_field( 'products' ); ?>
						<ul>
							<?php foreach ( $products as $product ) { ?>
								<li>
									<?php echo esc_html( $product ); ?>
								</li>
							<?php } ?>
						</ul>
					<?php } ?>
					
					<?php
					$schema                 = new stdClass();
					$schema->{ '@context' } = 'https://schema.org';
					$schema->{ '@type' }    = 'Brand';
					$schema->name           = $brand;

					echo '<script type="application/ld+json">' . PHP_EOL;
					echo wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . PHP_EOL;
					echo '</script>' . PHP_EOL;
					?>
				</div>
			</div>
		<?php

		return ob_get_clean();
	}
}


add_filter( 'the_content', 'brand_details_default_shortcode' );
/**
 * Adds the [brand-details] shortcode if it it isn't already in the content.
 *
 * @param string $content The post content.
 */
function brand_details_default_shortcode( $content ) {
	if ( get_field( 'show_brand_details' ) && ! has_shortcode( $content, 'brand-details' ) ) {
		$content = do_shortcode( '[brand-details]' ) . $content;
	}

	return $content;
}