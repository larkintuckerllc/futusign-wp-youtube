<?php
/**
 * Define the youtube video functionality
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
 * Define the youtube video functionality.
*
 * @since      0.1.0
 * @package    futusign_youtube
 * @subpackage futusign_youtube/common
 * @author     John Tucker <john@larkintuckerllc.com>
 */
class Futusign_Youtube_Video {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 */
	public function __construct() {
	}
	/**
	 * Register the video post type.
	 *
	 * @since    0.1.0
	 */
	public function register() {
		$labels = array(
			'name' => __( 'YouTube Videos', 'futusign_youtube' ),
			'singular_name' => __( 'YouTube Video', 'futusign_youtube' ),
			'add_new' => __( 'Add New' , 'futusign_youtube' ),
			'add_new_item' => __( 'Add New YouTube Video' , 'futusign_youtube' ),
			'edit_item' =>  __( 'Edit YouTube Video' , 'futusign_youtube' ),
			'new_item' => __( 'New YouTube Video' , 'futusign_youtube' ),
			'view_item' => __('View YouTube Video', 'futusign_youtube'),
			'search_items' => __('Search YouTube Videos', 'futusign_youtube'),
			'not_found' =>  __('No YouTube Videos found', 'futusign_youtube'),
			'not_found_in_trash' => __('No YouTube Videos found in Trash', 'futusign_youtube'),
		);
		register_post_type( 'futusign_yt_video', // ABBREVIATED FOR WP
			array(
				'labels' => $labels,
				'public' => true,
				'exclude_from_search' => false,
				'publicly_queryable' => false,
				'show_in_nav_menus' => false,
				'has_archive' => false,
				'show_in_rest' => true,
				'rest_base' => 'fs-youtube-videos',
				'menu_icon' => plugins_url( 'img/youtube_video.png', __FILE__ )
			)
		);
	}
	/**
	 * Return the playlists for a youtube video.
	 *
	 * @since    0.1.0
	 * @param    string     $column      The column name.
	 * @param    string     $post_id     The post id.
	 */
	public function manage_posts_custom_column( $column, $post_id ) {
		switch ( $column ) {
			case 'playlists':
				$playlists = get_the_terms( $post_id, 'futusign_playlist' );
				if ($playlists == false) {
					echo '';
				} else {
					echo join( ', ', wp_list_pluck( $playlists, 'name' ) );
				}
				break;
			case 'overrides':
				$overrides = get_the_terms( $post_id, 'futusign_override' );
				if ($overrides == false) {
					echo '';
				} else {
					echo join( ', ', wp_list_pluck( $overrides, 'name' ) );
				}
				break;
		}
	}
	/**
	 * Insert playlists column for an web.
	 *
	 * @since    0.1.0
	 * @param    array     $columns      The columns.
	 */
	public static function manage_posts_columns($columns) {
		$i = array_search( 'title', array_keys( $columns ) ) + 1;
		$columns_before = array_slice( $columns, 0, $i );
		$columns_after = array_slice( $columns, $i );
		$overrides = array();
		if (class_exists( 'Futusign_Override' )) {
			$overrides = array(
				'overrides' => __('On Overrides', 'futusign')
			);
		}
		return array_merge(
			$columns_before,
			array(
				'playlists' => __('On Playlists', 'futusign_web')
			),
			$overrides,
			$columns_after
		);
	}
	/**
	 * Build filter admin selection.
	 *
	 * @since    0.1.0
	 */
	public function restrict_manage_posts() {
		global $typenow;
		$post_type = 'futusign_yt_video';
		$taxonomy_id = 'futusign_playlist';
		if ($typenow != $post_type) {
			return;
		}
		$selected = isset( $_GET[$taxonomy_id] ) ? $_GET[$taxonomy_id] : '';
		$taxonomy = get_taxonomy( $taxonomy_id );
		wp_dropdown_categories( array(
			'show_option_all' =>  __( 'Show All', 'futusign_youtube' ) . ' ' . $taxonomy->label,
			'taxonomy' => $taxonomy_id,
			'name' => $taxonomy_id,
			'orderby' => 'name',
			'selected' => $selected,
			'show_count' => false,
			'hide_empty' => false,
			'hide_if_empty' => true,
		) );
	}
	/**
	 * Build filter admin selection for overide
	 *
	 * @since    0.2.0
	 */
	public function restrict_manage_posts_override() {
		if (! class_exists( 'Futusign_Override' )) {
			return;
		}
		global $typenow;
		$post_type = 'futusign_yt_video';
		$taxonomy_id = 'futusign_override';
		if ($typenow != $post_type) {
			return;
		}
		$selected = isset( $_GET[$taxonomy_id] ) ? $_GET[$taxonomy_id] : '';
		$taxonomy = get_taxonomy( $taxonomy_id );
		wp_dropdown_categories( array(
			'show_option_all' =>  __( 'Show All', 'futusign' ) . ' ' . $taxonomy->label,
			'taxonomy' => $taxonomy_id,
			'name' => $taxonomy_id,
			'orderby' => 'name',
			'selected' => $selected,
			'show_count' => false,
			'hide_empty' => false,
			'hide_if_empty' => true,
		) );
	}
	/**
	 * Convert query playlists variables from ids to slugs
	 *
	 * @since    0.1.0
	 */
	public function parse_query($wp_query) {
		global $pagenow;
		$post_type = 'futusign_yt_video';
		$taxonomy_id = 'futusign_playlist';
		$q_vars = &$wp_query->query_vars;
		if (
			$pagenow != 'edit.php' ||
			!isset( $q_vars['post_type'] ) ||
			$q_vars['post_type'] !== $post_type ||
			!isset( $q_vars[$taxonomy_id] ) ||
			!is_numeric( $q_vars[$taxonomy_id] ) ||
			$q_vars[$taxonomy_id] == 0
		) {
			return;
		}
		$term = get_term_by( 'id', $q_vars[$taxonomy_id], $taxonomy_id );
		$q_vars[$taxonomy_id] = $term->slug;
	}
	/**
	 * Convert query playlists variables from ids to slugs - override
	 *
	 * @since    0.2.0
	 */
	public function parse_query_override($wp_query) {
		if (! class_exists( 'Futusign_Override' )) {
			return;
		}
		global $pagenow;
		$post_type = 'futusign_yt_video';
		$taxonomy_id = 'futusign_override';
		$q_vars = &$wp_query->query_vars;
		if (
			$pagenow != 'edit.php' ||
			!isset( $q_vars['post_type'] ) ||
			$q_vars['post_type'] !== $post_type ||
			!isset( $q_vars[$taxonomy_id] ) ||
			!is_numeric( $q_vars[$taxonomy_id] ) ||
			$q_vars[$taxonomy_id] == 0
		) {
			return;
		}
		$term = get_term_by( 'id', $q_vars[$taxonomy_id], $taxonomy_id );
		$q_vars[$taxonomy_id] = $term->slug;
	}
	/**
	 * Define advanced custom fields for youtube video.
	 *
	 * @since    0.1.0
	 */
	// TODO: DEPRECATED REPLACE WITH ACF_ADD_LOCAL_FIELD_GROUP
	public function register_field_group() {
		if( function_exists( 'register_field_group' ) ) {
			register_field_group(array (
				'id' => 'acf_futusign_youtube_videos', // TODO: DEPRECATED
				'key' => 'acf_futusign_youtube_videos',
				'title' => 'futusign YouTube Videos',
				'fields' => array (
					array (
						'key' => 'field_acf_futusign_youtube_videos_instructions',
						'label' => __('Instructions', 'futusign_youtube'),
						'name' => '',
						'type' => 'message',
						'message' => wp_kses(__( 'In addition to setting the <i>URL</i> and <i>Suggested Quality</i>, add the <i>YouTube Video</i> to one or more <i>Playlists</i> below.', 'futusign_youtube' ), array( 'i' => array() ) ),
					),
					array (
						'key' => 'field_acf_futusign_youtube_videos_url',
						'label' => 'URL',
						'name' => 'url',
						'type' => 'text',
						'instructions' => wp_kses(__( 'The <i>share</i> Uniform Resource Locator (URL) or address of the YouTube video, e.g., <i>https://youtu.be/cmdFne7LnuA</i>.', 'futusign_youtube' ), array( 'i' => array() ) ),
						'required' => 1,
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_acf_futusign_youtube_videos_suggested_quality',
						'label' => 'Suggested Quality',
						'name' => 'suggested_quality',
						'type' => 'select',
						'instructions' => wp_kses(__( 'The highest quality that the <i>YouTube Video</i> will play at; <i>default</i> will auto-select the quality.', 'futusign_youtube' ), array( 'i' => array() ) ),
						'required' => 1,
						'choices' => array (
							'default' => 'default',
							'highres' => 'highres',
							'hd1080' => 'hd1080',
							'hd720' => 'hd720',
							'large' => 'large',
							'medium' => 'medium',
							'small' => 'small',
						),
						'default_value' => 'default',
						'allow_null' => 0,
						'multiple' => 0,
					),
				),
				'location' => array (
					array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'futusign_yt_video',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options' => array (
					'position' => 'normal',
					'layout' => 'no_box',
					'hide_on_screen' => array (
						0 => 'permalink',
						1 => 'the_content',
						2 => 'excerpt',
						3 => 'discussion',
						4 => 'comments',
						5 => 'revisions',
						6 => 'slug',
						7 => 'author',
						8 => 'format',
						9 => 'featured_image',
						10 => 'categories',
						11 => 'tags',
						12 => 'send-trackbacks',
					),
				),
				'menu_order' => 0,
			));
		}
	}
	/**
	 * Validate ACF Field for YouTube Share URL
	 *
	 * @since    0.4.0
	 * @param    Boolean $valid      Current validity
	 * @param    Boolean $value      Current value
	 */
	public function acf_validate_value_field_acf_futusign_youtube_videos_url( $valid, $value ) {
		if ( !$valid ) return false;
		if ( preg_match ( '/^https:\/\/youtu\.be\/.+/' , $value ) === 1 ) return true;
		return 'Invalid YouTube share Uniform Resource Locator (URL)';
  }
}
