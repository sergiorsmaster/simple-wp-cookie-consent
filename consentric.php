<?php
/**
 * Plugin Name:       Consentric — Simple Cookie Consent
 * Plugin URI:        https://github.com/sergiorsmaster/consentric
 * Description:       A simple, free, and open-source cookie consent banner. No hidden Pro features, no subscription.
 * Version:           0.7.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Sergio Ricardo
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       consentric
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
	exit;
}

// Plugin constants
define('CSCC_VERSION', '0.7.0');
define('CSCC_PLUGIN_FILE', __FILE__);
define('CSCC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CSCC_PLUGIN_URL', plugin_dir_url(__FILE__));

// Autoload core classes
require_once CSCC_PLUGIN_DIR . 'includes/class-cscc-activator.php';
require_once CSCC_PLUGIN_DIR . 'includes/class-cscc-deactivator.php';
require_once CSCC_PLUGIN_DIR . 'includes/class-cscc-consent-store.php';
require_once CSCC_PLUGIN_DIR . 'includes/class-cscc-shortcodes.php';
require_once CSCC_PLUGIN_DIR . 'includes/class-cscc-cookie-scanner.php';
require_once CSCC_PLUGIN_DIR . 'includes/class-cscc-wp-consent-api.php';
require_once CSCC_PLUGIN_DIR . 'includes/class-cscc-polylang.php';
require_once CSCC_PLUGIN_DIR . 'public/class-cscc-public.php';
require_once CSCC_PLUGIN_DIR . 'admin/class-cscc-admin.php';

// Activation / deactivation hooks
register_activation_hook(__FILE__, array('CSCC_Activator', 'activate'));
register_deactivation_hook(__FILE__, array('CSCC_Deactivator', 'deactivate'));

// Boot frontend and shortcodes
CSCC_Public::init();
CSCC_Shortcodes::init();
CSCC_WP_Consent_API::init();
CSCC_Polylang::init();

// Boot admin
if (is_admin()) {
	CSCC_Admin::init();
}
