<?php
if (!defined('ABSPATH')) {
	exit;
}

global $wpdb;
$table = $wpdb->prefix . 'scc_cookies';

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only display hint, action is nonce-verified in class-scc-admin.php
$edit_id = isset( $_GET['action'], $_GET['cookie_id'] ) && 'edit_cookie' === sanitize_key( wp_unslash( $_GET['action'] ) )
	? absint( $_GET['cookie_id'] )
	: 0;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $table is a trusted prefix + constant
$edit_cookie = $edit_id ? $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d", $edit_id ) ) : null;

// Status messages
$messages = array(
	'added'   => __( 'Cookie added.', 'consentric' ),
	'updated' => __( 'Cookie updated.', 'consentric' ),
	'deleted' => __( 'Cookie deleted.', 'consentric' ),
);
// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only display feedback
$scc_msg = isset( $_GET['scc_msg'] ) ? sanitize_key( wp_unslash( $_GET['scc_msg'] ) ) : '';
if ( $scc_msg && isset( $messages[ $scc_msg ] ) ) {
	echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( $messages[ $scc_msg ] ) . '</p></div>';
}

$categories = array(
	'necessary' => __('Necessary', 'consentric'),
	'analytics' => __('Analytics', 'consentric'),
	'marketing' => __('Marketing', 'consentric'),
	'functional' => __('Functional', 'consentric'),
);

$sources = array(
	'manual' => __('Manual', 'consentric'),
	'scan' => __('Scan', 'consentric'),
	'cookiedb' => __('Cookie DB', 'consentric'),
);

