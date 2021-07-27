<?php
namespace SV_Disper_Bar;
class Processing_Fields extends Main {

	/**
	 * @see settings_errors
	 *
	 * @param $setting
	 *
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 16.01.2021
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 */
	public function settings_errors( $setting ) {
		return settings_errors( $setting );
	}

	/**
	 * Обработка настроек плагина
	 * @see check_settings
	 *
	 * @param $settings
	 *
	 * @return mixed
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 24.01.2021
	 */
	public function check_settings( $settings ) {
		$code = 'check';
		foreach( $settings as $name => & $value ) {
			if ( empty( $value['text_button'] ) ) {
				$message    = esc_html__( 'Text field cannot be empty', SV_DISPER_BAR_PLUGIN_DOMAIN );
				$type       = 'error';
				add_settings_error( 'settings', $code, $message, $type );
				$options = new Options();
				return $options->settings();
			}
			if ( empty( $value['link_button'] ) ) {
				$message    = esc_html__( 'The link field cannot be empty', SV_DISPER_BAR_PLUGIN_DOMAIN );
				$type       = 'error';
				add_settings_error( 'settings', $code, $message, $type );
				$options = new Options();
				return $options->settings();
			}
		}
		$message    = esc_html__( 'The settings were successfully saved.', SV_DISPER_BAR_PLUGIN_DOMAIN );
		$type       = 'success';
		add_settings_error( 'settings', $code, $message, $type );

		return $settings;
	}

	/**
	 * Обработка правил
	 * @see check_points
	 *
	 * @param $settings
	 *
	 * @return mixed|void
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 16.01.2021
	 */
	public function check_points( $settings ) {
		$code       = 'check';
		$options    = new Options();
		$rules   	= $settings['rules'];
		$count      = count( $rules );
		foreach( $rules as $key => & $value ) {

			$price      = $value['price'];
			$percent    = $value['percent'];

			if ( $count < 2 ) {
				$message    = esc_html__( 'There must be at least one rule', SV_DISPER_BAR_PLUGIN_DOMAIN );
				$type       = 'error';
				add_settings_error( 'rules', $code, $message, $type );
				return $options->rules();
			}

			if ( ! is_numeric( $price ) ) {
				$message    = esc_html__( 'The value of the amount field can only contain numbers', SV_DISPER_BAR_PLUGIN_DOMAIN );
				$type       = 'error';
				add_settings_error( 'rules', $code, $message, $type );
				return $options->rules();
			}

			if ( ! is_numeric( $percent ) ) {
				$message    = esc_html__( 'The value of the percentage field can only be numbers', SV_DISPER_BAR_PLUGIN_DOMAIN );
				$type       = 'error';
				add_settings_error( 'rules', $code, $message, $type );
				return $options->rules();
			}

			if ( $key > 0 && $price == 0 ) {
				$message    = esc_html__( 'The value of the field amount cannot be equal to zero', SV_DISPER_BAR_PLUGIN_DOMAIN );
				$type       = 'error';
				add_settings_error( 'rules', $code, $message, $type );
				return $options->rules();
			}

			if ( $key > 0 && $percent == 0 ) {
				$message    = esc_html__( 'The percentage field value cannot be zero', SV_DISPER_BAR_PLUGIN_DOMAIN );
				$type       = 'error';
				add_settings_error( 'rules', $code, $message, $type );
				return $options->rules();
			}
			if ( $key > 0 && $percent > 100 ) {
				$message    = esc_html__( 'The value of the field percentage cannot be more than 100%', SV_DISPER_BAR_PLUGIN_DOMAIN );
				$type       = 'error';
				add_settings_error( 'rules', $code, $message, $type );
				return $options->rules();
			}

		}

		$message    = esc_html__( 'The rules were successfully saved.', SV_DISPER_BAR_PLUGIN_DOMAIN );
		$type       = 'success';
		add_settings_error( 'rules', $code, $message, $type );

		return $settings;
	}

	/**
	 * Обработка стилей
	 * @see check_style
	 *
	 * @param $settings
	 *
	 * @return mixed|void
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 16.01.2021
	 */
	public function check_style( $settings ) {
		$code       = 'check';
		$functions   = new Functions();

		foreach( $settings as $name => & $value ) {

			if (
				$functions->check_color( $value['container-background'] )       == false ||
				$functions->check_color( $value['mess-border'] )                == false ||
				$functions->check_color( $value['mess-background'] )            == false ||
				$functions->check_color( $value['link-color'] )                 == false ||
				$functions->check_color( $value['bar-background'] )             == false ||
				$functions->check_color( $value['price-color'] )                == false ||
				$functions->check_color( $value['price-background'] )           == false ||
				$functions->check_color( $value['price-title-color'] )          == false ||
				$functions->check_color( $value['price-title-border'] )         == false ||
				$functions->check_color( $value['price-title-background'] )     == false
			) {
				$message    = esc_html__( 'Color field value does not match CSS format NEX', SV_DISPER_BAR_PLUGIN_DOMAIN );
				$type       = 'error';
				add_settings_error( 'style', $code, $message, $type );
				$options = new Options();
				return $options->colors();
			}

		}

		$message    = esc_html__( 'The color scheme was successfully saved.', SV_DISPER_BAR_PLUGIN_DOMAIN );
		$type       = 'success';
		add_settings_error( 'style', $code, $message, $type );

		return $settings;
	}
}