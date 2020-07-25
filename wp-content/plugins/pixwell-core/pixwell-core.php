<?php
/**
 * Plugin Name:    Pixwell Core
 * Plugin URI:     https://themeforest.net/user/theme-ruby/
 * Description:    Features for Pixwell, this is required plugin (important) for this theme.
 * Version:        4.5
 * Text Domain:    pixwell-core
 * Domain Path:    /languages/
 * Author:         Theme-Ruby
 * Author URI:     https://themeforest.net/user/theme-ruby/
 * @package        pixwell-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PIXWELL_CORE_VERSION', '4.5' );
define( 'PIXWELL_CORE_URL', plugin_dir_url( __FILE__ ) );
define( 'PIXWELL_CORE_PATH', plugin_dir_path( __FILE__ ) );

if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

add_action( 'init', 'pixwell_core_language' );
add_action( 'init', 'pixwell_register_shortcodes', 99 );
add_action( 'admin_enqueue_scripts', 'pixwell_core_admin_enqueue', 10 );
add_action( 'wp_enqueue_scripts', 'pixwell_core_enqueue', 1 );
add_action( 'widgets_init', 'pixwell_register_widgets' );
add_action( 'wp_footer', 'pixwell_enqueue_shortcode', 1 );

/** load translate */
if ( ! function_exists( 'pixwell_core_language' ) ) {
	function pixwell_core_language() {
		$loaded = load_plugin_textdomain( 'pixwell-core', false, PIXWELL_CORE_PATH . 'languages/' );
		if ( ! $loaded ) {
			$locale = apply_filters( 'plugin_locale', get_locale(), 'pixwell-core' );
			$mofile = PIXWELL_CORE_PATH . 'languages/pixwell-core-' . $locale . '.mo';
			load_textdomain( 'pixwell-core', $mofile );
		}
	}
}

if ( ! is_plugin_active( 'redux-framework/redux-framework.php' ) ) {
	include_once PIXWELL_CORE_PATH . 'lib/redux-framework/framework.php';
}

