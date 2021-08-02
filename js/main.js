jQuery( function( $ ) {
	if ( typeof sv_disper_bar_params === 'undefined' ) {
		return false;
	}
	$(function () {
		let localize = sv_disper_bar_params;

		function sv_disper_bar() {
			$.post( localize.ajax_url, sv_disper_bar_data, function( response ) {

				let sv_obj = $.parseJSON( response ),
					sv_counter = sv_obj.sv_percent.sv_counter,
					sv_width = sv_obj.sv_percent.sv_width;

				$( '#sv-disper-bar' ).animate(
					{
						width: sv_width + '%'
					},
					1000
				);

				if ( sv_counter > 0 ) {
					$( '#sv-disper-link-text' ).text( localize.text_yas + ': ' + sv_counter + '%' );
				} else {
					$( '#sv-disper-link-text' ).text( localize.text_no );
				}

			});
		}
		$( document.body ).on(
			'added_to_cart addig_to_cart updated_wc_div removed_from_cart updated_cart_totals updated_checkout updated_shipping_method wc_fragment_refresh wc_fragments_refreshed wc_fragments_loaded',
			sv_disper_bar
		);
	});
});

