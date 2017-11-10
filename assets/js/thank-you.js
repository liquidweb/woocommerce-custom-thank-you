(function($, w){
	/**
	 * The object that encapsulates all plugin related data and functions
	 *
	 * @type {{textInputID: string, hiddenInputID: string}}
	 */
	var LWWooCommerceThankYouRedir = {
		textInputID: '#product-thank-you-label',
		hiddenInputID: '#product-thank-you',

		autoCompleteOptions: {
			autoFocus: true,
			minLength: 2,

			/**
			 * Performs the AJAX request to the WP ajax endpoint to get the hints
			 *
			 * @param request
			 * @param response
			 */
			source: function(request, response){
				$.ajax({
					url: 'admin-ajax.php',
					data: {
						action: 'wc-thank-you-hint',
						search: request.term
					},
					method: "post",
			        dataType: "json",
					success: function(data){
						if(typeof data.success !== "undefined"){
							response(data.data);
						} else {
							response({});
						}
					},
					error: function(){
						response({});
					}
		        });
			},

			/**
			 * Select callback for setting the value into the right input field (the hidden one)
			 *
			 * @param event
			 * @param ui
			 */
			select: function(event, ui){
				var item = ui.item;

				// The hidden field
				$(LWWooCommerceThankYouRedir.hiddenInputID).val(item.value);

				// The display field
				$(LWWooCommerceThankYouRedir.textInputID).val(item.label);

				event.preventDefault();
			},

			/**
			 * Select callback for setting the value into the right input field (the hidden one)
			 *
			 * @param event
			 * @param ui
			 */
			focus: function(event, ui){
				event.preventDefault();
			}
		}
	};

	/**
	 * Empties the value of the hidden input field (that is only filled when used with autocomplete)
	 */
	LWWooCommerceThankYouRedir.emptyHiddenOnChange = function(){
		var trimmedLabelFieldValue = $(LWWooCommerceThankYouRedir.textInputID).val().trim();

		if(trimmedLabelFieldValue.indexOf('http') === 0 || trimmedLabelFieldValue === ""){
			$(LWWooCommerceThankYouRedir.hiddenInputID).val('');
		}
	};

	/**
	 * Function that sets up all necessary hooks
	 */
	LWWooCommerceThankYouRedir.init = function(){
		$(LWWooCommerceThankYouRedir.textInputID)
			.change(LWWooCommerceThankYouRedir.emptyHiddenOnChange)
			.autocomplete(LWWooCommerceThankYouRedir.autoCompleteOptions);
	};

	// Init onload
	$(function(){
		LWWooCommerceThankYouRedir.init();
	});
})(window.jQuery, window);