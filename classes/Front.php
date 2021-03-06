<?php
namespace SV_Disper_Bar;
class Front extends Main {

	public function hooks() {
		add_action( 'wp_footer', [ $this, 'html_footer' ], 10 );
	}

	/**
	 * HTML конечных точек
	 * @see html_item
	 */
	public function html_item() {
		$functions 	= new Functions();
		$options 	= new Options();
		$rules 		= $options->rules();
		$settings 	= $options->settings();
		foreach ( $rules['rules'] as $key => $item ) : if ( $key > 0 ) :
			$width = $functions->width_main( $item['price'], $functions->price_max() );
			?>
			<div class="sv-disper-item" style="left:<?php echo $width ?>%;">
				<?php if ( ! wp_is_mobile() ) : ?>
					<div class="sv-disper-price" data-title="<?php echo sprintf(
						esc_html__( 'Discount: %d%s', SV_DISPER_BAR_PLUGIN_DOMAIN ),
						$item['percent'], '%'
					) ?>">
						<?php echo sprintf(
							esc_html__( 'of %s', SV_DISPER_BAR_PLUGIN_DOMAIN ),
							wc_price( wc_format_decimal( $item['price'], false ), [ 'currency' => get_woocommerce_currency() ] )
						) ?>
					</div>
				<?php else : ?>
					<?php if ( ! empty( $settings['mess']['price_mobile'] ) ) : ?>
					<div class="sv-disper-price">
						<?php echo $item['price'] ?>
					</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		<?php endif; endforeach;
	}

	/**
	 * HTML контейнера бара
	 * @see html_footer
	 */
	public function html_footer() {
		$functions 		= new Functions();
		$options 		  = new Options();
		$sv_counter 	= $functions->bar_counter();
		$settings 		= $options->settings();
		$sv_width     = $functions->width();
		$animate_bar  = ($sv_width == 0 && is_front_page());
		$striped_bar  = (! empty( $settings['mess']['striped_bar'] ) );

		if ( $sv_counter > 0 ) {
			$link_button = apply_filters( 'sv_disper_bar_link_button', $settings['mess']['link_button'] );
			$text_button = sprintf(
					esc_html__( 'Discount: %d%s', SV_DISPER_BAR_PLUGIN_DOMAIN ),
					$sv_counter, '%'
			);
		} else {
			$link_button = apply_filters( 'sv_disper_bar_link_button', $settings['mess']['link_button'] );
			$text_button = apply_filters( 'sv_disper_bar_text_button', $settings['mess']['text_button'] );
		}
		$prices_bar_percent = ' style="width:' . $functions->width() . '%;"';
		?>
		<div class="sv-disper-container">
			<div class="sv-disper-mess">
				<a href="<?php echo $link_button ?>" class="sv-disper-link">
					<span id="sv-disper-link-text"><?php echo $text_button ?></span>
				</a>
			</div>
			<div class="sv-disper-prices">
				<div
					id="sv-disper-bar"
					<?php echo 'class="sv-disper-bar' .
					(($animate_bar) ? ' sv-disper-bar-animate' : '') .
					(($striped_bar) ? ' sv-disper-bar-striped' : '') . '"' ?>
					<?php echo $prices_bar_percent ?>>
				</div>
				<?php self::html_item() ?>
			</div>
		</div>
		<?php
	}
}
