<?php
namespace SV_Disper_Bar;
class Options extends Main {

	/**
	 * Выводим настройки
	 * @see settings
	 * @return array|mixed|void
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 24.01.2021
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 */
	public function settings() {
		$default = [
			'mess' => [
				'text_button'   => esc_html__( 'Get a discount', SV_DISPER_BAR_PLUGIN_DOMAIN ),
				'link_button'   => wc_get_page_permalink( 'shop' ),
				'price_mobile'  => 'on',
			]
		];
		$options = get_option( 'sv-disper-bar-settings', $default );
		$options = ( empty( $options ) ) ? $default : $options;
		return $options;
	}
	/**
	 * Выводим настройки правил
	 * @link
	 * @see rules
	 * @return array|mixed|void
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 18.01.2021
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 */
	public function rules() {
		$default = [
			'rules' => [
				0 => [
					'price' => 0,
					'percent' => 0,
				],
				1 => [
					'price' => 1000,
					'percent' => 1,
				],
				2 => [
					'price' => 3000,
					'percent' => 3,
				],
				3 => [
					'price' => 5000,
					'percent' => 5,
				],
				4 => [
					'price' => 10000,
					'percent' => 10,
				],
			],
		];
		$options = get_option( 'sv-disper-bar-points', $default );
		$options = ( empty( $options ) ) ? $default : $options;
		return $options;
	}

	/**
	 * Цвета
	 * @see colors
	 * @return array|mixed|void
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 19.01.2021
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 */
	public function colors() {
		$default = [
			'color' => [
				'container-background'        => '#3858e9',
				'mess-border'                 => '#3858e9',
				'mess-background'             => '#c107b7',
				'link-color'                  => '#ffffff',
				'bar-background'              => '#52b344',
				'item-background'             => '#ffffff',
				'price-color'                 => '#292929',
				'price-background'            => '#ffffff',
				'price-title-color'           => '#ffffff',
				'price-title-border'          => '#ffffff',
				'price-title-background'      => '#090c21',
			],
		];
		$options = get_option( 'sv-disper-bar-style', $default );
		$options = ( empty( $options ) ) ? $default : $options;
		return $options;
	}

	public function style_title( $key ) {

		switch ( $key ) {
			case 'mess-border' :
				$title = esc_html__( 'The color of the right border of the message block', SV_DISPER_BAR_PLUGIN_DOMAIN );
				break;
			case 'mess-background' :
				$title = esc_html__( 'Message block background color', SV_DISPER_BAR_PLUGIN_DOMAIN );
				break;
			case 'link-color' :
				$title = esc_html__( 'Message block text color (link)', SV_DISPER_BAR_PLUGIN_DOMAIN );
				break;
			case 'bar-background' :
				$title = esc_html__( 'Background color of dynamic progress bar', SV_DISPER_BAR_PLUGIN_DOMAIN );
				break;
			case 'item-background' :
				$title = esc_html__( 'End point label background color', SV_DISPER_BAR_PLUGIN_DOMAIN );
				break;
			case 'price-color' :
				$title = esc_html__( 'Price block text color', SV_DISPER_BAR_PLUGIN_DOMAIN );
				break;
			case 'price-background' :
				$title = esc_html__( 'Price block background color', SV_DISPER_BAR_PLUGIN_DOMAIN );
				break;
			case 'price-title-color' :
				$title = esc_html__( 'Tooltip text color (interest rate)', SV_DISPER_BAR_PLUGIN_DOMAIN );
				break;
			case 'price-title-border' :
				$title = esc_html__( 'Hint block outline color (border)', SV_DISPER_BAR_PLUGIN_DOMAIN );
				break;
			case 'price-title-background' :
				$title = esc_html__( 'Tooltip block background color', SV_DISPER_BAR_PLUGIN_DOMAIN );
				break;
			default :
				$title = esc_html__( 'Common container color', SV_DISPER_BAR_PLUGIN_DOMAIN );;
		}
		return $title;
	}

