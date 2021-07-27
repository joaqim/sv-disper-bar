<?php
/**
 * Activate/Deactivate/Uninstall
 */
defined( 'ABSPATH' ) || exit;
use SV_Disper_Bar\Functions;
class Register_Plugin {

	public function activation() {}

	public function deactivation() {
		if ( ! current_user_can( 'activate_plugins' ) ) return false;
		check_admin_referer( "deactivate-plugin_" . SV_DISPER_BAR_PLUGIN_REG );
		$functions = new Functions();
		return $functions->sv_delete_option( [ 'settings', 'points', 'style' ] );
	}

	public function uninstall() {
		if ( ! current_user_can( 'activate_plugins' ) ) return false;
		$functions = new Functions();
		return $functions->sv_delete_option( [ 'settings', 'points', 'style' ] );
	}
}