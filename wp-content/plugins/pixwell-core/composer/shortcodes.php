<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'rbc_sec_fullwidth', 'rbc_render_sec_fullwidth' );
add_shortcode( 'rbc_sec_content', 'rbc_render_sec_content' );

/** section fullwidth */
if ( ! function_exists( 'rbc_render_sec_fullwidth' ) ) {
	function rbc_render_sec_fullwidth( $attrs, $content ) {

		$str        = '';
		$class_name = array();
		$settings   = shortcode_atts( array(
			'uuid'   => '',
			'layout' => 'wrapper',
		), $attrs );

		if ( empty( $settings['uuid'] ) ) {
			return false;
		}

		$class_name[] = 'rbc-row rbc-fw-section clearfix';

		switch ( $settings['layout'] ) {
			case 'full' :
				$class_name[] = 'is-fullwidth';
				break;
			case 'stretched' :
				$class_name[] = 'is-fullwide';
				break;
			default :
				$class_name[] = 'is-wrapper';
		}

		$class_name = implode( ' ', $class_name );
		$str .= '<div id="' . $settings['uuid'] . '" class="' . esc_attr( $class_name ) . '">';
		$str .= '<div class="rbc-container rb-p20-gutter">';
		$str .= '<div class="rbc-content">';
		$str .= apply_filters( 'the_content', $content );
		$str .= '</div>';
		$str .= '</div>';
		$str .= '</div>';

		return $str;
	}
}

/** section content */
if ( ! function_exists( 'rbc_render_sec_content' ) ) {
	function rbc_render_sec_content( $attrs, $content ) {
		$str      = '';
		$settings = shortcode_atts( array(
			'uuid'           => '',
			'sidebar_name'   => '',
			'sidebar_pos'    => 'right',
			'sidebar_sticky' => ''
		), $attrs );

		if ( empty( $settings['uuid'] ) ) {
			return false;
		}

		if ( 'default' == $settings['sidebar_sticky'] && function_exists( 'pixwell_get_option' ) ) {
			$sidebar_sticky = pixwell_get_option( 'sidebar_sticky' );
			if ( ! empty( $sidebar_sticky ) ) {
				$settings['sidebar_sticky'] = 'sticky';
			}
		}

		$class_name = 'rbc-sidebar';
		if ( 'sticky' == $settings['sidebar_sticky'] ) {
			$class_name = 'rbc-sidebar sidebar-sticky';
		}

		$str .= '<div id="' . $settings['uuid'] . '" class="rbc-row rbc-content-section is-wrapper is-sidebar-' . esc_attr( $settings['sidebar_pos'] ) . '">';
		$str .= '<div class="rbc-container rb-p20-gutter">';
		$str .= '<div class="rbc-wrap">';
		$str .= '<div class="rbc-content">';
		$str .= apply_filters( 'the_content', $content );
		$str .= '</div>';
		$str .= '<aside class="' . esc_attr( $class_name ) . '">';
		$str .= '<div class="sidebar-inner">';
		ob_start();
		if ( is_dynamic_sidebar( $settings['sidebar_name'] ) ) {
			dynamic_sidebar( $settings['sidebar_name'] );
		}
		$str .= ob_get_clean();
		$str .= '</div>';
		$str .= '</aside>';
		$str .= '</div>';
		$str .= '</div>';
		$str .= '</div>';

		return $str;
	}
}