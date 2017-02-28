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
			'name' => __( 'Youtube Videos', 'futusign_youtube' ),
			'singular_name' => __( 'Youtube Video', 'futusign_youtube' ),
			'add_new' => __( 'Add New' , 'futusign_youtube' ),
			'add_new_item' => __( 'Add New Youtube Video' , 'futusign_youtube' ),
			'edit_item' =>  __( 'Edit Youtube Video' , 'futusign_youtube' ),
			'new_item' => __( 'New Youtube Video' , 'futusign_youtube' ),
			'view_item' => __('View Youtube Video', 'futusign_youtube'),
			'search_items' => __('Search Youtube Videos', 'futusign_youtube'),
			'not_found' =>  __('No Youtube Videos found', 'futusign_youtube'),
			'not_found_in_trash' => __('No Youtube Videos found in Trash', 'futusign_youtube'),
		);
		register_post_type( 'futusign_yt_video', // ABBREVIATED FOR WP
			array(
				'labels' => $labels,
				'public' => true,
				'publicly_queryable' => true,
				'rewrite' => array('slug' => 'fs-youtube-videos'),
				'has_archive' => false,
				'show_in_rest' => true,
				'rest_base' => 'fs-youtube-videos',
				'menu_icon' => 'dashicons-format-video',
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
		}
	}
	/**
	 * Insert playlists column for a youtube video.
	 *
	 * @since    0.1.0
	 * @param    array     $columns      The columns.
	 */
	public function manage_posts_columns($columns) {
		$i = array_search( 'title', array_keys( $columns ) ) + 1;
		$columns_before = array_slice( $columns, 0, $i );
		$columns_after = array_slice( $columns, $i );
		return array_merge(
			$columns_before,
			array(
				'playlists' => __('Subscribed Playlists', 'futusign_youtube')
			),
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
				'title' => 'futusign Youtube Videos',
				'fields' => array (
					array (
						'key' => 'field_58b589305dc74',
						'label' => __('Instructions', 'futusign_youtube'),
						'name' => '',
						'type' => 'message',
						'message' => wp_kses(__( 'In addition to setting the <i>URL</i>, add the slide deck to one or more <i>Playlists</i> below.', 'futusign_youtube' ), array( 'i' => array() ) ),
					),
					array (
						'key' => 'field_58b587c5b10eb',
						'label' => 'URL',
						'name' => 'url',
						'type' => 'text',
						'instructions' => esc_html__( 'The Uniform Resource Locator (URL) or address of the YouTube video.', 'futusign_youtube' ),
						'required' => 1,
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
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
}
