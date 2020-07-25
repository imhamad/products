<?php
/** actions & filters */
add_action( 'after_switch_theme', 'pixwell_activate_redirect' );
add_action( 'init', 'pixwell_single_load_next_endpoint' );
add_action( 'wp_head', 'pixwell_script_settings' );
add_filter( 'comment_form_defaults', 'pixwell_add_comment_placeholder', 10 );
add_filter( 'body_class', 'pixwell_set_body_classes', 20 );
add_filter( 'get_archives_link', 'pixwell_archives_widget_span' );
add_filter( 'wp_list_categories', 'pixwell_cat_widget_span' );
add_filter( 'widget_tag_cloud_args', 'pixwell_widget_tag_cloud_args' );
add_filter( 'the_content', 'pixwell_filter_content_ajax', 999 );
add_filter( 'previous_posts_link_attributes', 'pixwell_simple_pagination_class' );
add_filter( 'next_posts_link_attributes', 'pixwell_simple_pagination_class' );
add_filter( 'rb_add_reaction', 'pixwell_reaction_items' );

/** redirect to activate plugin */
if ( ! function_exists( 'pixwell_activate_redirect' ) ) {
	function pixwell_activate_redirect() {

		global $pagenow;

		if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {

			$first_activate = get_option( 'pixwell_first_activate_theme', '' );
			if ( ! empty( $first_activate ) ) {
				update_option( 'pixwell_first_activate_theme', '1' );
			} else {
				add_option( 'pixwell_first_activate_theme', '1' );
			}
			wp_redirect( admin_url( 'admin.php?page=pixwell-plugins' ) );
			exit;
		}
	}
}

/** site body classes */
if ( ! function_exists( 'pixwell_set_body_classes' ) ) {
	function pixwell_set_body_classes( $classes ) {

		$navbar_sticky        = pixwell_get_option( 'navbar_sticky' );
		$navbar_sticky_smart  = pixwell_get_option( 'navbar_sticky_smart' );
		$site_tooltips        = pixwell_get_option( 'site_tooltips' );
		$site_back_to_top     = pixwell_get_option( 'site_back_to_top' );
		$block_header_style   = pixwell_get_option( 'block_header_style' );
		$widget_header_style  = pixwell_get_option( 'widget_header_style' );
		$entry_meta_style     = pixwell_get_option( 'entry_meta_style' );
		$style_cat_icon       = pixwell_get_option( 'style_cat_icon' );
		$style_element        = pixwell_get_option( 'style_element' );
		$feat_overlay         = pixwell_get_option( 'feat_overlay' );
		$pagination_layout    = pixwell_get_option( 'pagination_layout' );
		$single_post_parallax = pixwell_get_option( 'single_post_parallax' );
		$mobile_logo_pos      = pixwell_get_option( 'mobile_logo_pos' );
		$readmore_mobile      = pixwell_get_option( 'readmore_mobile' );
		$excerpt_mobile       = pixwell_get_option( 'excerpt_mobile' );
		$off_canvas_style     = pixwell_get_option( 'off_canvas_style' );
		$lazy_animate         = pixwell_get_option( 'lazy_animate' );
		$site_layout          = pixwell_get_option( 'site_layout' );

		if ( is_page_template( 'rbc-frontend.php' ) ) {
			$classes   = array_diff( $classes, array( 'page' ) );
			$classes[] = 'hfeed';

			$header_float         = rb_get_meta( 'header_float' );
			$navbar_border        = rb_get_meta( 'navbar_border' );
			$header_banner_border = rb_get_meta( 'header_banner_border' );

			if ( ! empty( $header_float ) && 1 == $header_float ) {
				$classes[] = 'is-header-float';
			}
			if ( ! empty( $navbar_border ) && - 1 == $navbar_border ) {
				$classes[] = 'none-nb';
			}
			if ( ! empty( $header_banner_border ) && 1 == $header_banner_border ) {
				$classes[] = 'is-bb';
			}
		}

		if ( is_single() ) {
			$layout       = pixwell_get_single_layout();
			$header_align = pixwell_get_option( 'single_post_header_align' );

			$classes[] = 'is-single-' . esc_attr( $layout );
			if ( ! empty( $header_align ) ) {
				$classes[] = 'is-single-hc';
			}
		}

		if ( ! empty( $navbar_sticky ) ) {
			$classes[] = 'sticky-nav';
		}

		if ( ! empty( $navbar_sticky_smart ) ) {
			$classes[] = 'smart-sticky';
		}

		if ( ! empty( $off_canvas_style ) && 'light' == $off_canvas_style ) {
			$classes[] = 'off-canvas-light';
		}

		if ( ! empty( $site_tooltips ) ) {
			$classes[] = 'is-tooltips';
			if ( ! empty( $site_tooltips_touch ) ) {
				$classes[] = 'is-tooltips-touch';
			}
		}

		if ( ! empty( $site_back_to_top ) ) {
			$classes[] = 'is-backtop';
		}

		if ( ! empty( $block_header_style ) ) {
			$classes[] = 'block-header-' . esc_attr( $block_header_style );
		}

		if ( ! empty( $widget_header_style ) ) {
			$classes[] = 'w-header-' . esc_attr( $widget_header_style );
		}

		if ( ! empty( $style_cat_icon ) ) {
			$classes[] = 'cat-icon-' . esc_attr( $style_cat_icon );
		}

		if ( ! empty( $entry_meta_style ) && 'border' == $entry_meta_style ) {
			$classes[] = 'is-meta-border';
		}

		if ( ! empty( $style_element ) && 'none' != $style_element ) {
			if ( $style_element == 'round' ) {
				$classes[] = 'ele-round';
			} elseif ( $style_element == 'round_all' ) {
				$classes[] = 'ele-round feat-round';
			}
		}

		if ( ! empty( $single_post_parallax ) ) {
			$classes[] = 'is-parallax-feat';
		}

		if ( ! empty( $feat_overlay ) ) {
			$classes[] = 'is-fmask';
		}

		if ( ! empty( $pagination_layout ) && 'dark' == $pagination_layout ) {
			$classes[] = 'is-dark-pag';
		}

		if ( ! empty( $mobile_logo_pos ) && 'left' == $mobile_logo_pos ) {
			$classes[] = 'mobile-logo-left';
		}

		if ( ! empty( $readmore_mobile ) ) {
			$classes[] = 'mh-p-link';
		}

		if ( ! empty( $excerpt_mobile ) ) {
			$classes[] = 'mh-p-excerpt';
		}

		if ( ! empty( $lazy_animate ) ) {
			$classes[] = 'is-lazyload';
		}

		if ( ! empty( $site_layout ) ) {
			$classes[] = 'boxed';
		}

		return $classes;
	}
}

