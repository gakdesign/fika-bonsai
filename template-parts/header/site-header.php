<!DOCTYPE html>
<html lang="<?php language_attributes(); ?>" dir="ltr">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php wp_body_open(); ?>

		<a class="skip" href="#main">Skip to main content</a>

		<?php
		$next_class_text = get_field( 'topbar_next_class', 'option' );
		$next_class_link = get_field( 'topbar_next_class_link', 'option' );
		?>
		<?php if ( $next_class_text ) : ?>
		<div class="topbar">
			<?php echo esc_html( $next_class_text ); ?>
			<?php if ( $next_class_link ) : ?>
				<a href="<?php echo esc_url( $next_class_link ); ?>">View dates</a>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<header class="site-header">
			<div class="header-inner">
				<a class="brand" href="<?php bloginfo( 'url' ); ?>/">
					<span class="brand-name">fika</span>
					<span class="brand-tag">exeter</span>
				</a>

				<nav aria-label="Primary">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_class'     => 'nav-primary',
							'container'      => false,
							'fallback_cb'    => false,
							'depth'          => 2,
						)
					);
					?>
				</nav>

				<div class="header-tools">
					<a class="icon-btn" href="<?php bloginfo( 'url' ); ?>/?s=" aria-label="Search">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
							<circle cx="11" cy="11" r="7" />
							<path d="M20 20l-3.5-3.5" stroke-linecap="round" />
						</svg>
					</a>

					<a class="icon-btn cart-btn" href="<?php echo function_exists( 'wc_get_cart_url' ) ? esc_url( wc_get_cart_url() ) : '#'; ?>" aria-label="Basket">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
							<path d="M6 6h15l-1.5 9h-12z" stroke-linejoin="round" />
							<path d="M6 6 5 3H2" stroke-linecap="round" />
							<circle cx="9" cy="20" r="1" fill="currentColor" stroke="none" />
							<circle cx="18" cy="20" r="1" fill="currentColor" stroke="none" />
						</svg>
						<?php if ( function_exists( 'WC' ) ) : ?>
							<span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
						<?php endif; ?>
					</a>

					<details class="menu-toggle">
						<summary class="icon-btn menu-summary">Menu</summary>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'primary',
								'menu_class'     => 'nav-drawer',
								'container'      => false,
								'fallback_cb'    => false,
								'depth'          => 2,
							)
						);
						?>
					</details>
				</div>
			</div>
		</header>

		<main id="main">
		<div class="mobile-overflow">
			<header>
				<div class="navigation-bg">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/header-banner.png" alt=" " class="img-res"/>
				</div>
				<div class="container container-header">
					<div class="row">
						<div class="col-lg-5">
							
						</div>
						<div class="col-lg-2 text-center">
							<a href="<?php bloginfo('url'); ?>/" title="<?php echo get_bloginfo( 'name' ); ?>" class="header-logo">
								<?php 
									$image = get_field('site_main_logo', 'option');
									if ( $image ) {
											echo wp_get_attachment_image( $image, 'full' );
									}
								?>
							</a>
						</div>
						<div class="col-lg-5 text-end">


							<div class="hamburger" id="trigger">
								<div class="hamburger-lines">
									<div class="top-bun"></div>
									<div class="meat"></div>
									<div class="bottom-bun"></div>
								</div>
							</div>
						</div>
				</div>
			</header>
			<div class="hidden-height"></div>
