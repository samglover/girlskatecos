<?php
/**
 * Page template
 *
 * @file    page.php
 * @package Girl_Skate_Cos
 * @since   1.0.0
 */

get_header();
?>

<div id="content">
	<?php get_template_part( 'template-parts/loop', 'single' ); ?>
</div>

<?php get_footer(); ?>