/**
 * @return string
 * add theme settings
 */
if ( ! function_exists( 'pixwell_script_settings' ) ) {
	function pixwell_script_settings() {

		$settings                    = array();
		$settings['sliderPlay']      = pixwell_get_option( 'slider_play' );
		$settings['sliderSpeed']     = pixwell_get_option( 'slider_speed' );
		$settings['textNext']        = pixwell_get_option( 'slider_next' );
		$settings['textPrev']        = pixwell_get_option( 'slider_prev' );
		$settings['sliderDot']       = pixwell_get_option( 'slider_dot' );
		$settings['sliderAnimation'] = pixwell_get_option( 'slider_animation' );

		if ( ! empty( $settings['sliderPlay'] ) ) {
			$settings['sliderPlay'] = 1;
		} else {
			$settings['sliderPlay'] = 0;
		}

		if ( ! empty( $settings['sliderDot'] ) ) {
			$settings['sliderDot'] = 1;
		} else {
			$settings['sliderDot'] = 0;
		}

		if ( empty( $settings['sliderSpeed'] ) ) {
			$settings['sliderSpeed'] = 5550;
		}

		if ( intval( $settings['sliderSpeed'] ) < 1500 ) {
			$settings['sliderSpeed'] = 1500;
		}

		if ( ! empty( $settings['embedRes'] ) ) {
			$settings['embedRes'] = 1;
		} else {
			$settings['embedRes'] = 0;
		}

		if ( ! empty( $settings['sliderAnimation'] ) ) {
			$settings['sliderAnimation'] = 1;
		} else {
			$settings['sliderAnimation'] = 0;
		}

		wp_localize_script( 'pixwell-global', 'themeSettings', wp_json_encode( $settings ) );
	}
}


/** comment placeholder */
if ( ! function_exists( 'pixwell_add_comment_placeholder' ) ) {
	function pixwell_add_comment_placeholder( $defaults ) {

		if ( ! empty( $defaults['fields']['author'] ) ) {
			$defaults['fields']['author'] = str_replace( '<input', '<input placeholder="' . pixwell_translate( 'your_name' ) . '"', $defaults['fields']['author'] );
		}
		if ( ! empty( $defaults['fields']['email'] ) ) {
			$defaults['fields']['email'] = str_replace( '<input', '<input placeholder="' . pixwell_translate( 'your_email' ) . '"', $defaults['fields']['email'] );
		}

		if ( ! empty( $defaults['fields']['url'] ) ) {
			$defaults['fields']['url'] = str_replace( '<input', '<input placeholder="' . pixwell_translate( 'your_website' ) . '"', $defaults['fields']['url'] );
		}

		if ( ! empty( $defaults['comment_field'] ) ) {
			$defaults['comment_field'] = str_replace( '<textarea', '<textarea placeholder="' . pixwell_translate( 'your_comment' ) . '"', $defaults['comment_field'] );
		}

		return $defaults;
	}
}

/** load next */
if ( ! function_exists( 'pixwell_single_load_next_endpoint' ) ) {
	function pixwell_single_load_next_endpoint() {
		add_rewrite_endpoint( 'rbsnp', EP_PERMALINK );
		flush_rewrite_rules();
	}
}


/**
 * add span tag for default categories widget
 */
if ( ! function_exists( 'pixwell_cat_widget_span' ) ) {
	function pixwell_cat_widget_span( $str ) {
		$pos = strpos( $str, '</a> (' );
		if ( false != $pos ) {
			$str = str_replace( '</a> (', '<span class="count">', $str );
			$str = str_replace( ')', '</span></a>', $str );
		}

		return $str;
	}
};

