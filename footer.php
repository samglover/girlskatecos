<?php
/**
 * Footer template
 *
 * @file    footer.php
 * @package Girl_Skate_Cos
 * @since   1.0.0
 */

?>

	<div id="footer">
		<?php wp_nav_menu( array( 'theme_location' => 'footer-menu' ) ); ?>

		<p class="copyright"><small>
			The original content within this website is &copy; <?php echo wp_kses_post( date( 'Y' ) ); ?>. <a href="https://girlskatecos.com/sitemap_index.xml">XML Sitemap</a>
		</small></p>
	</div>

	<?php wp_footer(); ?>
</body>
</html>
