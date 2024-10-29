<?php
/**
 * Plugin Name: Ananas(アナナス)
 * Plugin URI: https://www.ananas-analytics.cloud/
 * Description: サイト閲覧者の個人情報に配慮したアクセス解析ツール
 * Author: AnanasHQ
 * Author URI: https://www.ananas-analytics.cloud
 * Version: 1.0.0
 * Text Domain: ananas-analytics
 * Domain Path: /languages
 *
 */

namespace Ananas\Analytics\WP;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/config/constants.php';

// Automatically loads files used throughout the plugin.
require_once PLAUSIBLE_ANALYTICS_PLUGIN_DIR . 'vendor/autoload.php';

// Initialize the plugin.
$plugin = new Plugin();
$plugin->register();
