<?php
/**
 * Plugin Name: EHx Donate
 * Plugin URI: https://wordpress.org/plugins/ehx-donate
 * Description: A feature-rich donation management plugin with AJAX forms, multilingual support, and seamless WordPress integration.
 * Version: 1.1.4
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: EH Studio
 * Author URI: https://eh.studio
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ehx-donate
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('EHXDonate_VERSION', '1.0.0');
define('EHXDonate_FILE', __FILE__);
define('EHXDonate_PATH', plugin_dir_path(__FILE__));
define('EHXDonate_URL', plugin_dir_url(__FILE__));
define('EHXDonate_BASENAME', plugin_basename(__FILE__));

// Autoload dependencies
if (file_exists(EHXDonate_PATH . 'vendor/autoload.php')) {
    require_once EHXDonate_PATH . 'vendor/autoload.php';
}

// Bootstrap the plugin
use EHXDonate\Core\Plugin;

/**
 * Initialize the plugin
 */
function exh_donate_init() {
    return Plugin::getInstance();
}

// Initialize the plugin
$GLOBALS['exh_donate'] = exh_donate_init();

// Register WP-CLI commands
// if (defined('WP_CLI') && WP_CLI) {
//     require_once EHXDonate_PATH . 'includes/cli/RenameCommand.php';
//     \WP_CLI::add_command('EHXDonate', 'EHXDonate\\CLI\\RenameCommand');
// }

// Activation and deactivation hooks
register_activation_hook(__FILE__, [Plugin::class, 'activate']);
register_deactivation_hook(__FILE__, [Plugin::class, 'deactivate']);
