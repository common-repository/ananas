<?php
/**
 * Ananas Analytics | Filters.
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

class Filters {
	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'script_loader_tag', [ $this, 'add_plausible_attributes' ], 10, 2 );
	}

	/**
	 * Add Ananas Analytics attributes.
	 *
	 * @param string $tag    Script tag.
	 * @param string $handle Script handle.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return mixed
	 */
	public function add_plausible_attributes( $tag, $handle ) {
		// Bailout, if not `Ananas Analytics` script.
		if ( 'plausible-analytics' !== $handle ) {
			return $tag;
		}

		$settings    = Helpers::get_settings();
		$api_url     = Helpers::get_data_api_url();
		$domain_name = $settings['domain_name'];

		return str_replace( ' src', " async defer data-domain='{$domain_name}' data-api='{$api_url}' src", $tag );
	}
}
