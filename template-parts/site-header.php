<?php
/**
 * Site header template
 *
 * @file    site-header.php
 * @package Girl_Skate_Cos
 * @since   1.1.0
 */

$site_header_classes = 'site-header';
if ( has_custom_logo() ) {
	$site_header_classes .= ' has-custom-logo';
}
?>

<div id="header" class="<?php echo esc_attr( $site_header_classes ); ?>">
	<div id="logo-title">
		<?php
		if ( has_custom_logo() ) {
			the_custom_logo();
		}

		$title_parts     = explode( ' ', get_bloginfo( 'name' ) );
		$title_formatted = implode( ' <br />', $title_parts );
		?>

		<?php if ( is_front_page() ) { ?>
			<h1 id="title">
				<a href="<?php echo esc_url( home_url() ); ?>">
					<?php echo wp_kses_post( $title_formatted ); ?>
				</a>
			</h1>
		<?php } else { ?>
			<p id="title">
				<a href="<?php echo esc_url( home_url() ); ?>">
					<?php echo wp_kses_post( $title_formatted ); ?>
				</a>
			</p>
		<?php } ?>
	</div>
	<p class="section-header">
		<?php echo bloginfo( 'description' ); ?>
	</p>
	<?php get_breadcrumbs(); ?>
</div>