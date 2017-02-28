<?php
/**
 * The inactive functionality of the plugin.
 *
 * @link       https://bitbucket.org/futusign/futusign-wp-youtube
 * @since      0.1.0
 *
 * @package    futusign_youtube
 * @subpackage futusign_youtube/inactive
 */
/**
 * The inactive functionality of the plugin.
 *
 * @package    futusign_youtube
 * @subpackage futusign_youtube/inactive
 * @author     John Tucker <john@larkintuckerllc.com>
 */
class Futusign_Youtube_Inactive {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 */
	public function __construct() {
	}
	/**
	 * Display missing plugin dependency notices.
	 *
	 * @since    0.3.0
	 */
	public function missing_plugins_notice() {
		if ( ! Futusign_Youtube::is_plugin_active( 'futusign' ) ) {
			include plugin_dir_path( __FILE__ ) . 'partials/futusign-youtube-missing-futusign.php';
		}
	}
}
