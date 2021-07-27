<?php
/**
 * Основные настройки плагина
 * @link https://wp-kama.ru/function/add_settings_field
 * @link https://wp-kama.ru/id_3773/api-optsiy-nastroek.html
 * @copyright Copyright (c) 2021, SoveTit RU
 * Date: 16.01.2021
 * @author Pavel Ketov <pavel@sovetit.ru>
 */
defined( 'ABSPATH' ) || exit;
use SV_Disper_Bar\Processing_Fields;
use SV_Disper_Bar\Options;

function sv_disper_bar_settings_callback( $args ) {
	$name    	= sanitize_text_field( $args['name'] );
	$value   	= sanitize_text_field( $args['value'] );
	$options 	= new Options();
	$settings 	= $options->settings();
	$checked 	= ( empty( $settings['mess']['price_mobile'] ) ) ? '' : ' checked="checked"';
	?>
	<table class="sv-disper-settings-box">
		<tr>
			<td><?php esc_html_e( 'Link', SV_DISPER_BAR_PLUGIN_DOMAIN ) ?></td>
			<td>
				<input type="text" name="<?php echo $name ?>[<?php echo $value ?>][link_button]" value="<?php echo $settings['mess']['link_button'] ?>">
			</td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Link text', SV_DISPER_BAR_PLUGIN_DOMAIN ) ?></td>
			<td>
				<input type="text" name="<?php echo $name ?>[<?php echo $value ?>][text_button]" value="<?php echo $settings['mess']['text_button'] ?>">
			</td>
		</tr>
		<tr>
			<td><?php _e( 'Show prices<br>on mobile', SV_DISPER_BAR_PLUGIN_DOMAIN ) ?></td>
			<td>
				<input type="checkbox" name="<?php echo $name ?>[<?php echo $value ?>][price_mobile]"<?php echo $checked ?>>
			</td>
		</tr>
	</table>
	<?php
}
/**
 * Правила
 * @link https://shouts.dev/add-or-remove-input-fields-dynamically-using-jquery
 * @see sv_disper_bar_points_callback
 *
 * @param $args
 *
 * @copyright Copyright (c) 2021, SoveTit RU
 * Date: 16.01.2021
 * @author Pavel Ketov <pavel@sovetit.ru>
 */
function sv_disper_bar_points_callback( $args ) {
	$name    	= sanitize_text_field( $args['name'] );
	$value   	= sanitize_text_field( $args['value'] );
	$options 	= new Options();
	$rules 		= $options->rules();
	?>
	<table id="sv-disper-box" class="sv-disper-box">
		<input type="hidden" name="<?php echo $name ?>[<?php echo $value ?>][0][price]" value="0">
		<input type="hidden" name="<?php echo $name ?>[<?php echo $value ?>][0][percent]" value="0">
		<tr>
			<th class="sv-disper-group">
				<span class="sv-disper-price">
					<?php echo sprintf(
						esc_html__( 'Amount (%s)', SV_DISPER_BAR_PLUGIN_DOMAIN ),
						get_woocommerce_currency_symbol()
					) ?>
				</span>
				<span class="sv-disper-percent">
					<?php esc_html_e( 'Percent %', SV_DISPER_BAR_PLUGIN_DOMAIN ) ?>
				</span>
			</th>
		</tr>
		<?php foreach ( $rules['rules'] as $key => $item ) : if ( $key > 0 ) : ?>
		<tr id="sv-disper-new">
			<td class="sv-disper-group">
				<input type="text" class="sv-disper-price code" name="<?php echo $name ?>[<?php echo $value ?>][<?php echo $key ?>][price]" onkeypress="sv_number_validate(event)" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')" value="<?php echo $item['price'] ?>">
				<input type="text" class="sv-disper-percent code" name="<?php echo $name ?>[<?php echo $value ?>][<?php echo $key ?>][percent]" onkeypress="sv_number_validate(event)" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')" value="<?php echo $item['percent'] ?>">
				<button id="sv-disper-remove" class="sv-disper-remove button button-secondary" type="button"><?php esc_html_e( 'Delete', SV_DISPER_BAR_PLUGIN_DOMAIN ) ?></button>
			</td>
		</tr>
		<?php endif; endforeach; ?>
	</table>
	<table>
		<tr>
			<td colspan="3">
				<button id="sv-disper-add" class="button button-secondary" type="button"><?php esc_html_e( 'Add', SV_DISPER_BAR_PLUGIN_DOMAIN ) ?></button>
			</td>
		</tr>
	</table>

	<?php
}

function sv_disper_bar_style_callback( $args ) {
	$name       = sanitize_text_field( $args['name'] );
	$value      = sanitize_text_field( $args['value'] );
	$options 	= new Options();
	$colors 	= $options->colors();
	?>
	<table class="sv-disper-colors-box">
	<?php foreach ( $colors['color'] as $key => $item ) :  ?>
		<tr>
			<td><?php echo $options->style_title( $key ) ?></td>
			<td>
				<input
						name="<?php echo $name ?>[<?php echo $value ?>][<?php echo $key ?>]"
						type="text"
						class="sv-disper-color-picker"
						value="<?php echo $item ?>"
						data-default-color="<?php echo $item ?>"
				>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
	<?php
}

/**
 * Processing callback settings
 * @param $settings
 * @return array|bool
 */
function sv_disper_bar_processing_settings_callback( $settings ) {
	$processing = new Processing_Fields();
	return $processing->check_settings( $settings );
}
/**
 * Processing callback points
 * @param $settings
 * @return array|bool
 */
function sv_disper_bar_processing_points_callback( $settings ) {
	$processing = new Processing_Fields();
	return $processing->check_points( $settings );
}

/**
 * Processing callback style
 * @param $style
 * @return mixed|void
 */
function sv_disper_bar_processing_style_callback( $style ) {
	$processing = new Processing_Fields();
	return $processing->check_style( $style );
}


