<?php
/**
 * SCC Shortcodes
 *
 * [scc_preferences]
 *   Renders a link that opens the cookie preferences modal.
 *   Attributes:
 *     label  — link text (default: "Cookie Settings")
 *     class  — extra CSS classes on the <a> tag
 *
 * [scc_cookie_list]
 *   Renders a formatted table of cookies grouped by category.
 *   Attributes:
 *     categories — comma-separated list to filter (default: all)
 *                  e.g. categories="analytics,marketing"
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SCC_Shortcodes {

	public static function init() {
		add_shortcode( 'scc_preferences',  array( __CLASS__, 'render_preferences' ) );
		add_shortcode( 'scc_cookie_list',  array( __CLASS__, 'render_cookie_list' ) );
	}

	/**
	 * [scc_preferences label="Cookie Settings" class=""]
	 */
	public static function render_preferences( $atts ) {
		$atts = shortcode_atts(
			array(
				'label' => __( 'Cookie Settings', 'consentric' ),
				'class' => '',
			),
			$atts,
			'scc_preferences'
		);

		$classes = trim( 'scc-preferences-link ' . sanitize_html_class( $atts['class'] ) );

		return sprintf(
			'<a href="#" class="%s" data-scc-action="open-preferences">%s</a>',
			esc_attr( $classes ),
			esc_html( $atts['label'] )
		);
	}

	/**
	 * [scc_cookie_list categories="necessary,analytics,marketing,functional"]
	 */
	public static function render_cookie_list( $atts ) {
		global $wpdb;

		$atts = shortcode_atts(
			array( 'categories' => '' ),
			$atts,
			'scc_cookie_list'
		);

		$all_categories = array(
			'necessary'  => __( 'Necessary', 'consentric' ),
			'analytics'  => __( 'Analytics', 'consentric' ),
			'marketing'  => __( 'Marketing', 'consentric' ),
			'functional' => __( 'Functional', 'consentric' ),
		);

		// Filter categories if the attribute is set.
		if ( ! empty( $atts['categories'] ) ) {
			$requested = array_map( 'trim', explode( ',', $atts['categories'] ) );
			$requested = array_intersect( $requested, array_keys( $all_categories ) );
			$categories = array_intersect_key( $all_categories, array_flip( $requested ) );
		} else {
			$categories = $all_categories;
		}

		if ( empty( $categories ) ) {
			return '';
		}

		$table   = $wpdb->prefix . 'scc_cookies';
		$placeholders = implode( ', ', array_fill( 0, count( $categories ), '%s' ) );
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $table and $placeholders are trusted
		$cookies = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$table} WHERE category IN ({$placeholders}) ORDER BY category, cookie_name", // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
				...array_keys( $categories )
			)
		);

		// Group by category.
		$grouped = array_fill_keys( array_keys( $categories ), array() );
		foreach ( $cookies as $cookie ) {
			if ( isset( $grouped[ $cookie->category ] ) ) {
				$grouped[ $cookie->category ][] = $cookie;
			}
		}

		ob_start();

		foreach ( $categories as $slug => $label ) {
			if ( empty( $grouped[ $slug ] ) ) {
				continue;
			}
			?>
			<div class="scc-cookie-list__group">
				<h3 class="scc-cookie-list__heading scc-cookie-list__heading--<?php echo esc_attr( $slug ); ?>">
					<?php echo esc_html( $label ); ?>
				</h3>
				<div class="scc-cookie-list__scroll">
					<table class="scc-cookie-list__table">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Cookie', 'consentric' ); ?></th>
								<th><?php esc_html_e( 'Service', 'consentric' ); ?></th>
								<th><?php esc_html_e( 'Duration', 'consentric' ); ?></th>
								<th><?php esc_html_e( 'Description', 'consentric' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $grouped[ $slug ] as $cookie ) : ?>
								<tr>
									<td><code class="scc-cookie-list__name"><?php echo esc_html( $cookie->cookie_name ); ?></code></td>
									<td><?php echo esc_html( $cookie->service ); ?></td>
									<td class="scc-cookie-list__duration"><?php echo esc_html( $cookie->duration ); ?></td>
									<td><?php echo esc_html( $cookie->description ); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
			<?php
		}

		// Empty state — no cookies in any requested category.
		if ( empty( array_filter( $grouped ) ) ) {
			echo '<p class="scc-cookie-list__empty">' .
				esc_html__( 'No cookies have been added yet.', 'consentric' ) .
				'</p>';
		}

		return ob_get_clean();
	}
}
