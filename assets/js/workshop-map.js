/**
 * Workshop map pop-up
 *
 * Opens the location map in a native <dialog> on workshop product pages.
 * The Google Maps iframe src is only set on first open, so no Google
 * request happens unless the visitor asks for the map.
 *
 * Markup: template-parts/woocommerce/workshop-product.php
 * Styles: assets/css/woocommerce-workshop.css
 * Enqueued only on workshop products (inc/woocommerce.php → bonsai_wc_assets()).
 */
(function($) {
	var $dialog = $('#workshop-map-dialog');

	// No dialog on the page, or browser lacks <dialog> — the trigger stays a normal link to Google Maps.
	if ( ! $dialog.length || typeof $dialog[0].showModal !== 'function' ) {
		return;
	}

	var dialog = $dialog[0];

	function bonsai_open_workshop_map(e) {
		e.preventDefault();

		// Lazy-load the iframe on first open.
		var $frame = $dialog.find('iframe[data-src]');
		if ( $frame.length ) {
			$frame.attr('src', $frame.attr('data-src')).removeAttr('data-src');
		}

		dialog.showModal();
		$('body').addClass('workshop-map-open');
	}

	function bonsai_close_workshop_map() {
		dialog.close();
	}

	$('.workshop-map-trigger').on('click.bonsai', bonsai_open_workshop_map);
	$dialog.find('.workshop-map-close').on('click.bonsai', bonsai_close_workshop_map);

	// Click on the backdrop (outside the dialog box) closes it.
	$dialog.on('click.bonsai', function(e) {
		if ( e.target === dialog ) {
			bonsai_close_workshop_map();
		}
	});

	// Fires for Esc as well as the close button — unlock page scroll.
	$dialog.on('close.bonsai', function() {
		$('body').removeClass('workshop-map-open');
	});
})(jQuery);
