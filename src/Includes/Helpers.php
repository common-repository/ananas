<?php
/**
 * Ananas Analytics | Helpers
 *
 * @since 1.0.0
 *
 * @package    WordPress
 * @subpackage Ananas Analytics
 */

namespace Ananas\Analytics\WP\Includes;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Helpers {

	/**
	 * Get Plain Domain.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public static function get_domain() {
		$site_url = site_url();
		$domain   = preg_replace( '/^http(s?)\:\/\/(www\.)?/i', '', $site_url );

		return $domain;
	}

	/**
	 * Get Analytics URL.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public static function get_analytics_url() {
		$settings         = self::get_settings();
		$domain           = $settings['domain_name'];
		$default_domain   = 'ananas-analytics.cloud';
		$is_outbound_link = apply_filters( 'plausible_analytics_enable_outbound_links', true );
		$file_name        = $is_outbound_link ? 'plausible.outbound-links' : 'plausible';

		// Triggered when self hosted analytics is enabled.
		if (
			! empty( $settings['is_self_hosted_analytics'] ) &&
			'true' === $settings['is_self_hosted_analytics']
		) {
			$default_domain = $settings['self_hosted_domain'];
		}

		$url = "https://{$default_domain}/js/{$file_name}.js";

		// Triggered when custom domain is enabled.
		if (
			! empty( $settings['custom_domain'] ) &&
			'true' === $settings['custom_domain']
		) {
			$custom_domain_prefix = $settings['custom_domain_prefix'];
			$url                  = "https://{$custom_domain_prefix}.{$domain}/js/{$file_name}.js";
		}

		return $url;
	}

	/**
	 * Get Dashboard URL.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public static function get_analytics_dashboard_url() {
		$settings = self::get_settings();
		$domain   = $settings['domain_name'];

		return "https://ananas-analytics.cloud/{$domain}";
	}

	/**
	 * Toggle Switch HTML Markup.
	 *
	 * @param string $name Name of the toggle switch.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public static function display_toggle_switch( $name ) {
		$settings            = Helpers::get_settings();
		$individual_settings = ! empty( $settings[ $name ] ) ? $settings[ $name ] : '';
		?>
		<label class="plausible-analytics-switch">
			<input <?php checked( $individual_settings, 'true' ); ?> class="plausible-analytics-switch-checkbox" name="plausible_analytics_settings[<?php echo $name; ?>]" value="1" type="checkbox" />
			<span class="plausible-analytics-switch-slider"></span>
		</label>
		<?php
	}

	/**
	 * Get Settings.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public static function get_settings() {
		return get_option( 'plausible_analytics_settings', [] );
	}

	/**
	 * Get Data API URL.
	 *
	 * @since  1.2.2
	 * @access public
	 *
	 * @return string
	 */
	public static function get_data_api_url() {
		$settings = self::get_settings();
		$url      = 'https://plausible.io/api/event';

		// Triggered when self hosted analytics is enabled.
		if (
			! empty( $settings['is_self_hosted_analytics'] ) &&
			'true' === $settings['is_self_hosted_analytics']
		) {
			$default_domain = $settings['self_hosted_domain'];
			$url            = "https://{$default_domain}/api/event";
		}

		// Triggered when custom domain is enabled.
		if (
			! empty( $settings['custom_domain'] ) &&
			'true' === $settings['custom_domain']
		) {
			$domain               = $settings['domain_name'];
			$custom_domain_prefix = $settings['custom_domain_prefix'];
			$url                  = "https://{$custom_domain_prefix}.{$domain}/api/event";
		}

		return $url;
	}

	/**
	 * Get Quick Actions.
	 *
	 * @since  1.3.0
	 * @access public
	 *
	 * @return array
	 */
	public static function get_quick_actions() {
		return [
			'view-docs'        => [
				'label' => esc_html__( 'Ananasウェブサイト', 'plausible-analytics' ),
				'url'   => esc_url( 'https://www.ananas-analytics.cloud' ),
			],
			'report-issue'     => [
				'label' => esc_html__( 'ソフトウェアの問題を報告する', 'plausible-analytics' ),
				'url'   => esc_url( 'https://www.ananas-analytics.cloud/contact' ),
			]
			// 'translate-plugin' => [
			// 	'label' => esc_html__( 'Translate Plugin', 'plausible-analytics' ),
			// 	'url'   => esc_url( 'https://translate.wordpress.org/projects/wp-plugins/plausible-analytics/' ),
			// ],
		];
	}

	/**
	 * Render Quick Actions
	 *
	 * @since  1.3.0
	 * @access public
	 *
	 * @return mixed
	 */
	public static function render_quick_actions() {
		ob_start();
		$quick_actions = self::get_quick_actions();
		?>
		<div class="plausible-analytics-quick-actions">
		<?php
		if ( ! empty( $quick_actions ) && count( $quick_actions ) > 0 ) {
			?>
			<div class="plausible-analytics-quick-actions-title">
				<?php esc_html_e( 'リンク', 'plausible-analytics' ); ?>
			</div>
			<ul>
			<?php
			foreach ( $quick_actions as $quick_action ) {
				?>
				<li>
					<a target="_blank" href="<?php echo $quick_action['url']; ?>" title="<?php echo $quick_action['label']; ?>">
						<?php echo $quick_action['label']; ?>
					</a>
				</li>
				<?php
			}
			?>
			</ul>
			<?php
		}
		?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Clean variables using `sanitize_text_field`.
	 * Arrays are cleaned recursively. Non-scalar values are ignored.
	 *
	 * @param string|array $var Sanitize the variable.
	 *
	 * @since  1.3.0
	 * @access public
	 *
	 * @return string|array
	 */
	public static function clean( $var ) {
		if ( is_array( $var ) ) {
			return array_map( [ __CLASS__, __METHOD__ ], $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( wp_unslash( $var ) ) : $var;
		}
	}

	/**
	 * Get user role for the loggedin user.
	 *
	 * @since  1.3.0
	 * @access public
	 *
	 * @return string
	 */
	public static function get_user_role() {
		global $current_user;

		$user_roles = $current_user->roles;
		$user_role  = array_shift( $user_roles );

		return $user_role;
	}
}
