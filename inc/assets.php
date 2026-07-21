<?php
/**
 * assets.php – Enqueue scripts and styles
 */

// Preconnects for Google Fonts (fast + correct CORS for gstatic)
add_action('wp_head', function () { ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<?php }, 1);

function bonsai_assets() {
  // --- Styles ---
  wp_enqueue_style(
    'custom-font',
    'https://fonts.googleapis.com/css2?family=Caveat:wght@500&family=Poppins:wght@400;500;600;700&display=swap',
    [],
    null
  );

  wp_enqueue_style(
    'fontawesome',
    BONSAI_THEME_URI . '/assets/fonts/fontawesome/css/fontawesome.min.cs',
    [],
    filemtime(BONSAI_THEME_DIR . '/assets/fonts/fontawesome/css/fontawesome.min.css')
  );
  wp_enqueue_style(
    'fontawesome-brands',
    BONSAI_THEME_URI . '/assets/fonts/fontawesome/css/brands.min.css',
    [],
    filemtime(BONSAI_THEME_DIR . '/assets/fonts/fontawesome/css/brands.min.css')
  );
  wp_enqueue_style(
    'slick',
    BONSAI_THEME_URI . '/assets/css/slick.css',
    [],
    filemtime(BONSAI_THEME_DIR . '/assets/css/slick.css')
  );

  // wp_enqueue_style(
  //   'aos',
  //   BONSAI_THEME_URI . '/assets/css/aos.css',
  //   [],
  //   filemtime(BONSAI_THEME_DIR . '/assets/css/aos.css')
  // );

  wp_enqueue_style(
    'bs',
    BONSAI_THEME_URI . '/assets/css/bootstrap-custom.min.css',
    [],
    filemtime(BONSAI_THEME_DIR . '/assets/css/bootstrap-custom.min.css')
  );

  wp_enqueue_style(
    'style-additions',
    BONSAI_THEME_URI . '/assets/css/core/additions.css',
    [],
    filemtime(BONSAI_THEME_DIR . '/assets/css/core/additions.css')
  );

  wp_enqueue_style(
    'style-header',
    BONSAI_THEME_URI . '/assets/css/core/header.css',
    [],
    filemtime(BONSAI_THEME_DIR . '/assets/css/core/header.css')
  );

  wp_enqueue_style(
    'style-footer',
    BONSAI_THEME_URI . '/assets/css/core/footer.css',
    [],
    filemtime(BONSAI_THEME_DIR . '/assets/css/core/footer.css')
  );

  wp_enqueue_style(
    'style',
    BONSAI_THEME_URI . '/assets/css/core/base.css',
    [],
    filemtime(BONSAI_THEME_DIR . '/assets/css/core/base.css')
  );

  // --- Scripts ---
  wp_enqueue_script('jquery');

  wp_enqueue_script(
    'slick',
    BONSAI_THEME_URI . '/assets/js/slick.js',
    ['jquery'],
    filemtime(BONSAI_THEME_DIR . '/assets/js/slick.js'),
    true
  );

  // wp_enqueue_script(
  //   'aosjs',
  //   BONSAI_THEME_URI . '/assets/js/aos.js',
  //   [],
  //   filemtime(BONSAI_THEME_DIR . '/assets/js/aos.js'),
  //   true
  // );

  wp_enqueue_script(
    'bootjs',
    BONSAI_THEME_URI . '/assets/js/bootstrap.bundle.min.js',
    [],
    filemtime(BONSAI_THEME_DIR . '/assets/js/bootstrap.bundle.min.js'),
    true
  );

  wp_enqueue_script(
    'main',
    BONSAI_THEME_URI . '/assets/js/main.js',
    [],
    filemtime(BONSAI_THEME_DIR . '/assets/js/main.js'),
    true
  );

  // --- SiteMinder IBE widget (only on single-accommodation) ---
  if (is_singular('accommodation')) {
    wp_enqueue_script(
      'siteminder-ibe',
      'https://widget.siteminder.com/ibe.min.js',
      [],
      null,
      [ 'in_footer' => true, 'strategy' => 'defer' ] // WP 6.3+
    );
  }
}
add_action('wp_enqueue_scripts', 'bonsai_assets');
