<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://bitbucket.org/futusign/futusign-wp-youtube
 * @since      0.1.0
 *
 * @package    futusign_youtube
 * @subpackage futusign_youtube/admin
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * The admin-specific functionality of the plugin.
 *
 * @package    futusign_youtube
 * @subpackage futusign_youtube/admin
 * @author     John Tucker <john@larkintuckerllc.com>
 */
class Futusign_Youtube_Admin {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 */
	public function __construct() {
	}
	/**
	 * filter link builder
	 *
	 * @since    0.3.0
	 * @param    array      $query     query
	 * @return   array      filtered query
	 */
	public function wp_link_query_args( $query ) {
		$cpt_to_remove = 'futusign_yt_video';
		$key = array_search( $cpt_to_remove, $query['post_type'] );
		if( $key ) unset( $query['post_type'][$key] );
		return $query;
	}
}
