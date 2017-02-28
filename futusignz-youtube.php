<?php

/**
 * The plugin bootstrap file
 *
 * @link             https://bitbucket.org/futusign/futusign-wp-youtube
 * @since            0.1.0
 * @package          futusign_youtube
 * @wordpress-plugin
 * Plugin Name:      futusign Youtube
 * Plugin URI:       https://www.futusign.com
 * Description:      Add futusign Youtube Videos feature
 * Version:          0.1.0
 * Author:           John Tucker
 * Author URI:       https://github.com/larkintuckerllc
 * License:          Custom
 * License URI:      https://www.futusign.com/license
 * Text Domain:      futusign-youtube
 * Domain Path:      /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}
function activate_futusign_youtube() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-futusign-youtube-activator.php';
	Futusign_Youtube_Activator::activate();
}
function deactivate_futusign_youtube() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-futusign-youtube-deactivator.php';
	Futusign_Youtube_Deactivator::deactivate();
}
register_activation_hook( __FILE__, 'activate_futusign_youtube' );
register_deactivation_hook( __FILE__, 'deactivate_futusign_youtube' );
require_once plugin_dir_path( __FILE__ ) . 'includes/class-futusign-youtube.php';
/**
 * Begins execution of the plugin.
 *
 * @since    0.1.0
 */
function run_futusign_youtube() {
	$plugin = new Futusign_Youtube();
	$plugin->run();
}
run_futusign_youtube();
