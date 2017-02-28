<?php
/**
 * The file that defines the core plugin class
 *
 * @link       https://bitbucket.org/futusign/futusign-wp-youtube
 * @since      0.1.0
 *
 * @package    futusign_youtube
 * @subpackage futusign_youtube/includes
 */
/**
 * The core plugin class.
 *
 * @since      0.1.0
 * @package    futusign_youtube
 * @subpackage futusign_youtube/includes
 * @author     John Tucker <john@larkintuckerllc.com>
 */
class Futusign_Youtube {
	/**
	 * Static function to determine if dependant plugin(s) are active
	 *
	 * @since    0.1.0
	 * @var      string    $plugin    Indicates which plugin(s) to check for.
	 */
	public static function is_plugin_active( $plugin ) {
		return true;
	}
	/**
	 * Static function to determine if dependant plugin(s) are installed
	 *
	 * @since    0.1.0
	 * @var      string    $plugin    Indicates which plugin(s) to check for.
	 */
	public static function is_plugin_installed( $plugin ) {
		return true;
	}
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;
	/**
	 * The current version of the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      Futusign_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;
	/**
	 * Define the core functionality of the plugin.
	 *
	 * @since    0.1.0
	 */
	public function __construct() {
		$this->plugin_name = 'futusign-youtube';
		$this->version = '0.1.0';
		$this->load_dependencies();
		$this->set_locale();
		if (Futusign_Youtube::is_plugin_active('all')) {
			$this->define_common_hooks();
			if ( is_admin() ) {
				$this->define_admin_hooks();
			} else {
				$this->define_public_hooks();
			}
		} else {
			$this->define_inactive_hooks();
		}
	}
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( __FILE__ ) . 'class-futusign-youtube-loader.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-futusign-youtube-i18n.php';
		if (Futusign_Youtube::is_plugin_active('all')) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'common/class-futusign-youtube-common.php';
			if ( is_admin() ) {
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-futusign-youtube-admin.php';
			} else {
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-futusign-youtube-public.php';
			}
		} else {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inactive/class-futusign-youtube-inactive.php';
		}
		$this->loader = new Futusign_Youtube_Loader();
	}
	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Futusign_Youtube_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}
	/**
	 * Register all of the inactive hooks of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_inactive_hooks() {
		$plugin_inactive = new Futusign_Youtube_Inactive();
	}
	/**
	 * Register all of the common hooks of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_common_hooks() {
		$plugin_common = new Futusign_Youtube_Common();
		// YOUTUBE VIDEO
		$youtube_video = $plugin_common->get_youtube_video();
		$this->loader->add_action('init', $youtube_video, 'register');
		/*
		$this->loader->add_filter('init', $screen, 'register_field_group');
		$this->loader->add_filter('manage_futusign_screen_posts_custom_column', $screen, 'manage_posts_custom_column', 10, 2 );
		$this->loader->add_filter('manage_futusign_screen_posts_columns', $screen, 'manage_posts_columns');
		$this->loader->add_filter('restrict_manage_posts', $screen, 'restrict_manage_posts');
		$this->loader->add_filter('parse_query', $screen, 'parse_query');
		*/
	}
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Futusign_Youtube_Admin();
	}
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Futusign_Youtube_Public();
	}
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.1.0
	 */
	public function run() {
		$this->loader->run();
	}
	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.1.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}
	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     0.1.0
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}
}
