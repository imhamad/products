<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RB_GALLERY', 1.0 );
define( 'RB_GALLERY_URL', plugin_dir_url( __FILE__ ) );
define( 'RB_GALLERY_PATH', plugin_dir_path( __FILE__ ) );

if ( ! class_exists( 'ruby_gallery' ) ) {
	class ruby_gallery {

		protected static $instance = null;

		static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		function __construct() {

			do_action( 'rb_gallery_init' );
			include_once RB_GALLERY_PATH . 'core.php';
			include_once RB_GALLERY_PATH . 'shortcodes.php';

			$this->init_components();

			do_action( 'rb_gallery_loaded' );
		}

		private function init_components() {
			add_action( 'init', array( $this, 'register_gallery' ), 1 );
			add_action( 'pre_get_posts', 'rb_gallery_show_all' );

			add_filter( 'template_include', 'rb_gallery_template_redirect', 50 );
			add_shortcode( 'rb_gallery', 'rb_gallery_shortcode' );

			if ( function_exists( 'rb_register_meta_boxes' ) ) {
				add_filter( 'rb_meta_boxes', 'rb_gallery_register_metaboxes' );
			}
		}

		/** register gallery post type */
		public function register_gallery() {

			$settings = get_option( 'pixwell_theme_options' );

			if ( ! empty( $settings['gallery_permalink'] ) ) {
				$permalinks = esc_attr( trim( $settings['gallery_permalink'] ) );
			} else {
				$permalinks = esc_html__( 'gallery', 'pixwell-core' );
			}

			if ( ! empty( $settings['gallery_cat_permalink'] ) ) {
				$cat_permalinks = esc_attr( trim( $settings['gallery_cat_permalink'] ) );
			} else {
				$cat_permalinks = esc_html__( 'gallery-category', 'pixwell-core' );
			}

			$slug = array(
				'slug'       => $permalinks,
				'with_front' => false
			);
			register_post_type( 'rb-gallery', array(
				'labels'          => array(
					'name'               => esc_html__( 'Galleries', 'pixwell-core' ),
					'all_items'          => esc_html__( 'All Galleries', 'pixwell-core' ),
					'add_new'            => esc_html__( 'Add New Gallery', 'pixwell-core' ),
					'menu_name'          => esc_html__( 'Galleries', 'pixwell-core' ),
					'singular_name'      => esc_html__( 'Gallery', 'pixwell-core' ),
					'add_item'           => esc_html__( 'New Gallery', 'pixwell-core' ),
					'add_new_item'       => esc_html__( 'Add New Gallery', 'pixwell-core' ),
					'edit_item'          => esc_html__( 'Edit Gallery', 'pixwell-core' ),
					'not_found'          => esc_html__( 'No gallery item found.', 'pixwell-core' ),
					'not_found_in_trash' => esc_html__( 'No gallery item found in Trash.', 'pixwell-core' ),
					'parent_item_colon'  => ''
				),
				'public'          => true,
				'has_archive'     => true,
				'can_export'      => true,
				'rewrite'         => $slug,
				'capability_type' => 'post',
				'menu_position'   => 4,
				'show_ui'         => true,
				'menu_icon'       => 'dashicons-format-gallery',
				'supports'        => array(
					'author',
					'title',
					'editor',
					'thumbnail',
					'page-attributes'
				),
				'taxonomies'      => array( 'gallery-category' ),
			) );

			$labels = array(
				'name'              => esc_html__( 'Gallery Categories', 'pixwell-core' ),
				'menu_name'         => esc_html__( 'Gallery Categories', 'pixwell-core' ),
				'singular_name'     => esc_html__( 'Category', 'pixwell-core' ),
				'search_items'      => esc_html__( 'Search Categories', 'pixwell-core' ),
				'all_items'         => esc_html__( 'All Categories', 'pixwell-core' ),
				'parent_item'       => esc_html__( 'Parent Category', 'pixwell-core' ),
				'parent_item_colon' => esc_html__( 'Parent Category:', 'pixwell-core' ),
				'edit_item'         => esc_html__( 'Edit Category', 'pixwell-core' ),
				'update_item'       => esc_html__( 'Update Category', 'pixwell-core' ),
				'add_new_item'      => esc_html__( 'Add New Category', 'pixwell-core' ),
				'new_item_name'     => esc_html__( 'New Category Name', 'pixwell-core' )
			);

			register_taxonomy( 'gallery-category', array( 'rb-gallery' ), array(
				'hierarchical'      => true,
				'labels'            => $labels,
				'show_ui'           => true,
				'query_var'         => true,
				'show_admin_column' => true,
				'rewrite'           => array( 'slug' => $cat_permalinks ),
			) );

			flush_rewrite_rules();
		}
	}
}

/** LOAD */
ruby_gallery::get_instance();