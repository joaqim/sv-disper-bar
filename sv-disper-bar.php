<?php
/**
 * Plugin Name: SV Discount progress bar
 * Description: The plugin shows clients an attractive indicator at the bottom of the site, thereby informing in advance that when buying a product for a certain amount, the client will receive a discount in accordance with the plugin settings.
 * Plugin URI:  https://sovetit.ru/wp-plugins/sv-disper-bar
 * Author URI:  https://sovetit.ru
 * Author:      Pavel Ketov
 * Contributors: Joaqim
 * Version:     2.0.2
 * License:     GPL-2.0+
 * Text Domain: sv_disper_bar
 * Domain Path: /languages
 */
defined( 'ABSPATH' ) || exit;

if ( ! defined( 'SV_DISPER_BAR_PLUGIN_FILE' ) ) {

	// Версия плагина
	define( 'SV_DISPER_BAR_VERSION', '2.0.2' );

	define( 'SV_DISPER_BAR_PLUGIN_FILE', __FILE__ );

	// Абсолютный путь к каталогу плагина
	define( 'SV_DISPER_BAR_PLUGIN_DIR', dirname( SV_DISPER_BAR_PLUGIN_FILE ) );

	// Имя каталога плагина
	define( 'SV_DISPER_BAR_PLUGIN_NAME', basename( SV_DISPER_BAR_PLUGIN_DIR ) );

	// Path: sv-disper-bar/sv-disper-bar.php
	define( 'SV_DISPER_BAR_PLUGIN_REG', SV_DISPER_BAR_PLUGIN_NAME . '/' . SV_DISPER_BAR_PLUGIN_NAME . '.php' );

	// URL каталога плагина
	define( 'SV_DISPER_BAR_PLUGIN_URL', plugins_url( SV_DISPER_BAR_PLUGIN_NAME ) );

	// Text Domain
	define( 'SV_DISPER_BAR_PLUGIN_DOMAIN', 'sv_disper_bar' );

	// Имя кода nonce для защиты Ajax запросов
	define( 'SV_DISPER_BAR_PLUGIN_NONCE_NAME',  SV_DISPER_BAR_PLUGIN_DOMAIN . '_nonce' );

}

/** Load WooCommerce compatibility file. */
if ( class_exists( 'WooCommerce' ) ) {
	add_action( 'admin_notices', SV_DISPER_BAR_PLUGIN_DOMAIN  . '_no_active_woocommerce' );
	return;
}
if( ! in_array('woocommerce/woocommerce.php',
	apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	add_action( 'admin_notices', SV_DISPER_BAR_PLUGIN_DOMAIN . '_no_active_woocommerce' );
	return;
}

require_once SV_DISPER_BAR_PLUGIN_DIR . '/includes/register-plugin.php';
register_activation_hook( SV_DISPER_BAR_PLUGIN_FILE, 	[ 'Register_Plugin', 'activation' ] );
register_deactivation_hook( SV_DISPER_BAR_PLUGIN_FILE, 	[ 'Register_Plugin', 'deactivation' ] );
register_uninstall_hook( SV_DISPER_BAR_PLUGIN_FILE, 	[ 'Register_Plugin', 'uninstall' ] );

/** @see sv_disper_bar_no_active_woocommerce */
function sv_disper_bar_no_active_woocommerce() {
	?>
	<div class="notice notice-warning is-dismissible">
		<p><?php echo sprintf(
				esc_html__( 'For the plugin %s to work correctly, install and activate the plugin WooCommerce!', SV_DISPER_BAR_PLUGIN_DOMAIN ),
				esc_html__( 'SV Discount progress bar', SV_DISPER_BAR_PLUGIN_DOMAIN )
			) ?></p>

	</div>
	<?php
}

require_once SV_DISPER_BAR_PLUGIN_DIR . '/vendor/autoload.php';

$main = new SV_Disper_Bar\Main();

/** @var $clssses Add-Clssses Front */
$clssses = [
	'functions' 	=> 'SV_Disper_Bar\Functions',
	'front' 		=> 'SV_Disper_Bar\Front',
];
$main->act( $clssses );

if ( is_admin() ) {

	/** @var $clssses Add-Clssses Admin */
	$clssses = [
		'admin' => 'SV_Disper_Bar\Admin',
	];
	$main->act( $clssses );

	require_once SV_DISPER_BAR_PLUGIN_DIR . '/includes/options.php';

}
