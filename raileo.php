<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              raileo
 * @since             1.0.0
 * @package           Raileo
 *
 * @wordpress-plugin
 * Plugin Name:       Raileo
 * Plugin URI:        https://raileo.com
 * Description:       Raileo monitroing for wordpress helps you to monitor your website's downtime, ssl expiry, performance, seo and a lot more. Raileo can send you email and slack notifications about any monitoring incidents.
 * Version:           1.0.3
 * Author:            Justin George
 * Author URI:        https://twitter.com/Raileo1
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       raileo
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'RAILEO_VERSION', '1.0.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-raileo-activator.php
 */
function activate_raileo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-raileo-activator.php';
	Raileo_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-raileo-deactivator.php
 */
function deactivate_raileo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-raileo-deactivator.php';
	Raileo_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_raileo' );
register_deactivation_hook( __FILE__, 'deactivate_raileo' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-raileo.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_raileo() {

	$plugin = new Raileo();
	$plugin->run();

}
run_raileo();
