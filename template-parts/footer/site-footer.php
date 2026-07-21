	</main>

	<footer class="site-footer">
		<div class="max-w footer-grid">
			<div class="footer-brand">
				<a class="brand" href="<?php bloginfo( 'url' ); ?>/">
					<span class="brand-name">fika</span>
					<span class="brand-tag">exeter</span>
				</a>
				<?php if ( $footer_blurb = get_field( 'footer_blurb', 'option' ) ) : ?>
					<p><?php echo esc_html( $footer_blurb ); ?></p>
				<?php endif; ?>
			</div>

			<div class="newsletter">
				<form action="<?php echo esc_url( home_url( '/newsletter-signup/' ) ); ?>" method="post" class="newsletter-form">
					<?php wp_nonce_field( 'fika_newsletter', 'fika_newsletter_nonce' ); ?>
					<label for="newsletter-email">Occasional notes — classes and new arrivals</label>
					<div class="newsletter-row">
						<input id="newsletter-email" name="email" type="email" autocomplete="email" placeholder="you@example.com" required />
						<button type="submit" class="btn btn-primary">Sign up</button>
					</div>
				</form>
			</div>
		</div>

		<div class="max-w footer-meta">
			<span>© <?php echo esc_html( date( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
			<span>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'footer-links',
						'fallback_cb'    => false,
						'depth'          => 1,
						'link_before'    => '',
						'link_after'     => '',
						'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
					)
				);
				?>
			</span>
		</div>
	</footer>

	<?php wp_footer(); ?>
</body>
</html>