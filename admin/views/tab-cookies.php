<?php
if (!defined('ABSPATH')) {
	exit;
}

global $wpdb;
$table = $wpdb->prefix . 'scc_cookies';

// Editing an existing cookie?
$edit_id = isset($_GET['action'], $_GET['cookie_id']) && $_GET['action'] === 'edit_cookie'
	? absint($_GET['cookie_id'])
	: 0;
$edit_cookie = $edit_id ? $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table} WHERE id = %d", $edit_id)) : null;

// Status messages
$messages = array(
	'added' => __('Cookie added.', 'simple-cookie-consent'),
	'updated' => __('Cookie updated.', 'simple-cookie-consent'),
	'deleted' => __('Cookie deleted.', 'simple-cookie-consent'),
);
if (!empty($_GET['scc_msg']) && isset($messages[$_GET['scc_msg']])) {
	echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($messages[$_GET['scc_msg']]) . '</p></div>';
}

$categories = array(
	'necessary' => __('Necessary', 'simple-cookie-consent'),
	'analytics' => __('Analytics', 'simple-cookie-consent'),
	'marketing' => __('Marketing', 'simple-cookie-consent'),
	'functional' => __('Functional', 'simple-cookie-consent'),
);

$sources = array(
	'manual' => __('Manual', 'simple-cookie-consent'),
	'scan' => __('Scan', 'simple-cookie-consent'),
	'cookiedb' => __('Cookie DB', 'simple-cookie-consent'),
);

$page_url = admin_url('options-general.php?page=scc-cookie-consent&tab=cookies');
?>
<div class="scc-tab-content">

	<!-- Add / Edit form -->
	<div class="scc-cookie-form-wrap" id="scc-cookie-form-wrap" <?php echo (!$edit_cookie && empty($_GET['add'])) ? 'style="display:none"' : ''; ?>>

		<h2 class="scc-section-title">
			<?php echo $edit_cookie ? esc_html__('Edit Cookie', 'simple-cookie-consent') : esc_html__('Add Cookie', 'simple-cookie-consent'); ?>
		</h2>

		<form method="post" action="<?php echo esc_url($page_url); ?>">
			<?php wp_nonce_field('scc_save_cookie', 'scc_cookie_nonce'); ?>
			<input type="hidden" name="cookie_id" value="<?php echo esc_attr($edit_id); ?>">

			<div class="scc-field">
				<label class="scc-field__label" for="scc_cookie_name">
					<?php esc_html_e('Cookie Name', 'simple-cookie-consent'); ?> <span class="required">*</span>
				</label>
				<div class="scc-field__control">
					<input type="text" id="scc_cookie_name" name="cookie_name" class="regular-text" required
						value="<?php echo esc_attr($edit_cookie->cookie_name ?? ''); ?>" placeholder="_ga">
				</div>
			</div>

			<div class="scc-field">
				<label class="scc-field__label" for="scc_category">
					<?php esc_html_e('Category', 'simple-cookie-consent'); ?>
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
					<?php esc_html_e('Service', 'simple-cookie-consent'); ?>
				</label>
				<div class="scc-field__control">
					<input type="text" id="scc_service" name="service" class="regular-text"
						value="<?php echo esc_attr($edit_cookie->service ?? ''); ?>"
						placeholder="<?php esc_attr_e('e.g. Google Analytics', 'simple-cookie-consent'); ?>">
				</div>
			</div>

			<div class="scc-field">
				<label class="scc-field__label" for="scc_duration">
					<?php esc_html_e('Duration', 'simple-cookie-consent'); ?>
				</label>
				<div class="scc-field__control">
					<input type="text" id="scc_duration" name="duration" class="regular-text"
						value="<?php echo esc_attr($edit_cookie->duration ?? ''); ?>"
						placeholder="<?php esc_attr_e('e.g. 2 years', 'simple-cookie-consent'); ?>">
				</div>
			</div>

			<div class="scc-field">
				<label class="scc-field__label" for="scc_description">
					<?php esc_html_e('Description', 'simple-cookie-consent'); ?>
				</label>
				<div class="scc-field__control">
					<textarea id="scc_description" name="description" class="large-text" rows="2"><?php
					echo esc_textarea($edit_cookie->description ?? '');
					?></textarea>
				</div>
			</div>

			<div class="scc-form-actions">
				<?php submit_button($edit_cookie ? __('Update Cookie', 'simple-cookie-consent') : __('Add Cookie', 'simple-cookie-consent'), 'primary', 'submit', false); ?>
				<a href="<?php echo esc_url($page_url); ?>" class="button">
					<?php esc_html_e('Cancel', 'simple-cookie-consent'); ?>
				</a>
			</div>
		</form>
	</div>

	<!-- Table header -->
	<div class="scc-table-header">
		<h2 class="scc-section-title" style="margin-top:0">
			<?php esc_html_e('Cookie List', 'simple-cookie-consent'); ?>
		</h2>
		<?php if (!$edit_cookie): ?>
			<button type="button" class="button button-primary" id="scc-add-cookie-btn">
				<?php esc_html_e('+ Add Cookie', 'simple-cookie-consent'); ?>
			</button>
		<?php endif; ?>
	</div>

	<!-- Cookie table -->
	<?php $cookies = $wpdb->get_results("SELECT * FROM {$table} ORDER BY category, cookie_name"); ?>

	<?php if (empty($cookies)): ?>
		<p class="scc-notice scc-notice--info">
			<?php esc_html_e('No cookies yet. Add one manually or run the scanner.', 'simple-cookie-consent'); ?>
		</p>
	<?php else: ?>
		<table class="widefat striped scc-cookie-table">
			<thead>
				<tr>
					<th><?php esc_html_e('Name', 'simple-cookie-consent'); ?></th>
					<th><?php esc_html_e('Category', 'simple-cookie-consent'); ?></th>
					<th><?php esc_html_e('Service', 'simple-cookie-consent'); ?></th>
					<th><?php esc_html_e('Duration', 'simple-cookie-consent'); ?></th>
					<th><?php esc_html_e('Source', 'simple-cookie-consent'); ?></th>
					<th><?php esc_html_e('Actions', 'simple-cookie-consent'); ?></th>
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
								<?php esc_html_e('Edit', 'simple-cookie-consent'); ?>
							</a>
							&nbsp;|&nbsp;
							<a href="<?php echo esc_url(wp_nonce_url(add_query_arg(array(
								'action' => 'delete_cookie',
								'cookie_id' => $cookie->id,
							), $page_url), 'scc_delete_cookie')); ?>" class="scc-delete-link"
								data-name="<?php echo esc_attr($cookie->cookie_name); ?>">
								<?php esc_html_e('Delete', 'simple-cookie-consent'); ?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>

</div>