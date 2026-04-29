<?php
if (!defined('ABSPATH')) {
	exit;
}

global $wpdb;
$cscc_table = $wpdb->prefix . 'cscc_cookies';

// Edit cookie: verify nonce before reading cookie_id from URL.
$cscc_edit_id     = 0;
$cscc_edit_cookie = null;
if ( isset( $_GET['action'], $_GET['cookie_id'], $_GET['_wpnonce'] )
	&& 'edit_cookie' === sanitize_key( wp_unslash( $_GET['action'] ) )
	&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'cscc_edit_cookie' )
) {
	$cscc_edit_id = absint( $_GET['cookie_id'] );
}
if ( $cscc_edit_id ) {
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- $cscc_table uses trusted prefix
	$cscc_edit_cookie = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$cscc_table} WHERE id = %d", $cscc_edit_id ) );
}

// Status messages via transient (set on redirect in class-cscc-admin.php).
$cscc_messages = array(
	'added'   => __( 'Cookie added.', 'consentric' ),
	'updated' => __( 'Cookie updated.', 'consentric' ),
	'deleted' => __( 'Cookie deleted.', 'consentric' ),
);
$cscc_msg = get_transient( 'cscc_admin_notice' );
if ( $cscc_msg && isset( $cscc_messages[ $cscc_msg ] ) ) {
	delete_transient( 'cscc_admin_notice' );
	echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( $cscc_messages[ $cscc_msg ] ) . '</p></div>';
}

$cscc_categories = array(
	'necessary' => __('Necessary', 'consentric'),
	'analytics' => __('Analytics', 'consentric'),
	'marketing' => __('Marketing', 'consentric'),
	'functional' => __('Functional', 'consentric'),
);

$cscc_sources = array(
	'manual' => __('Manual', 'consentric'),
	'scan' => __('Scan', 'consentric'),
	'cookiedb' => __('Cookie DB', 'consentric'),
);

