<?php
/**
 * Plugin Name: Pixwell Importer
 * Plugin URI: http://themeruby.com/
 * Description: 1-Click to import demo for this theme.
 * Version: 4.5
 * Author: Theme-Ruby
 * Author URI: http://themeruby.com/
 * @package   pixwell-importer
 * @copyright (c) 2019, Theme-Ruby
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PIXWELL_IMPORTER' ) ) {

	define( 'PIXWELL_IMPORT_VERSION', '4.5' );

	class PIXWELL_IMPORTER {

		private static $instance = null;

		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		public function __construct() {
			self::$instance = $this;
			require_once plugin_dir_path( __FILE__ ) . 'importer/radium-importer.php';
			require_once plugin_dir_path( __FILE__ ) . 'init.php';
			require_once plugin_dir_path( __FILE__ ) . 'render.php';
			require_once plugin_dir_path( __FILE__ ) . 'hook.php';


			add_action( 'admin_menu', array( $this, 'add_admin' ), 99 );
			add_action( 'init', array( $this, 'load_textdomain' ) );
			RB_PROCESS_IMPORTER::get_instance();
		}

		/** add admin */
		public function add_admin() {
			$page = add_submenu_page( 'themes.php', "Ruby Import Demos", "Ruby Import Demos", 'switch_themes', 'ruby-importer', array(
				$this,
				'render_panel'
			) );
			add_action( 'load-' . $page, array( $this, 'load' ) );
		}

		public function load() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		}

		/** load scripts */
		public function enqueue() {
			wp_enqueue_script( 'rb-importer-js', plugin_dir_url( __FILE__ ) . 'assets/importer.js', array( 'jquery' ), PIXWELL_IMPORT_VERSION, true );
			wp_enqueue_style( 'rb-importer-style', plugin_dir_url( __FILE__ ) . 'assets/importer.css', PIXWELL_IMPORT_VERSION, true );
			wp_localize_script( 'rb-importer-js', 'RBImporter', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		}

		/** load textdomain */
		public function load_textdomain() {
			$loaded = load_plugin_textdomain( 'pixwell-importer', false, plugin_dir_path( __FILE__ ) . 'languages/' );
			if ( ! $loaded ) {
				$locale = apply_filters( 'plugin_locale', get_locale(), 'pixwell-importer' );
				$mofile = plugin_dir_path( __FILE__ ) . 'languages/pixwell-importer-' . $locale . '.mo';
				load_textdomain( 'pixwell-importer', $mofile );
			}
		}

		/** render panel */
		public function render_panel() {
			RB_RENDER_IMPORTER::get_instance();
		}
	}
}

/** load plugin */
PIXWELL_IMPORTER::get_instance();