<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.textunited.com/
 * @since             1.0.0
 * @package           Text_United_Translation
 *
 * @wordpress-plugin
 * Plugin Name:       Text United Translation
 * Plugin URI:        https://wordpress.org/plugins/text-united-translation/
 * Description:       Translate your wordpress in over 170 languages within minutes. Fully SEO compatible. Customizable language selector settings. No codes needed.
 * Version:           1.0.36
 * Author:            Text United
 * Author URI:        https://www.textunited.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       text-united-translation
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
define( 'TEXT_UNITED_TRANSLATION_VERSION', '1.0.36' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-text-united-translation-activator.php
 */
function activate_text_united_translation() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-text-united-translation-activator.php';
	Text_United_Translation_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-text-united-translation-deactivator.php
 */
function deactivate_text_united_translation() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-text-united-translation-deactivator.php';
	Text_United_Translation_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_text_united_translation' );
register_deactivation_hook( __FILE__, 'deactivate_text_united_translation' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-text-united-translation.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_text_united_translation() {

	$plugin = new Text_United_Translation();
	$plugin->run();

}
run_text_united_translation();