$cscc_page_url = admin_url('options-general.php?page=cscc-cookie-consent&tab=cookies');
?>
<div class="cscc-tab-content">

	<!-- Add / Edit form -->
	<div class="cscc-cookie-form-wrap" id="cscc-cookie-form-wrap" <?php echo ! $cscc_edit_cookie ? 'style="display:none"' : ''; ?>>

		<h2 class="cscc-section-title">
			<?php echo $cscc_edit_cookie ? esc_html__('Edit Cookie', 'consentric') : esc_html__('Add Cookie', 'consentric'); ?>
		</h2>

		<form method="post" action="<?php echo esc_url($cscc_page_url); ?>">
			<?php wp_nonce_field('cscc_save_cookie', 'cscc_cookie_nonce'); ?>
			<input type="hidden" name="cookie_id" value="<?php echo esc_attr($cscc_edit_id); ?>">

			<div class="cscc-field">
				<label class="cscc-field__label" for="cscc_cookie_name">
					<?php esc_html_e('Cookie Name', 'consentric'); ?> <span class="required">*</span>
				</label>
				<div class="cscc-field__control">
					<input type="text" id="cscc_cookie_name" name="cookie_name" class="regular-text" required
						value="<?php echo esc_attr($cscc_edit_cookie->cookie_name ?? ''); ?>" placeholder="_ga">
				</div>
			</div>

			<div class="cscc-field">
				<label class="cscc-field__label" for="cscc_category">
					<?php esc_html_e('Category', 'consentric'); ?>
				</label>
				<div class="cscc-field__control">
					<select id="cscc_category" name="category">
						<?php foreach ($cscc_categories as $cscc_key => $cscc_label): ?>
							<option value="<?php echo esc_attr($cscc_key); ?>" <?php selected($cscc_edit_cookie->category ?? 'necessary', $cscc_key); ?>>
								<?php echo esc_html($cscc_label); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="cscc-field">
				<label class="cscc-field__label" for="cscc_service">
					<?php esc_html_e('Service', 'consentric'); ?>
				</label>
				<div class="cscc-field__control">
					<input type="text" id="cscc_service" name="service" class="regular-text"
						value="<?php echo esc_attr($cscc_edit_cookie->service ?? ''); ?>"
						placeholder="<?php esc_attr_e('e.g. Google Analytics', 'consentric'); ?>">
				</div>
			</div>

			<div class="cscc-field">
				<label class="cscc-field__label" for="cscc_duration">
					<?php esc_html_e('Duration', 'consentric'); ?>
				</label>
				<div class="cscc-field__control">
					<input type="text" id="cscc_duration" name="duration" class="regular-text"
						value="<?php echo esc_attr($cscc_edit_cookie->duration ?? ''); ?>"
						placeholder="<?php esc_attr_e('e.g. 2 years', 'consentric'); ?>">
				</div>
			</div>

			<div class="cscc-field">
				<label class="cscc-field__label" for="cscc_description">
					<?php esc_html_e('Description', 'consentric'); ?>
				</label>
				<div class="cscc-field__control">
					<textarea id="cscc_description" name="description" class="large-text" rows="2"><?php
					echo esc_textarea($cscc_edit_cookie->description ?? '');
					?></textarea>
				</div>
			</div>

			<div class="cscc-form-actions">
				<?php submit_button($cscc_edit_cookie ? __('Update Cookie', 'consentric') : __('Add Cookie', 'consentric'), 'primary', 'submit', false); ?>
				<a href="<?php echo esc_url($cscc_page_url); ?>" class="button">
					<?php esc_html_e('Cancel', 'consentric'); ?>
				</a>
			</div>
		</form>
	</div>

	<!-- Cookie DB status -->
	<?php
	$cscc_db_count = CSCC_Cookie_Scanner::db_count();
	?>
	<p class="cscc-cookie-db-status">
		<?php
		printf(
			/* translators: 1: number of cookies, 2: opening <a> tag, 3: closing </a> tag */
			esc_html__( 'Cookie database: %1$d entries loaded from the %2$sOpen Cookie Database%3$s.', 'consentric' ),
			intval( $cscc_db_count ),
			'<a href="https://github.com/jkwakman/Open-Cookie-Database" target="_blank" rel="noopener noreferrer">',
			'</a>'
		);
		?>
	</p>

	<!-- Table header -->
	<div class="cscc-table-header">
		<h2 class="cscc-section-title" style="margin-top:0">
			<?php esc_html_e('Cookie List', 'consentric'); ?>
		</h2>
		<?php if (!$cscc_edit_cookie): ?>
			<button type="button" class="button" id="cscc-scan-btn">
				<?php esc_html_e('Run Scanner', 'consentric'); ?>
			</button>
			<button type="button" class="button button-primary" id="cscc-add-cookie-btn">
				<?php esc_html_e('+ Add Cookie', 'consentric'); ?>
			</button>
		<?php endif; ?>
	</div>
	<div id="cscc-scan-result" style="display:none;margin-top:8px"></div>

	<!-- Cookie table -->
	<?php
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- $cscc_table is a trusted prefix + constant
	$cscc_cookies = $wpdb->get_results( "SELECT * FROM {$cscc_table} ORDER BY category, cookie_name" );
	?>

	<?php if (empty($cscc_cookies)): ?>
		<p class="cscc-notice cscc-notice--info">
			<?php esc_html_e('No cookies yet. Add one manually or run the scanner.', 'consentric'); ?>
		</p>
	<?php else: ?>
		<table class="widefat striped cscc-cookie-table">
			<thead>
				<tr>
					<th><?php esc_html_e('Name', 'consentric'); ?></th>
					<th><?php esc_html_e('Category', 'consentric'); ?></th>
					<th><?php esc_html_e('Service', 'consentric'); ?></th>
					<th><?php esc_html_e('Duration', 'consentric'); ?></th>
					<th><?php esc_html_e('Source', 'consentric'); ?></th>
					<th><?php esc_html_e('Actions', 'consentric'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($cscc_cookies as $cscc_cookie): ?>
					<tr>
						<td><code><?php echo esc_html($cscc_cookie->cookie_name); ?></code></td>
						<td>
							<span class="cscc-cat-badge cscc-cat-<?php echo esc_attr($cscc_cookie->category); ?>">
								<?php echo esc_html($cscc_categories[$cscc_cookie->category] ?? $cscc_cookie->category); ?>
							</span>
						</td>
						<td><?php echo esc_html($cscc_cookie->service); ?></td>
						<td><?php echo esc_html($cscc_cookie->duration); ?></td>
						<td><?php echo esc_html($cscc_sources[$cscc_cookie->source] ?? $cscc_cookie->source); ?></td>
						<td class="cscc-row-actions">
							<a href="<?php echo esc_url(wp_nonce_url(add_query_arg(array(
								'action' => 'edit_cookie',
								'cookie_id' => $cscc_cookie->id,
							), $cscc_page_url), 'cscc_edit_cookie')); ?>">
								<?php esc_html_e('Edit', 'consentric'); ?>
							</a>
							&nbsp;|&nbsp;
							<a href="<?php echo esc_url(wp_nonce_url(add_query_arg(array(
								'action' => 'delete_cookie',
								'cookie_id' => $cscc_cookie->id,
							), $cscc_page_url), 'cscc_delete_cookie')); ?>" class="cscc-delete-link"
								data-name="<?php echo esc_attr($cscc_cookie->cookie_name); ?>">
								<?php esc_html_e('Delete', 'consentric'); ?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>

</div>