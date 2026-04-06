<?php
// Only run when WordPress triggers an uninstall (plugin deleted via admin).
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

// Drop the cookies table
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}scc_cookies" );

// Delete all plugin options
$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'scc\_%'" );
