/**
 * Workshop waiting list
 *
 * Submits the sold-out waiting list form over AJAX so the visitor stays on
 * the page. Without JS the form still posts to admin-post.php and redirects
 * back with a ?waitlist= result code.
 *
 * Markup / handler: inc/waiting-list.php
 * Styles: assets/css/woocommerce-workshop.css
 * Localised data: bonsaiWaitlist.ajaxUrl, bonsaiWaitlist.sending, bonsaiWaitlist.error
 */
(function($) {
	var $wrap = $('#workshop-waitlist');

	if ( ! $wrap.length || typeof bonsaiWaitlist === 'undefined' ) {
		return;
	}

	var $form    = $wrap.find('.workshop-waitlist-form');
	var $button  = $form.find('button[type="submit"]');
	var $message = $wrap.find('.workshop-waitlist-message');
	var label    = $button.text();

	function bonsai_waitlist_message(text, isSuccess) {
		$message
			.removeClass('is-success is-error')
			.addClass(isSuccess ? 'is-success' : 'is-error')
			.text(text);
	}

	$form.on('submit.bonsai', function(e) {
		e.preventDefault();

		$button.prop('disabled', true).text(bonsaiWaitlist.sending);

		$.post(bonsaiWaitlist.ajaxUrl, $form.serialize())
			.done(function(response) {
				var data = response && response.data ? response.data : {};
				bonsai_waitlist_message(data.message || bonsaiWaitlist.error, !! response.success);

				if ( response.success ) {
					$wrap.addClass('is-complete');
				}
			})
			.fail(function(xhr) {
				// wp_send_json_error() responses arrive here (4xx/5xx) with a message.
				var data = xhr.responseJSON && xhr.responseJSON.data ? xhr.responseJSON.data : {};
				bonsai_waitlist_message(data.message || bonsaiWaitlist.error, false);
			})
			.always(function() {
				$button.prop('disabled', false).text(label);
			});
	});
})(jQuery);
