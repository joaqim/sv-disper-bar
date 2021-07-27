jQuery( function( $ ) {
	if ( typeof disper_end_key === 'undefined' ) {
		return false;
	}
	$(function () {

		let disper_key = disper_end_key +1,
			localize = sv_disper_bar_params;

		$("#sv-disper-add").on( 'click', function() {

			let key = disper_key++,
				html = '';
				
			html += '<tr id="sv-disper-new">';
			html += '<td class="sv-disper-group">';
			html += '<input class="sv-disper-price code" type="text" onkeypress="sv_number_validate(event)" onkeyup="this.value = this.value.replace(/[^0-9\\.]/g, \'\')" name="sv-disper-bar-points[rules][' + key + '][price]">';
			html += ' <input class="sv-disper-percent code" type="text" onkeypress="sv_number_validate(event)" onkeyup="this.value = this.value.replace(/[^0-9\\.]/g, \'\')" name="sv-disper-bar-points[rules][' + key + '][percent]">';
			html += ' <button id="sv-disper-remove" class="button button-secondary" type="button">' + localize.sv_delete + '</button>';
			html += '</td>';
			html += '</tr>';

			$('#sv-disper-box').append(html);

		});

		$(document).on('click', '#sv-disper-remove', function () {
			$(this).closest('#sv-disper-new').remove();
		});

		/**
		 * @link https://make.wordpress.org/core/2012/11/30/new-color-picker-in-wp-3-5/
		 * @link http://automattic.github.io/Iris/
		 * @type {{hide: boolean, defaultColor: boolean, change: change, clear: clear, palettes: *[]}}
		 */
		let sv_disper_container 		= $( '.sv-disper-container-preview' ),
			sv_disper_mess 				= $( '.sv-disper-mess-preview' ),
			sv_disper_link 				= $( '.sv-disper-link-preview' ),
			sv_disper_bar 				= $( '.sv-disper-bar-preview' ),
			sv_disper_item 				= $( '.sv-disper-item-preview' ),
			sv_disper_price 			= $( '.sv-disper-price-preview' ),
			sv_disper_data_title 		= $( '.sv-disper-data-title' ),
			sv_disper_bar_prefix_name 	= 'sv-disper-bar-style[color]',
			sv_disper_bar_picker 		= {
				hide: true,
				width: 230,
				change: function( event, ui ) {
					switch ( this.name ) {
						case sv_disper_bar_prefix_name + '[container-background]':
							return sv_disper_container.css( 'background-color', ui.color.toString() );
						case sv_disper_bar_prefix_name + '[mess-border]':
							return sv_disper_mess.css( 'border-color', ui.color.toString() );
						case sv_disper_bar_prefix_name + '[mess-background]':
							return sv_disper_mess.css( 'background-color', ui.color.toString() );
						case sv_disper_bar_prefix_name + '[link-color]':
							return sv_disper_link.css( 'color', ui.color.toString() );
						case sv_disper_bar_prefix_name + '[bar-background]':
							return sv_disper_bar.css( 'background-color', ui.color.toString() );
						case sv_disper_bar_prefix_name + '[item-background]':
							return sv_disper_item.css( 'background-color', ui.color.toString() );
						case sv_disper_bar_prefix_name + '[price-color]':
							return sv_disper_price.css( 'color', ui.color.toString() );
						case sv_disper_bar_prefix_name + '[price-background]':
							return sv_disper_price.css( 'background-color', ui.color.toString() );
						case sv_disper_bar_prefix_name + '[price-title-color]':
							return sv_disper_data_title.css( 'color', ui.color.toString() );
						case sv_disper_bar_prefix_name + '[price-title-border]':
							return sv_disper_data_title.css( 'border-color', ui.color.toString() );
						case sv_disper_bar_prefix_name + '[price-title-background]':
							return sv_disper_data_title.css( 'background-color', ui.color.toString() );
						default: return false;
					}
				},
				palettes: ['#ffffff', '#292929', '#c107b7', '#3858e9', '#52b344', '#090c21']
			};
		$( '.sv-disper-color-picker' ).wpColorPicker( sv_disper_bar_picker );
		$( '.sv-disper-colors-box .wp-picker-default' ).val( sv_disper_bar_params.sv_reset );
	});

});

function sv_number_validate( e ) {
	let theEvent = e || window.event,
		key = theEvent.keyCode || theEvent.which;

	key = String.fromCharCode( key );

	let regex = /[0-9]|\./;
	if( !regex.test(key) ) {
		theEvent.returnValue = false;
		if ( theEvent.preventDefault ) theEvent.preventDefault();
	}
}