<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CSCC_Deactivator {

	public static function deactivate() {
		// Nothing to do on deactivation.
		// Data and settings are intentionally preserved so they survive plugin updates.
		// Full cleanup happens on uninstall (uninstall.php).
	}
}