/**
 * add span tag for default archive widget
 */
if ( ! function_exists( 'pixwell_archives_widget_span' ) ) {
	function pixwell_archives_widget_span( $str ) {
		$pos = strpos( $str, '</a>&nbsp;(' );
		if ( false != $pos ) {
			$str = str_replace( '</a>&nbsp;(', '<span class="count">', $str );
			$str = str_replace( ')', '</span></a>', $str );
		}

		return $str;
	}
}


/**
 * @param $args
 *
 * @return mixed
 * tag filter
 */
if ( ! function_exists( 'pixwell_widget_tag_cloud_args' ) ) {
	function pixwell_widget_tag_cloud_args( $args ) {
		$args['largest']  = 1;
		$args['smallest'] = 1;

		return $args;
	}
}


/**
 * add class to simple pagination
 */
if ( ! function_exists( 'pixwell_simple_pagination_class' ) ) {
	function pixwell_simple_pagination_class() {
		return 'class="page-numbers"';
	}
}


/** reaction */
if ( ! function_exists( 'pixwell_reaction_items' ) ) {
	function pixwell_reaction_items( $defaults ) {

		$love   = pixwell_get_option( 'reaction_love' );
		$sad    = pixwell_get_option( 'reaction_sad' );
		$happy  = pixwell_get_option( 'reaction_happy' );
		$sleepy = pixwell_get_option( 'reaction_sleepy' );
		$angry  = pixwell_get_option( 'reaction_angry' );
		$dead   = pixwell_get_option( 'reaction_dead' );
		$wink   = pixwell_get_option( 'reaction_wink' );

		if ( empty( $love ) ) {
			unset ( $defaults['love'] );
		}
		if ( empty( $sad ) ) {
			unset ( $defaults['sad'] );
		}
		if ( empty( $happy ) ) {
			unset ( $defaults['happy'] );
		}
		if ( empty( $sleepy ) ) {
			unset ( $defaults['sleepy'] );
		}
		if ( empty( $angry ) ) {
			unset ( $defaults['angry'] );
		}
		if ( empty( $dead ) ) {
			unset ( $defaults['dead'] );
		}
		if ( empty( $wink ) ) {
			unset ( $defaults['wink'] );
		}

		return $defaults;
	}
}


/** load default fonts */
if ( ! function_exists( 'pixwell_font_urls' ) ) {
	function pixwell_font_urls() {

		$font_body      = pixwell_get_option( 'font_body' );
		$font_h1        = pixwell_get_option( 'font_h1' );
		$font_h2        = pixwell_get_option( 'font_h2' );
		$font_h3        = pixwell_get_option( 'font_h3' );
		$font_h4        = pixwell_get_option( 'font_h4' );
		$font_h5        = pixwell_get_option( 'font_h5' );
		$font_h6        = pixwell_get_option( 'font_h6' );
		$font_cat_icon  = pixwell_get_option( 'font_cat_icon' );
		$font_post_meta = pixwell_get_option( 'font_post_meta' );
		$font_button    = pixwell_get_option( 'font_button' );
		$font_input     = pixwell_get_option( 'font_input' );

		$fonts_url       = '';
		$font_quicksand  = _x( 'on', 'Quicksand font: on or off', 'pixwell' );
		$font_montserrat = _x( 'on', 'Montserrat font: on or off', 'pixwell' );
		$font_poppins    = _x( 'on', 'Poppins font: on or off', 'pixwell' );

		if ( 'off' !== $font_quicksand || 'off' !== $font_montserrat ) {

			$font_families = array();

			if ( ! class_exists( 'ReduxFramework' ) || empty( $font_body['font-family'] ) ) {
				if ( 'off' !== $font_poppins ) {
					$font_families[] = 'Poppins:400,400i,700,700i';
				}
			}

			if ( ! class_exists( 'ReduxFramework' ) || empty( $font_h1['font-family'] ) || empty( $font_h2['font-family'] ) || empty( $font_h3['font-family'] ) || empty( $font_h4['font-family'] ) || empty( $font_h5['font-family'] ) || empty( $font_h6['font-family'] ) ) {
				if ( 'off' !== $font_quicksand ) {
					$font_families[] = 'Quicksand:300,400,500,600,700';
				}
			}

			if ( ! class_exists( 'ReduxFramework' ) || empty( $font_cat_icon['font-family'] ) || empty( $font_post_meta['font-family'] ) || empty( $font_button['font-family'] ) || empty( $font_input['font-family'] ) ) {
				if ( 'off' !== $font_montserrat ) {
					$font_families[] = 'Montserrat:400,500,600,700';
				}
			}

			if ( count( $font_families ) < 1 ) {
				return false;
			}

			$params    = array(
				'family'  => urlencode( implode( '|', $font_families ) ),
				'subset'  => urlencode( 'latin,latin-ext' ),
				'display' => 'swap'
			);
			$fonts_url = add_query_arg( $params, 'https://fonts.googleapis.com/css' );
		}

		return $fonts_url;
	}
}