/** enqueue script */
if ( ! function_exists( 'pixwell_core_enqueue' ) ) {
	function pixwell_core_enqueue() {

		if ( is_admin() ) {
			return false;
		}

		wp_enqueue_style( 'pixwell-core', PIXWELL_CORE_URL . 'assets/core.css', array(), PIXWELL_CORE_VERSION, 'all' );
		wp_register_script( 'imagesloaded', PIXWELL_CORE_URL . 'assets/imagesloaded.min.js', array( 'jquery' ), '4.1.4', true );
		wp_register_script( 'jquery-isotope', PIXWELL_CORE_URL . 'assets/jquery.isotope.min.js', array( 'jquery' ), '3.0.6', true );
		wp_register_script( 'jquery-magnific-popup', PIXWELL_CORE_URL . 'assets/jquery.mp.min.js', array( 'jquery' ), '1.1.0', true );
		wp_register_style( 'pixwell-shortcode', false, array(), PIXWELL_CORE_VERSION, 'all' );
		wp_enqueue_script( 'imagesloaded' );
		wp_enqueue_script( 'jquery-magnific-popup' );
		wp_enqueue_script( 'jquery-isotope' );
		wp_enqueue_script( 'rbcookie', PIXWELL_CORE_URL . 'assets/rbcookie.min.js', array( 'jquery' ), '1.0.3', true );
		wp_enqueue_script( 'pixwell-core-script', PIXWELL_CORE_URL . 'assets/core.js', array(
			'jquery',
			'imagesloaded',
			'jquery-isotope',
			'rbcookie',
			'jquery-magnific-popup'
		), PIXWELL_CORE_VERSION, true );

		wp_localize_script( 'pixwell-core-script', 'pixwellCoreParams', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}
}


if ( ! function_exists( 'pixwell_enqueue_shortcode' ) ) {
	function pixwell_enqueue_shortcode() {
		wp_enqueue_style( 'pixwell-shortcode' );
	}
}


function pixwell_core_admin_enqueue( $hook ) {
	if ( is_admin() && $hook == 'widgets.php' ) {
		wp_enqueue_media();
		wp_enqueue_style( 'wp-color-picker' );
		wp_register_script( 'pixwell-core-admin', PIXWELL_CORE_URL . 'assets/admin.js', array( 'jquery' ), '3.0.6', true );
		wp_enqueue_script( 'pixwell-core-admin' );
	}
}

include_once PIXWELL_CORE_PATH . 'lib/taxonomy-meta.php';
include_once PIXWELL_CORE_PATH . 'composer/setup.php';
include_once PIXWELL_CORE_PATH . 'rb-meta/rb-meta.php';
include_once PIXWELL_CORE_PATH . 'rb-gallery/rb-gallery.php';
include_once PIXWELL_CORE_PATH . 'rb-portfolio/rb-portfolio.php';

include_once PIXWELL_CORE_PATH . 'reaction/reaction.php';

include_once PIXWELL_CORE_PATH . 'includes/redux.php';
include_once PIXWELL_CORE_PATH . 'includes/core.php';
include_once PIXWELL_CORE_PATH . 'includes/actions.php';
include_once PIXWELL_CORE_PATH . 'includes/shortcodes.php';
include_once PIXWELL_CORE_PATH . 'includes/seo.php';
include_once PIXWELL_CORE_PATH . 'includes/advertising.php';
include_once PIXWELL_CORE_PATH . 'includes/social.php';
include_once PIXWELL_CORE_PATH . 'includes/newsletter.php';
include_once PIXWELL_CORE_PATH . 'includes/share.php';
include_once PIXWELL_CORE_PATH . 'includes/bookmark.php';
include_once PIXWELL_CORE_PATH . 'includes/lazyload.php';
include_once PIXWELL_CORE_PATH . 'includes/extras.php';

include_once PIXWELL_CORE_PATH . 'widgets/advertising.php';
include_once PIXWELL_CORE_PATH . 'widgets/fw-instagram.php';
include_once PIXWELL_CORE_PATH . 'widgets/fw-instagram.php';
include_once PIXWELL_CORE_PATH . 'widgets/sb-facebook.php';
include_once PIXWELL_CORE_PATH . 'widgets/sb-flickr.php';
include_once PIXWELL_CORE_PATH . 'widgets/sb-follower.php';
include_once PIXWELL_CORE_PATH . 'widgets/sb-instagram.php';
include_once PIXWELL_CORE_PATH . 'widgets/sb-social-icon.php';
include_once PIXWELL_CORE_PATH . 'widgets/sb-address.php';
include_once PIXWELL_CORE_PATH . 'widgets/sb-tweet.php';
include_once PIXWELL_CORE_PATH . 'widgets/sb-youtube.php';
include_once PIXWELL_CORE_PATH . 'widgets/sb-post.php';
include_once PIXWELL_CORE_PATH . 'widgets/newsletter.php';
include_once PIXWELL_CORE_PATH . 'widgets/banner.php';
include_once PIXWELL_CORE_PATH . 'widgets/header-strip.php';
include_once PIXWELL_CORE_PATH . 'widgets/fw-mc.php';

if ( is_plugin_active( 'elementor/elementor.php' ) ) {
	include_once PIXWELL_CORE_PATH . 'elementor/base.php';
}

/** register widgets */
if ( ! function_exists( 'pixwell_register_widgets' ) ) {
	function pixwell_register_widgets() {
		register_widget( 'pixwell_widget_youtube_subscribe' );
		register_widget( 'pixwell_widget_tweets' );
		register_widget( 'pixwell_widget_facebook' );
		register_widget( 'pixwell_widget_social_icon' );
		register_widget( 'pixwell_widget_sb_instagram' );
		register_widget( 'pixwell_widget_sb_flickr' );
		register_widget( 'pixwell_widget_sb_post' );
		register_widget( 'pixwell_widget_advertising' );
		register_widget( 'pixwell_widget_follower' );
		register_widget( 'pixwell_widget_newsletter' );
		register_widget( 'pixwell_widget_banner' );
		register_widget( 'pixwell_widget_header_strip' );
		register_widget( 'pixwell_widget_address' );
		register_widget( 'pixwell_widget_fw_instagram' );
		register_widget( 'pixwell_widget_mc' );
	}
}