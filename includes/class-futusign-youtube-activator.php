<?php

/**
 * Fired during plugin activation
 *
 * @link       https://bitbucket.org/futusign/futusign-wp-youtube
 * @since      0.1.0
 *
 * @package    futusign_youtube
 * @subpackage futusign_youtube/includes
 */
/**
 * Fired during plugin activation.
 *
 * @since      0.1.0
 * @package    futusign_youtube
 * @subpackage futusign_youtube/includes
 * @author     John Tucker <john@larkintuckerllc.com>
 */
class Futusign_Youtube_Activator {
	/**
	 * Fired during plugin activation.
	 *
	 * @since    0.1.0
	 */
	public static function activate() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'common/class-futusign-youtube-common.php';
		$plugin_common = new Futusign_Youtube_Common();
		$youtube_video = $plugin_common->get_youtube_video();
		$youtube_video->register();
		flush_rewrite_rules();
	}
}
