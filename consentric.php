<?php
/**
 * Plugin Name:       Consentric — Truly Free Cookie Consent
 * Plugin URI:        https://github.com/sergiorsmaster/consentric
 * Description:       A simple, free, and open-source cookie consent banner. No hidden Pro features, no subscription.
 * Version:           0.6.0
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
define('SCC_VERSION', '0.6.0');
define('SCC_PLUGIN_FILE', __FILE__);
define('SCC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SCC_PLUGIN_URL', plugin_dir_url(__FILE__));

// Autoload core classes
require_once SCC_PLUGIN_DIR . 'includes/class-scc-activator.php';
require_once SCC_PLUGIN_DIR . 'includes/class-scc-deactivator.php';
require_once SCC_PLUGIN_DIR . 'includes/class-scc-consent-store.php';
require_once SCC_PLUGIN_DIR . 'includes/class-scc-shortcodes.php';
require_once SCC_PLUGIN_DIR . 'includes/class-scc-cookie-scanner.php';
require_once SCC_PLUGIN_DIR . 'includes/class-scc-wp-consent-api.php';
require_once SCC_PLUGIN_DIR . 'includes/class-scc-polylang.php';
require_once SCC_PLUGIN_DIR . 'public/class-scc-public.php';
require_once SCC_PLUGIN_DIR . 'admin/class-scc-admin.php';

// Activation / deactivation hooks
register_activation_hook(__FILE__, array('SCC_Activator', 'activate'));
register_deactivation_hook(__FILE__, array('SCC_Deactivator', 'deactivate'));

// Boot frontend and shortcodes
SCC_Public::init();
SCC_Shortcodes::init();
SCC_WP_Consent_API::init();
SCC_Polylang::init();

// Boot admin
if (is_admin()) {
	SCC_Admin::init();
}
