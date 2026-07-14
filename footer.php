<?php
/**
 * Footer template
 *
 * @file    footer.php
 * @package Girl_Skate_Cos
 * @since   1.0.0
 */

?>

		</main>
		<footer id="footer" class="site-footer">
			<?php wp_nav_menu( array( 'theme_location' => 'footer-menu' ) ); ?>

			<p class="copyright"><small>
				<?php
				echo wp_kses_post(
					sprintf(
						// Translators: %s is the current year.
						__( 'The original content within this website is &copy; %s.', 'girlskatecos' ),
						gmdate( 'Y' )
					)
				);
				?>
				<a href="https://girlskatecos.com/sitemap_index.xml">XML Sitemap</a>
			</small></p>
		</footer>
	</div>

	<?php wp_footer(); ?>
</body>
</html>
