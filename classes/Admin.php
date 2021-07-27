<?php
namespace SV_Disper_Bar;
class Admin extends Main {

	public $option_settings			= SV_DISPER_BAR_PLUGIN_NAME     . '-settings';
	public $option_points			= SV_DISPER_BAR_PLUGIN_NAME     . '-points';
	public $option_style				= SV_DISPER_BAR_PLUGIN_NAME     . '-style';

	public $option_settings_group	= SV_DISPER_BAR_PLUGIN_DOMAIN   . '_settings_group';
	public $option_points_group		= SV_DISPER_BAR_PLUGIN_DOMAIN   . '_points_group';
	public $option_style_group		= SV_DISPER_BAR_PLUGIN_DOMAIN   . '_style_group';

	public $option_settings_page		= SV_DISPER_BAR_PLUGIN_DOMAIN   . '_settings_page';
	public $option_points_page		= SV_DISPER_BAR_PLUGIN_DOMAIN   . '_points_page';
	public $option_style_page		= SV_DISPER_BAR_PLUGIN_DOMAIN   . '_style_page';

	public $section_settings_id		= SV_DISPER_BAR_PLUGIN_DOMAIN   . '_settings_id';
	public $section_points_id		= SV_DISPER_BAR_PLUGIN_DOMAIN   . '_points_id';
	public $section_style_id			= SV_DISPER_BAR_PLUGIN_DOMAIN   . '_style_id';

	public function hooks() {
		add_action( 'admin_menu', 		[ $this, 'add_plugin_page' ] );
		add_action( 'admin_init', 		[ $this, 'plugin_settings' ] );
	}

	/**
	 * Страница настроек плагина (опции в массиве)
	 * @link https://wp-kama.ru/id_3773/api-optsiy-nastroek.html#2.-stranitsa-nastroek-plagina
	 * @see add_plugin_page
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 16.01.2021
	 */
	public function add_plugin_page() {
		add_submenu_page(
			'woocommerce',
			esc_html__( 'SV Discount progress bar', SV_DISPER_BAR_PLUGIN_DOMAIN ),
			esc_html__( 'SV Discounts', SV_DISPER_BAR_PLUGIN_DOMAIN ),
			'manage_options',
			SV_DISPER_BAR_PLUGIN_NAME,
			[ $this, 'options_page_output' ],
			3
		);
	}

	/**
	 * Регистрируем настройки.
	 * Настройки будут храниться в массиве, а не одна настройка = одна опция.
	 * @see plugin_settings, sv_disper_bar_processing_settings_callback, sv_disper_bar_processing_points_callback, sv_disper_bar_processing_style_callback
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 16.01.2021
	 */
	public function plugin_settings() {

		// Настройки плагина
		register_setting( $this->option_settings_group, $this->option_settings, 'sv_disper_bar_processing_settings_callback' );
		add_settings_section( $this->section_settings_id, esc_html__( 'Settings', SV_DISPER_BAR_PLUGIN_DOMAIN ), '', $this->option_settings_page );

		add_settings_field(
			'sv-disper-bar-settings-id',
			esc_html__( 'Settings', SV_DISPER_BAR_PLUGIN_DOMAIN ),
			'sv_disper_bar_settings_callback',
			$this->option_settings_page,
			$this->section_settings_id,
			[
				'class' => 'sv_disper_bar_settings',
				'name' => $this->option_settings,
				'value' => 'mess',
				'label_for' => 'sv_disper_bar_settings',
			]
		);

		// Настройки правил
		register_setting( $this->option_points_group, $this->option_points, 'sv_disper_bar_processing_points_callback' );
		add_settings_section( $this->section_points_id, esc_html__( 'Rule settings', SV_DISPER_BAR_PLUGIN_DOMAIN ), '', $this->option_points_page );

		add_settings_field(
			'sv-disper-bar-rules-id',
			esc_html__( 'Add rules', SV_DISPER_BAR_PLUGIN_DOMAIN ),
			'sv_disper_bar_points_callback',
			$this->option_points_page,
			$this->section_points_id,
			[
				'class' => 'sv_disper_bar_rules',
				'name' => $this->option_points,
				'value' => 'rules',
				'label_for' => 'sv_disper_bar_rules',
			]
		);

		// Настройки стилей
		register_setting( $this->option_style_group, $this->option_style, 'sv_disper_bar_processing_style_callback' );
		add_settings_section( $this->section_style_id, esc_html__( 'Style settings', SV_DISPER_BAR_PLUGIN_DOMAIN ), '', $this->option_style_page );

		add_settings_field(
			'sv-disper-bar-style-id',
			esc_html__( 'Styles', SV_DISPER_BAR_PLUGIN_DOMAIN ),
			'sv_disper_bar_style_callback',
			$this->option_style_page,
			$this->section_style_id,
			[
				'id' => 'sv_disper_bar_style',
				'name' => $this->option_style,
				'value' => 'color',
				'label_for' => 'sv_disper_bar_style',
			]
		);

	}