	/**
	 * Получаем цвет по ключу
	 * @see color_css
	 *
	 * @param $get_key
	 *
	 * @return bool|mixed
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 19.01.2021
	 */
	public function color_css( $get_key ) {
		$options    = new Options();
		$colors     = $options->colors();
		foreach( $colors['color'] as $key => $item ) {
			if ( $key === $get_key ) {
				return $item;
			}
		}
		return false;
	}

	/**
	 * Получаем весь CSS из настроек
	 * @see style_css
	 * @return string|string[]|null
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 19.01.2021
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 */
	public function style_css() {
		$functions = new Functions();
		$css = "
		.sv-disper-container {
			background-color: {$this->color_css( 'container-background' )};
		}
		.sv-disper-container .sv-disper-mess {
			border-color: {$this->color_css( 'mess-border' )};
			background-color: {$this->color_css( 'mess-background' )};
		}
		.sv-disper-container .sv-disper-mess .sv-disper-link {
			color: {$this->color_css( 'link-color' )};
		}
		.sv-disper-container .sv-disper-prices .sv-disper-bar {
			background-color: {$this->color_css( 'bar-background' )};
		}
		.sv-disper-container .sv-disper-prices .sv-disper-item {
			background-color: {$this->color_css( 'item-background' )};
		}
		.sv-disper-container .sv-disper-prices .sv-disper-item .sv-disper-price {
			color: {$this->color_css( 'price-color' )};
			background-color: {$this->color_css( 'price-background' )};
		}
		.sv-disper-container .sv-disper-prices .sv-disper-item .sv-disper-price[data-title]:hover::before {
			color: {$this->color_css( 'price-title-color' )};
			border-color: {$this->color_css( 'price-title-border' )};
			background-color: {$this->color_css( 'price-title-background' )};
		}
		";
		return $functions->sv_minify_code( $css );
	}

	/**
	 * Предпросмотр прогресс бара в режиме реального времени при изменениях в настройках стиля
	 * @see preview
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 26.01.2021
	 */
	public function preview() {
		?>
		<div class="sv-disper-container-preview" style="background-color:<?php echo $this->color_css( 'container-background' ) ?>">
			<div class="sv-disper-mess-preview" style="border-color:<?php echo $this->color_css( 'mess-border' ) ?>;background-color:<?php echo $this->color_css( 'mess-background' ) ?>">
				<span class="sv-disper-link-preview" style="color:<?php echo $this->color_css( 'link-color' ) ?>">
					<?php esc_html_e( 'Get a discount', SV_DISPER_BAR_PLUGIN_DOMAIN ) ?>
				</span>
			</div>
			<div class="sv-disper-prices-preview">
				<div class="sv-disper-bar-preview" style="width:15%; background-color:<?php echo $this->color_css( 'bar-background' ) ?>"></div>
				<div class="sv-disper-item-preview" style="left:25%; background-color:<?php echo $this->color_css( 'item-background' ) ?>">
					<div class="sv-disper-data-title" style="color:<?php echo $this->color_css( 'price-title-color' ) ?>;border-color:<?php echo $this->color_css( 'price-title-border' ) ?>;background-color:<?php echo $this->color_css( 'price-title-background' ) ?>">
						<?php echo sprintf(
							esc_html__( 'Discount: %d%s', SV_DISPER_BAR_PLUGIN_DOMAIN ),
							15, '%'
						) ?>
					</div>
					<div class="sv-disper-price-preview" style="color:<?php echo $this->color_css( 'price-color' ) ?>; background-color:<?php echo $this->color_css( 'price-background' ) ?>">
						<?php echo sprintf( esc_html__( 'of %s', SV_DISPER_BAR_PLUGIN_DOMAIN ), wc_price( wc_format_decimal( '1,500', false ), [ 'currency' => get_woocommerce_currency() ] )) ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}