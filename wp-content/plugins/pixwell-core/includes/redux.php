<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * remove demo links
 */
if ( ! function_exists( 'pixwell_redux_remove_demo_link' ) && class_exists( 'ReduxFrameworkPlugin' ) ) {
	function pixwell_redux_remove_demo_link() {
		remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks' ), null, 2 );
		remove_action( 'admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );
	}

	add_action( 'redux/loaded', 'pixwell_redux_remove_demo_link', 1 );
}

/**
 * @param $option_name
 *
 * @return bool
 * get theme option
 */
if ( ! function_exists( 'pixwell_get_option' ) ) {
	function pixwell_get_option( $option_name ) {

		$settings = get_option( 'pixwell_theme_options' );

		if ( ! empty( $settings[ $option_name ] ) ) {
			return $settings[ $option_name ];
		}

		return false;
	}
}