$page_url = admin_url('options-general.php?page=scc-cookie-consent&tab=cookies');
?>
<div class="scc-tab-content">

	<!-- Add / Edit form -->
	<?php // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only display toggle ?>
	<div class="scc-cookie-form-wrap" id="scc-cookie-form-wrap" <?php echo ( ! $edit_cookie && empty( $_GET['add'] ) ) ? 'style="display:none"' : ''; ?>>

		<h2 class="scc-section-title">
			<?php echo $edit_cookie ? esc_html__('Edit Cookie', 'consentric') : esc_html__('Add Cookie', 'consentric'); ?>
		</h2>

		<form method="post" action="<?php echo esc_url($page_url); ?>">
			<?php wp_nonce_field('scc_save_cookie', 'scc_cookie_nonce'); ?>
			<input type="hidden" name="cookie_id" value="<?php echo esc_attr($edit_id); ?>">

			<div class="scc-field">
				<label class="scc-field__label" for="scc_cookie_name">
					<?php esc_html_e('Cookie Name', 'consentric'); ?> <span class="required">*</span>
				</label>
				<div class="scc-field__control">
					<input type="text" id="scc_cookie_name" name="cookie_name" class="regular-text" required
						value="<?php echo esc_attr($edit_cookie->cookie_name ?? ''); ?>" placeholder="_ga">
				</div>
			</div>

			<div class="scc-field">
				<label class="scc-field__label" for="scc_category">
					<?php esc_html_e('Category', 'consentric'); ?>
				</label>
				<div class="scc-field__control">
					<select id="scc_category" name="category">
						<?php foreach ($categories as $key => $label): ?>
							<option value="<?php echo esc_attr($key); ?>" <?php selected($edit_cookie->category ?? 'necessary', $key); ?>>
								<?php echo esc_html($label); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="scc-field">
				<label class="scc-field__label" for="scc_service">
					<?php esc_html_e('Service', 'consentric'); ?>
				</label>
				<div class="scc-field__control">
					<input type="text" id="scc_service" name="service" class="regular-text"
						value="<?php echo esc_attr($edit_cookie->service ?? ''); ?>"
						placeholder="<?php esc_attr_e('e.g. Google Analytics', 'consentric'); ?>">
				</div>
			</div>

			<div class="scc-field">
				<label class="scc-field__label" for="scc_duration">
					<?php esc_html_e('Duration', 'consentric'); ?>
				</label>
				<div class="scc-field__control">
					<input type="text" id="scc_duration" name="duration" class="regular-text"
						value="<?php echo esc_attr($edit_cookie->duration ?? ''); ?>"
						placeholder="<?php esc_attr_e('e.g. 2 years', 'consentric'); ?>">
				</div>
			</div>

			<div class="scc-field">
				<label class="scc-field__label" for="scc_description">
					<?php esc_html_e('Description', 'consentric'); ?>
				</label>
				<div class="scc-field__control">
					<textarea id="scc_description" name="description" class="large-text" rows="2"><?php
					echo esc_textarea($edit_cookie->description ?? '');
					?></textarea>
				</div>
			</div>

			<div class="scc-form-actions">
				<?php submit_button($edit_cookie ? __('Update Cookie', 'consentric') : __('Add Cookie', 'consentric'), 'primary', 'submit', false); ?>
				<a href="<?php echo esc_url($page_url); ?>" class="button">
					<?php esc_html_e('Cancel', 'consentric'); ?>
				</a>
			</div>
		</form>
	</div>

	<!-- Cookie DB status -->
	<?php
	$db_count = SCC_Cookie_Scanner::db_count();
	?>
	<p class="scc-cookie-db-status">
		<?php
		printf(
			/* translators: 1: number of cookies, 2: opening <a> tag, 3: closing </a> tag */
			esc_html__( 'Cookie database: %1$d entries loaded from the %2$sOpen Cookie Database%3$s.', 'consentric' ),
			intval( $db_count ),
			'<a href="https://github.com/jkwakman/Open-Cookie-Database" target="_blank" rel="noopener noreferrer">',
			'</a>'
		);
		?>
	</p>

	<!-- Table header -->
	<div class="scc-table-header">
		<h2 class="scc-section-title" style="margin-top:0">
			<?php esc_html_e('Cookie List', 'consentric'); ?>
		</h2>
		<?php if (!$edit_cookie): ?>
			<button type="button" class="button" id="scc-scan-btn">
				<?php esc_html_e('Run Scanner', 'consentric'); ?>
			</button>
			<button type="button" class="button button-primary" id="scc-add-cookie-btn">
				<?php esc_html_e('+ Add Cookie', 'consentric'); ?>
			</button>
		<?php endif; ?>
	</div>
	<div id="scc-scan-result" style="display:none;margin-top:8px"></div>

	<!-- Cookie table -->
	<?php
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $table is a trusted prefix + constant
	$cookies = $wpdb->get_results( "SELECT * FROM {$table} ORDER BY category, cookie_name" );
	?>

	<?php if (empty($cookies)): ?>
		<p class="scc-notice scc-notice--info">
			<?php esc_html_e('No cookies yet. Add one manually or run the scanner.', 'consentric'); ?>
		</p>
	<?php else: ?>
		<table class="widefat striped scc-cookie-table">
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
				<?php foreach ($cookies as $cookie): ?>
					<tr>
						<td><code><?php echo esc_html($cookie->cookie_name); ?></code></td>
						<td>
							<span class="scc-cat-badge scc-cat-<?php echo esc_attr($cookie->category); ?>">
								<?php echo esc_html($categories[$cookie->category] ?? $cookie->category); ?>
							</span>
						</td>
						<td><?php echo esc_html($cookie->service); ?></td>
						<td><?php echo esc_html($cookie->duration); ?></td>
						<td><?php echo esc_html($sources[$cookie->source] ?? $cookie->source); ?></td>
						<td class="scc-row-actions">
							<a href="<?php echo esc_url(add_query_arg(array(
								'action' => 'edit_cookie',
								'cookie_id' => $cookie->id,
							), $page_url)); ?>">
								<?php esc_html_e('Edit', 'consentric'); ?>
							</a>
							&nbsp;|&nbsp;
							<a href="<?php echo esc_url(wp_nonce_url(add_query_arg(array(
								'action' => 'delete_cookie',
								'cookie_id' => $cookie->id,
							), $page_url), 'scc_delete_cookie')); ?>" class="scc-delete-link"
								data-name="<?php echo esc_attr($cookie->cookie_name); ?>">
								<?php esc_html_e('Delete', 'consentric'); ?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>

</div>