	/**
	 * Вывод содержимого страницы настроек
	 * @link https://inprocess.by/blog/sozdanie-vkladok-na-stranitse-nastroek-v-adminke-wordpress/
	 * @see options_page_output
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 16.01.2021
	 */
	public function options_page_output() {
		$processing = new Processing_Fields();
		?>
		<div class="wrap options_page_box">
			<h1 class="wp-heading-inline"><?php echo get_admin_page_title() ?></h1>
			<hr class="wp-header-end">
			<?php $processing->settings_errors( 'settings' ); ?>
			<?php $processing->settings_errors( 'rules' ); ?>
			<?php $processing->settings_errors( 'style' ); ?>
			<?php $tab = ( empty( $_GET['tab'] ) ) ? '' : sanitize_text_field( $_GET['tab'] );
			if ( isset ( $tab ) ) {
				$this->options_tabs( $tab );
			} else {
				$tab = 'settings';
				$this->options_tabs( $tab );
			}
			if ( $_GET['page'] == SV_DISPER_BAR_PLUGIN_NAME ) {
				$options = new Options();
				switch ( $tab ) {
					case 'style' : ?>
						<form action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>" method="POST">
							<?php // style
							settings_fields( $this->option_style_group );
							do_settings_sections( $this->option_style_page );
							submit_button();
							?>
						</form>
						<?php
						$options->preview();
						break;
					case 'rules' : ?>
						<form action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>" method="POST">
							<?php // settings
							settings_fields( $this->option_points_group );
							do_settings_sections( $this->option_points_page );
							submit_button();
							?>
						</form>
						<?php break;
					default : ?>
						<form action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>" method="POST">
							<?php // settings
							settings_fields( $this->option_settings_group );
							do_settings_sections( $this->option_settings_page );
							submit_button();
							?>
						</form>
						<?php
				}
			}
			?>
		</div>
		<?php
	}

	public function options_tabs( $current = 'settings' ) {
		$tabs = [
			'settings' 	=> esc_html__( 'Settings', SV_DISPER_BAR_PLUGIN_DOMAIN ),
			'rules' 	=> esc_html__( 'Rules', SV_DISPER_BAR_PLUGIN_DOMAIN ),
			'style' 	=> esc_html__( 'Style', SV_DISPER_BAR_PLUGIN_DOMAIN ),
		];
		?>
		<div class="nav-tab-wrapper" style="margin-bottom: 10px;">
			<?php
			foreach( $tabs as $tab => $name ) {
				$class = ( $tab == $current ) ? ' nav-tab-active' : '';
				if ( $tab == 'fields' ) {
					$url = admin_url( 'admin.php?page=' . SV_DISPER_BAR_PLUGIN_NAME );
				} else {
					$url = admin_url( 'admin.php?page=' . SV_DISPER_BAR_PLUGIN_NAME ) . '&tab=' . $tab;
				}
				?>
				<a class="nav-tab<?php echo $class ?>"
				   href="<?php echo $url ?>">
					<?php echo $name ?>
				</a>
				<?php
			} ?>
		</div>
		<?php
	}

}