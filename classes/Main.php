<?php
/**
 * Main class of the plugin.
 * @package sv-disper-bar
 */
namespace SV_Disper_Bar;
class Main {

	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	public function init() {
		$this->plugin_textdomain();
		$this->hooks();
	}

	/**
	 * Локализация плагина
	 * @link
	 * @see plugin_textdomain
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 16.01.2021
	 */
	public function plugin_textdomain() {
		load_plugin_textdomain(
			SV_DISPER_BAR_PLUGIN_DOMAIN,
			false,
			SV_DISPER_BAR_PLUGIN_NAME . '/languages/'
		);
	}

	public function hooks() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts_admin' ] );

		add_filter( 'plugin_action_links_' . plugin_basename(SV_DISPER_BAR_PLUGIN_FILE ),
			[ $this,  'add_settings_link' ],
			10, 4
		);
	}

	/**
	 * Подключаем JS и CSS
	 * @see enqueue_scripts
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 16.01.2021
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( SV_DISPER_BAR_PLUGIN_NAME,
			SV_DISPER_BAR_PLUGIN_URL .
			'/css/style.css',
			array(), SV_DISPER_BAR_VERSION );

		$options = new Options();
		wp_add_inline_style( SV_DISPER_BAR_PLUGIN_NAME, $options->style_css() );

		wp_enqueue_script( SV_DISPER_BAR_PLUGIN_NAME,
			SV_DISPER_BAR_PLUGIN_URL .
			'/js/main.js',
			array(), SV_DISPER_BAR_VERSION, true );

		$name       = SV_DISPER_BAR_PLUGIN_DOMAIN;
		$options    = new Options();
		$settings   = $options->settings();
		wp_localize_script( SV_DISPER_BAR_PLUGIN_NAME,
			$name . '_params', [
				'ajax_url' 	=> admin_url( 'admin-ajax.php' ),
				'text_no' 	=> apply_filters( 'sv_disper_bar_link_button', $settings['mess']['text_button'] ),
				'text_yas'	=> esc_html__( 'Discount', $name ),
			]
		);
		$functions = new Functions();
		wp_add_inline_script( SV_DISPER_BAR_PLUGIN_NAME, $functions->sv_minify_code( "
		let {$name}_data = {
			action:'{$name}_act',
			type:'json',
		};
		" ), 'after' );

	}

	/**
	 * Подключаем JS и CSS для админки
	 * @see enqueue_scripts_admin
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 16.01.2021
	 */
	public function enqueue_scripts_admin() {

		wp_enqueue_style( SV_DISPER_BAR_PLUGIN_NAME,
			SV_DISPER_BAR_PLUGIN_URL .
			'/css/admin/style.css',
			array(), SV_DISPER_BAR_VERSION );

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		wp_enqueue_script( SV_DISPER_BAR_PLUGIN_NAME,
			SV_DISPER_BAR_PLUGIN_URL .
			'/js/admin/main.js',
			array( 'jquery', 'wp-color-picker' ), SV_DISPER_BAR_VERSION, true );

		$functions = new Functions();
		$disper_end_key = $functions->end_key();
		wp_add_inline_script( SV_DISPER_BAR_PLUGIN_NAME, trim( "
		let disper_end_key = {$disper_end_key};
		" ), 'after' );

		$name = SV_DISPER_BAR_PLUGIN_DOMAIN;
		wp_localize_script( SV_DISPER_BAR_PLUGIN_NAME,
			$name . '_params', [
				'sv_delete'	=> esc_html__( 'Delete', $name ),
				'sv_reset'	=> esc_html__( 'Reset', $name ),
			]
		);
	}

	public function act( $classes ) {
		if ( isset( $classes['functions'] ) ) {
			new Functions();
		}
		if ( isset( $classes['front'] ) ) {
			new Front();
		}
		if ( isset( $classes['admin'] ) ) {
			new Admin();
		}
	}

	/**
	 * Add link to plugin setting page on plugins page.
	 * @see add_settings_link
	 *
	 * @param $actions
	 * @param $plugin_file
	 * @param $plugin_data
	 * @param $context
	 *
	 * @return array
	 * @copyright Copyright (c) 2021, SoveTit RU
	 * Date: 16.01.2021
	 * @author Pavel Ketov <pavel@sovetit.ru>
	 */
	public function add_settings_link( $actions, $plugin_file, $plugin_data, $context ) {
		$ctl_actions = [
			'settings' =>
				'<a href="' . admin_url( 'admin.php?page=' . SV_DISPER_BAR_PLUGIN_NAME ) .
				'" aria-label="' . esc_attr__( 'View settings', SV_DISPER_BAR_PLUGIN_DOMAIN ) . '">' .
				esc_html__( 'Settings', SV_DISPER_BAR_PLUGIN_DOMAIN ) . '</a>',
		];

		return array_merge( $ctl_actions, $actions );
	}
}