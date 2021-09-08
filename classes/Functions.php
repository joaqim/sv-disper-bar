<?php
namespace SV_Disper_Bar;
class Functions extends Main {

	public function hooks() {
		if ( wp_doing_ajax() ) {
			add_action( 'wp_ajax_' . SV_DISPER_BAR_PLUGIN_DOMAIN . '_act', [ $this, 'callback' ] );
			add_action( 'wp_ajax_nopriv_' . SV_DISPER_BAR_PLUGIN_DOMAIN . '_act', [ $this, 'callback' ] );
		}
		add_action('woocommerce_cart_calculate_fees', [ $this, 'calculate_price_fees' ], 10 );
	}

	/**
	 * Получаем итоговую сумму WC
	 * @see sub_total_with_discount
	 * @return float
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 16.01.2021
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 */
	public function sub_total_with_discount() {
		$get_subtotal = WC()->cart->get_subtotal() + WC()->cart->get_discount_total();
		$get_subtotal_tax = WC()->cart->get_subtotal_tax() + WC()->cart->get_discount_tax();
		return $get_subtotal + $get_subtotal_tax;
	}

	/**
	 * Получаем итоговую сумму WC
	 * @see sub_total
	 * @return float
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 16.01.2021
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 */
	public function sub_total() {
		return WC()->cart->get_subtotal();
	}

	/**
	 * Считаем на сколько процентов увеличивать прогресс бар
	 * @see width_main
	 *
	 * @param $wc_sub_total
	 * @param $option_price
	 *
	 * @return float|int
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 16.01.2021
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 */
	public function width_main( $wc_sub_total, $option_price ) {
		$width = ( $wc_sub_total / $option_price ) * 100;
		$width = ( $width > 100 ) ? 100 : $width;
		return $width;
	}

	/**
	 * Максимальная сумма из настроек
	 * @see price_max
	 * @return int
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 16.01.2021
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 */
	public function price_max() {
		$price      = 0;
		$options    = new Options();
		$rules      = $options->rules();
		foreach ( $rules['rules'] as $key => $item ) {
			if ( $key === $this->end_key() ) {
				$price = $item['price'];
			}
		}
		return $price;
	}

	/**
	 * Последний ключ
	 * @see end_key
	 * @return int|string|null
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 18.01.2021
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 */
	public function end_key() {
		$options    = new Options();
		$rules      = $options->rules();
		$sv_disper_key = key( array_slice( $rules['rules'], -1, 1, true ) );
		return $sv_disper_key;
	}

	/**
	 * Считаем на сколько процентов увеличивать прогресс бар
	 * @see width
	 * @return float|int
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 16.01.2021
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 */
	public function width() {
		return $this->width_main( $this->sub_total_with_discount(), $this->price_max() );
	}

	/**
	 * Процентная ставка из настроек
	 * @see bar_counter
	 * @return int
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 16.01.2021
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 */
	public function bar_counter() {
		$counter    = 0;
		$sub_total  = $this->sub_total_with_discount();
		$options    = new Options();
		$rules      = $options->rules();
		foreach ( $rules['rules'] as $item ) {
			$price     = $item['price'];
			$percent   = $item['percent'];
			if ( $sub_total >= $price ) {
				$counter = $percent;
			}
		}
		return $counter;
	}

	/**
	 * Получаем Ajax ( let sv_disper_bar_data = { action 'sv_disper_bar_act' } )
	 * @see callback
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 16.01.2021
	 */
	public function callback() {
		$json_percent = [
			'sv_percent' => [
				'sv_counter' 	=> $this->bar_counter(),
				'sv_width' 		=> $this->width(),
				'sv_money_saved' => ($this->sub_total() * $this->bar_counter())
			]
		];
		$json_percent = wp_json_encode( $json_percent );
		wp_die( $json_percent );
	}

	/**
	 * Считаем и выводим итоговую сумму с учетом процентов скидки
	 * @see calculate_price_fees
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 18.01.2021
	 */
	public function calculate_price_fees() {
		$price      = $this->sub_total();
		$percent    = $this->bar_counter();
		$discount   = ( $price * $percent ) / 100;
		$discount   = ( - $discount );
		if ( $percent > 0 ) {
			WC()->cart->add_fee( sprintf(
				esc_html__( 'Discount: %d%s', SV_DISPER_BAR_PLUGIN_DOMAIN ),
				$percent, '%'
			), $discount );
		}
	}

	/**
	 * @see check_color
	 *
	 * @param $value
	 *
	 * @return bool
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 18.01.2021
	 */
	public function check_color( $value ) {
		if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Сжатие Inline CSS и JS кода
	 * @see sv_minify_code
	 *
	 * @param $code
	 *
	 * @return string
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 19.01.2021
	 */
	public function sv_minify_code( $code ){
		$patterns = [];
		$patterns[0] = '/\s+/';
		$patterns[1] = '/ \}/';
		$patterns[2] = '/\} /';
		$patterns[3] = '/\{ /';
		$patterns[4] = '/ \{/';
		$patterns[5] = '/: #/';
		$patterns[6] = '/; /';
		$patterns[7] = '/;\}/';
		$patterns[8] = '/, /';
		$patterns[9] = '/,}/';
		$patterns[10] = '/={/';

		$replacements = [];
		$replacements[0] = ' ';
		$replacements[1] = '}';
		$replacements[2] = $replacements[1];
		$replacements[3] = '{';
		$replacements[4] = $replacements[3];
		$replacements[5] = ':#';
		$replacements[6] = ';';
		$replacements[7] = $replacements[1];
		$replacements[8] = ',';
		$replacements[9] = $replacements[1];
		$replacements[10] = '= {';

		$code = preg_replace( $patterns, $replacements, $code );
		return trim( $code );
	}

	/**
	 * Removes option sv-disper-bar
	 * @see sv_delete_option
	 *
	 * @param $names
	 *
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 23.01.2021
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 */
	public function sv_delete_option( $names ) {
		if ( is_array( $names ) ) {
			foreach ( $names as $name ) {
				delete_option( SV_DISPER_BAR_PLUGIN_NAME . '-' . $name );
			}
		} else {
			delete_option( SV_DISPER_BAR_PLUGIN_NAME . '-' . $names );
		}

	}
}
