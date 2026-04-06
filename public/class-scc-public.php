<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SCC_Public {

	public static function init() {
		add_action( 'wp_head',             array( __CLASS__, 'inject_gtm_consent_defaults' ), 1 );
		add_action( 'wp_enqueue_scripts',  array( __CLASS__, 'enqueue_scripts' ) );
	}

	/**
	 * Inject gtag('consent', 'default', {...}) as the very first <head> script.
	 *
	 * Must fire before GTM / any Google tag so that Consent Mode v2 is active
	 * from the moment those tags load. Only outputs when GTM integration is enabled.
	 */
	public static function inject_gtm_consent_defaults() {
		if ( ! get_option( 'scc_enabled', '1' ) ) {
			return;
		}

		if ( ! get_option( 'scc_gtm_enabled', '0' ) ) {
			return;
		}

		$wait_ms = (int) get_option( 'scc_gtm_wait_for_update', 500 );
		?>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}

gtag('consent', 'default', {
	'ad_storage':             'denied',
	'analytics_storage':      'denied',
	'ad_user_data':           'denied',
	'ad_personalization':     'denied',
	'functionality_storage':  'denied',
	'personalization_storage':'denied',
	'security_storage':       'granted',
	'wait_for_update':        <?php echo $wait_ms; ?>
});

gtag('set', 'ads_data_redaction', true);
gtag('set', 'url_passthrough', false);
</script>
		<?php
	}

	/**
	 * Enqueue frontend scripts.
	 */
	public static function enqueue_scripts() {
		if ( ! get_option( 'scc_enabled', '1' ) ) {
			return;
		}

		// Core consent storage (always loaded)
		wp_enqueue_script(
			'scc-consent',
			SCC_PLUGIN_URL . 'public/assets/scc-consent.js',
			array(),
			SCC_VERSION,
			false // load in <head>
		);

		// Pass settings to JS
		wp_localize_script( 'scc-consent', 'sccSettings', array(
			'debug'   => (bool) get_option( 'scc_debug', '0' ),
			'gtmMode' => get_option( 'scc_gtm_mode', 'basic' ),
		) );

		// GTM bridge (only when GTM integration is enabled)
		if ( get_option( 'scc_gtm_enabled', '0' ) ) {
			wp_enqueue_script(
				'scc-gtm',
				SCC_PLUGIN_URL . 'public/assets/scc-gtm.js',
				array( 'scc-consent' ),
				SCC_VERSION,
				false // load in <head>, after scc-consent
			);
		}
	}
}
