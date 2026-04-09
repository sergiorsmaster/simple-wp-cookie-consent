<?php
// Only run when WordPress triggers an uninstall (plugin deleted via admin).
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

// Drop the cookies table.
// phpcs:ignore WordPress.DB.DirectDatabaseQuery
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}scc_cookies" );

// Delete all plugin options (scc_* keys).
// phpcs:ignore WordPress.DB.DirectDatabaseQuery
$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'scc\_%'" );

// Delete the cookie DB index transient and its timeout companion.
// (WordPress stores transients as _transient_<key> + _transient_timeout_<key>)
delete_transient( 'scc_cookie_db_index' );
