<?php
/**
 * The common functionality of the plugin.
 *
 * @link       https://bitbucket.org/futusign/futusign-wp-youtube
 * @since      0.1.0
 *
 * @package    futusign_youtube
 * @subpackage futusign_youtube/common
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * The common functionality of the plugin.
 *
 * @package    futusign_youtube
 * @subpackage futusign_youtube/common
 * @author     John Tucker <john@larkintuckerllc.com>
 */
class Futusign_Youtube_Common {
	/**
	 * The youtube video.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      Futusign_Youtube_Video    $youtube_video    The video.
 	*/
	private $youtube_video;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 */
	public function __construct() {
		$this->load_dependencies();
		$this->youtube_video = new Futusign_Youtube_Video();
	}
	/**
	 * Load the required dependencies for module.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( __FILE__ ) . 'class-futusign-youtube-video.php';
	}
	/**
	 * Retrieve the youtube video.
	 *
	 * @since     0.1.0
	 * @return    Futusign_Youtube_Video    The youtube video functionality.
	 */
	public function get_youtube_video() {
		return $this->youtube_video;
	}
}
