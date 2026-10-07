<main id="main" class="site-main">
  <?php
  // Cart / Checkout / My Account render their WooCommerce shortcode (inc/woocommerce.php);
  // every other page renders the page_builder modules.
  $bonsai_wc_rendered = function_exists( 'bonsai_wc_render_core_page' ) && bonsai_wc_render_core_page();

  if ( ! $bonsai_wc_rendered ) {
    include get_template_directory() . '/template-parts/modules/page_builder.php';
  }
  ?>
</main>
