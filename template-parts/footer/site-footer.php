	</main>

	<footer class="site-footer">
		<div class="max-w footer-grid">
			<div class="footer-brand">
				<a class="site-footer-hero-logo" href="<?php bloginfo( 'url' ); ?>/" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<?php $hero_logo = get_field( 'site_main_logo', 'option' ); ?>
				<?php if ( $hero_logo ) :
					$hero_logo_id = is_array( $hero_logo ) ? $hero_logo['ID'] : $hero_logo;
					echo wp_get_attachment_image(
						$hero_logo_id,
						'large',
						false,
						array( 'loading' => 'eager', 'fetchpriority' => 'high' )
					);
				else :
					?>
					<span class="brand-name">fika</span>
					<span class="brand-tag">exeter</span>
				<?php endif; ?>
			</a>
				<?php if ( $footer_blurb = get_field( 'footer_blurb', 'option' ) ) : ?>
					<p><?php echo esc_html( $footer_blurb ); ?></p>
				<?php endif; ?>

				<?php
				$instagram_url = get_field( 'social_instagram_url', 'option' );
				$facebook_url  = get_field( 'social_facebook_url', 'option' );
				?>
				<?php if ( $instagram_url || $facebook_url ) : ?>
					<ul class="footer-social">
						<?php if ( $instagram_url ) : ?>
							<li>
								<a href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer">
									<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false">
										<rect x="3" y="3" width="18" height="18" rx="5" />
										<circle cx="12" cy="12" r="4" />
										<circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none" />
									</svg>
									<span class="screen-reader-text">Instagram (opens in a new tab)</span>
								</a>
							</li>
						<?php endif; ?>

						<?php if ( $facebook_url ) : ?>
							<li>
								<a href="<?php echo esc_url( $facebook_url ); ?>" target="_blank" rel="noopener noreferrer">
									<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">
										<path d="M14 8.5V6.75c0-.83.67-1.25 1.5-1.25H17V2.5h-2.5C11.9 2.5 11 4.4 11 6.6v1.9H8.5v3H11V21.5h3v-10h2.6l.4-3H14Z" />
									</svg>
									<span class="screen-reader-text">Facebook (opens in a new tab)</span>
								</a>
							</li>
						<?php endif; ?>
					</ul>
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