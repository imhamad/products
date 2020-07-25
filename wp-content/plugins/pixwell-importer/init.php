<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'RB_PROCESS_IMPORTER' ) ) {
	class RB_PROCESS_IMPORTER {

		private static $instance = null;

		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		public function __construct() {
			self::$instance = $this;
			add_action( 'wp_ajax_rb_importer', array( $this, 'ajax_importer' ) );
			add_action( 'wp_ajax_rb_install_package', array( $this, 'ajax_install_package' ) );
			add_action( 'wp_ajax_rb_check_progress', array( $this, 'get_progress' ) );
		}

		/** install package */
		public function ajax_install_package() {

			if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'ruby-nonce' ) || ! isset ( $_REQUEST['slug'] ) || ! isset ( $_REQUEST['package'] ) || ! current_user_can( 'install_plugins' ) ) {
				die( 0 );
			}

			$package = base64_decode( $_REQUEST['package'] );
			if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			}

			$url = add_query_arg(
				array(
					'action' => 'upload-plugin',
					'plugin' => urlencode( sanitize_text_field( $_REQUEST['slug'] ) ),
				),
				'update.php'
			);

			$skin_args = array(
				'type'  => 'upload',
				'title' => '',
				'url'   => esc_url_raw( $url ),
			);

			$skin     = new Plugin_Installer_Skin( $skin_args );
			$upgrader = new Plugin_Upgrader( $skin );
			$upgrader->install( $package );

			die();
		}

		/** ajax importer */
		public function ajax_importer() {

			if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'ruby-nonce' ) || ! isset ( $_REQUEST['directory'] ) ) {
				die( 0 );
			}

			delete_option( 'rb_import_progress' );
			$this->import_progress_init();

			$directory      = sanitize_text_field( $_REQUEST['directory'] );
			$import_all     = sanitize_text_field( $_REQUEST['import_all'] );
			$import_content = sanitize_text_field( $_REQUEST['import_content'] );
			$import_pages   = sanitize_text_field( $_REQUEST['import_pages'] );
			$import_opts    = sanitize_text_field( $_REQUEST['import_opts'] );
			$import_widgets = sanitize_text_field( $_REQUEST['import_widgets'] );

			$demos_path = apply_filters( 'rb_importer_demos_path', trailingslashit( plugin_dir_path( __FILE__ ) . 'demos' ) );
			$path       = trailingslashit( $demos_path . $directory );
			$data       = array(
				'path'              => $path,
				'directory'         => $directory,
				'content'           => $path . apply_filters( 'rb_importer_content_file_name', 'content.xml' ),
				'pages'             => $path . apply_filters( 'rb_importer_pages_file_name', 'pages.xml' ),
				'theme_options'     => $path . apply_filters( 'rb_importer_tops_file_name', 'theme-options.json' ),
				'widgets'           => $path . apply_filters( 'rb_importer_widgets_file_name', 'widgets.txt' ),
				'theme_option_name' => apply_filters( 'rb_importer_theme_option_name', 'ruby_theme_options' ),
				'import_all'        => $import_all,
				'import_content'    => $import_content,
				'import_pages'      => $import_pages,
				'import_opts'       => $import_opts,
				'import_widgets'    => $import_widgets,
			);

			/** process */
			new RB_INIT_IMPORTER( $data );
		}


		/** import progress */
		public function import_progress_init() {
			add_action( 'wp_import_posts', array( $this, 'import_progress_setup' ) );
			add_action( 'add_attachment', array( $this, 'update_progress' ) );
			add_action( 'edit_attachment', array( $this, 'update_progress' ) );
			add_action( 'wp_insert_post', array( $this, 'update_progress' ) );
			add_filter( 'wp_import_post_data_raw', array( $this, 'check_post' ) );
		}

		/** import progress */
		public function import_progress_setup( $posts ) {
			$progress_array = array(
				'total_post'     => count( $posts ),
				'imported_count' => 0,
				'remaining'      => count( $posts )
			);
			update_option( 'rb_import_progress', $progress_array );

			return $posts;
		}

		/** update progress */
		public function update_progress() {
			$post_count = get_option( 'rb_import_progress' );
			if ( is_array( $post_count ) ) {
				if ( $post_count['remaining'] > 0 ) {
					$post_count['remaining']      = $post_count['remaining'] - 1;
					$post_count['imported_count'] = $post_count['imported_count'] + 1;
					update_option( 'rb_import_progress', $post_count );
				} else {
					$post_count['remaining']      = 0;
					$post_count['imported_count'] = $post_count['total_post'];
					update_option( 'rb_import_progress', $post_count );
				}
			}
		}

		/** check posts */
		public function check_post( $post ) {

			if ( ! post_type_exists( $post['post_type'] ) ) {
				$this->update_progress();

				return $post;
			}

			if ( $post['status'] == 'auto-draft' ) {
				$this->update_progress();

				return $post;
			}

			if ( 'nav_menu_item' == $post['post_type'] ) {
				$this->update_progress();

				return $post;
			}

			$post_exists = post_exists( $post['post_title'], '', $post['post_date'] );
			if ( $post_exists && get_post_type( $post_exists ) == $post['post_type'] ) {
				$this->update_progress();

				return $post;
			}

			return $post;
		}

		/** get import progress data */
		public function get_progress() {
			$progress = get_option( 'rb_import_progress' );
			wp_send_json( $progress );
			die();
		}
	}
}

/** init import */
if ( ! class_exists( 'RB_INIT_IMPORTER' ) ) {
	class RB_INIT_IMPORTER extends RB_Radium_Theme_Importer {
		private static $instance;

		public $main_path;
		public $content_demo;
		public $widgets;
		public $theme_options_file;
		public $theme_option_name;
		public $content_pages;
		public $directory;
		public $selection_data;
		public $widget_import_results;

		public function __construct( $data ) {
			self::$instance           = $this;
			$this->main_path          = $data['path'];
			$this->content_demo       = $data['content'];
			$this->content_pages      = $data['pages'];
			$this->widgets            = $data['widgets'];
			$this->theme_options_file = $data['theme_options'];
			$this->directory          = $data['directory'];
			$this->theme_option_name  = $data['theme_option_name'];
			$this->selection_data     = $data;

			parent::__construct();
		}
	}
}
