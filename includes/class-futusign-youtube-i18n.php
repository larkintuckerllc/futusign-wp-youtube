<?php
/**
 * Define the internationalization functionality
 *
 * @link       https://bitbucket.org/futusign/futusign-wp-youtube
 * @since      0.1.0
 *
 * @package    futusign_youtube
 * @subpackage futusign_youtube/includes
 */
/**
 * Define the internationalization functionality.
 *
 * @since      0.1.0
 * @package    futusign_youtube
 * @subpackage futusign_youtube/includes
 * @author     John Tucker <john@larkintuckerllc.com>
 */
class Futusign_Youtube_i18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.1.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'futusign_youtube',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
