<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** config import for Pixwell */
add_filter( 'rb_importer_demo_plugins', 'pixwell_importer_plugins' );
add_filter( 'rb_importer_theme_option_name', 'pixwell_theme_option_name' );
add_filter( 'rb_importer_demo_name', 'pixwell_importer_demo_name' );
add_action( 'rb_importer_before_content', 'pixwell_importer_duplicate_menus' );
add_action( 'rb_importer_before_widgets', 'pixwell_importer_init_widgets' );
add_action( 'rb_importer_content_settings', 'pixwell_importer_setup_content', 10, 1 );
add_action( 'rb_importer_content_settings', 'pixwell_importer_setup_category', 20, 2 );
add_action( 'rb_importer_after_theme_options', 'pixwell_importer_remove_cache' );
add_action( 'rb_importer_header', 'pixwell_importer_header' );

/** setup plugins */
if ( ! function_exists( 'pixwell_importer_plugins' ) ) {
	function pixwell_importer_plugins( $directory ) {
		switch ( $directory ) {
			case '11' :
				return array(
					array(
						'name' => esc_html__( 'Cookied', 'pixwell-importer' ),
						'slug' => 'cooked',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Breadcrumb NavXT', 'pixwell-importer' ),
						'slug' => 'breadcrumb-navxt',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Post Views Counter', 'pixwell-importer' ),
						'slug' => 'post-views-counter',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'oAuth Twitter Feed for Developers', 'pixwell-importer' ),
						'slug' => 'oauth-twitter-feed-for-developers',
						'file' => 'twitter-feed-for-developers',
						'info' => esc_html__( 'Optional', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Contact Form 7', 'pixwell-importer' ),
						'slug' => 'contact-form-7',
						'file' => 'wp-contact-form-7',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					)
				);
			case '12' :
				return array(
					array(
						'name' => esc_html__( 'WooCommerce', 'pixwell-importer' ),
						'slug' => 'woocommerce',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'YITH WooCommerce Wishlist', 'pixwell-importer' ),
						'slug' => 'yith-woocommerce-wishlist',
						'file' => 'init',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Breadcrumb NavXT', 'pixwell-importer' ),
						'slug' => 'breadcrumb-navxt',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Post Views Counter', 'pixwell-importer' ),
						'slug' => 'post-views-counter',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'oAuth Twitter Feed for Developers', 'pixwell-importer' ),
						'slug' => 'oauth-twitter-feed-for-developers',
						'file' => 'twitter-feed-for-developers',
						'info' => esc_html__( 'Optional', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Contact Form 7', 'pixwell-importer' ),
						'slug' => 'contact-form-7',
						'file' => 'wp-contact-form-7',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					)
				);
			case '13' :
				return array(
					array(
						'name' => esc_html__( 'Breadcrumb NavXT', 'pixwell-importer' ),
						'slug' => 'breadcrumb-navxt',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Post Views Counter', 'pixwell-importer' ),
						'slug' => 'post-views-counter',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Contact Form 7', 'pixwell-importer' ),
						'slug' => 'contact-form-7',
						'file' => 'wp-contact-form-7',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					)
				);
			case '14' :
				return array(
					array(
						'name' => esc_html__( 'Breadcrumb NavXT', 'pixwell-importer' ),
						'slug' => 'breadcrumb-navxt',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Post Views Counter', 'pixwell-importer' ),
						'slug' => 'post-views-counter',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Contact Form 7', 'pixwell-importer' ),
						'slug' => 'contact-form-7',
						'file' => 'wp-contact-form-7',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					)
				);
			case '15' :
				return array(
					array(
						'name' => esc_html__( 'Breadcrumb NavXT', 'pixwell-importer' ),
						'slug' => 'breadcrumb-navxt',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Post Views Counter', 'pixwell-importer' ),
						'slug' => 'post-views-counter',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Contact Form 7', 'pixwell-importer' ),
						'slug' => 'contact-form-7',
						'file' => 'wp-contact-form-7',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					)
				);
			case '16' :
				return array(
					array(
						'name' => esc_html__( 'Breadcrumb NavXT', 'pixwell-importer' ),
						'slug' => 'breadcrumb-navxt',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Post Views Counter', 'pixwell-importer' ),
						'slug' => 'post-views-counter',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Contact Form 7', 'pixwell-importer' ),
						'slug' => 'contact-form-7',
						'file' => 'wp-contact-form-7',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					)
				);
			case '17' :
				return array(
					array(
						'name' => esc_html__( 'Breadcrumb NavXT', 'pixwell-importer' ),
						'slug' => 'breadcrumb-navxt',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Post Views Counter', 'pixwell-importer' ),
						'slug' => 'post-views-counter',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Contact Form 7', 'pixwell-importer' ),
						'slug' => 'contact-form-7',
						'file' => 'wp-contact-form-7',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					)
				);
			case '18' :
				return array(
					array(
						'name' => esc_html__( 'Breadcrumb NavXT', 'pixwell-importer' ),
						'slug' => 'breadcrumb-navxt',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Post Views Counter', 'pixwell-importer' ),
						'slug' => 'post-views-counter',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Contact Form 7', 'pixwell-importer' ),
						'slug' => 'contact-form-7',
						'file' => 'wp-contact-form-7',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'MC4WP: Mailchimp for WordPress', 'pixwell-importer' ),
						'slug' => 'mailchimp-for-wp',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					)
				);
			case '19' :
				return array(
					array(
						'name' => esc_html__( 'Post Views Counter', 'pixwell-importer' ),
						'slug' => 'post-views-counter',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Contact Form 7', 'pixwell-importer' ),
						'slug' => 'contact-form-7',
						'file' => 'wp-contact-form-7',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'MC4WP: Mailchimp for WordPress', 'pixwell-importer' ),
						'slug' => 'mailchimp-for-wp',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					)
				);
			case '20' :
				return array(
					array(
						'name' => esc_html__( 'Cookied', 'pixwell-importer' ),
						'slug' => 'cooked',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Contact Form 7', 'pixwell-importer' ),
						'slug' => 'contact-form-7',
						'file' => 'wp-contact-form-7',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
				);
			case '21' :
				return array(
					array(
						'name' => esc_html__( 'Contact Form 7', 'pixwell-importer' ),
						'slug' => 'contact-form-7',
						'file' => 'wp-contact-form-7',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'MC4WP: Mailchimp for WordPress', 'pixwell-importer' ),
						'slug' => 'mailchimp-for-wp',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					)
				);
			case '22' :
				return array(
					array(
						'name'   => esc_html__( 'Pixwell Deal', 'pixwell-importer' ),
						'slug'   => 'pixwell-deal',
						'class'  => 'important',
						'source' => get_theme_file_path( 'plugins/pixwell-deal.zip' ),
						'info'   => esc_html__( 'Required', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Contact Form 7', 'pixwell-importer' ),
						'slug' => 'contact-form-7',
						'file' => 'wp-contact-form-7',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Breadcrumb NavXT', 'pixwell-importer' ),
						'slug' => 'breadcrumb-navxt',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
				);
			case '23' :
				return array(
					array(
						'name'   => esc_html__( 'Pixwell Deal', 'pixwell-importer' ),
						'slug'   => 'pixwell-deal',
						'class'  => 'important',
						'source' => get_theme_file_path( 'plugins/pixwell-deal.zip' ),
						'info'   => esc_html__( 'Required', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Contact Form 7', 'pixwell-importer' ),
						'slug' => 'contact-form-7',
						'file' => 'wp-contact-form-7',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Breadcrumb NavXT', 'pixwell-importer' ),
						'slug' => 'breadcrumb-navxt',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
				);
			case '24' :
				return array(
					array(
						'name' => esc_html__( 'Contact Form 7', 'pixwell-importer' ),
						'slug' => 'contact-form-7',
						'file' => 'wp-contact-form-7',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'MC4WP: Mailchimp for WordPress', 'pixwell-importer' ),
						'slug' => 'mailchimp-for-wp',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					)
				);
			case '25' :
				return array(
					array(
						'name' => esc_html__( 'Contact Form 7', 'pixwell-importer' ),
						'slug' => 'contact-form-7',
						'file' => 'wp-contact-form-7',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'MC4WP: Mailchimp for WordPress', 'pixwell-importer' ),
						'slug' => 'mailchimp-for-wp',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Breadcrumb NavXT', 'pixwell-importer' ),
						'slug' => 'breadcrumb-navxt',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					)
				);
			case '26' :
				return array(
					array(
						'name' => esc_html__( 'Breadcrumb NavXT', 'pixwell-importer' ),
						'slug' => 'breadcrumb-navxt',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Post Views Counter', 'pixwell-importer' ),
						'slug' => 'post-views-counter',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Contact Form 7', 'pixwell-importer' ),
						'slug' => 'contact-form-7',
						'file' => 'wp-contact-form-7',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					)
				);
			case '27' :
				return array(
					array(
						'name' => esc_html__( 'MC4WP: Mailchimp for WordPress', 'pixwell-importer' ),
						'slug' => 'mailchimp-for-wp',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Contact Form 7', 'pixwell-importer' ),
						'slug' => 'contact-form-7',
						'file' => 'wp-contact-form-7',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					)
				);
			case '28' :
				return array(
					array(
						'name' => esc_html__( 'WooCommerce', 'pixwell-importer' ),
						'slug' => 'woocommerce',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'MC4WP: Mailchimp for WordPress', 'pixwell-importer' ),
						'slug' => 'mailchimp-for-wp',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Contact Form 7', 'pixwell-importer' ),
						'slug' => 'contact-form-7',
						'file' => 'wp-contact-form-7',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					)
				);
			case '29' :
				return array(
					array(
						'name' => esc_html__( 'Contact Form 7', 'pixwell-importer' ),
						'slug' => 'contact-form-7',
						'file' => 'wp-contact-form-7',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					)
				);
			case '30' :
				return array(
					array(
						'name' => esc_html__( 'WooCommerce', 'pixwell-importer' ),
						'slug' => 'woocommerce',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Contact Form 7', 'pixwell-importer' ),
						'slug' => 'contact-form-7',
						'file' => 'wp-contact-form-7',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					)
				);
			case '31' :
				return array(
					array(
						'name' => esc_html__( 'Breadcrumb NavXT', 'pixwell-importer' ),
						'slug' => 'breadcrumb-navxt',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					),
					array(
						'name' => esc_html__( 'Contact Form 7', 'pixwell-importer' ),
						'slug' => 'contact-form-7',
						'file' => 'wp-contact-form-7',
						'info' => esc_html__( 'Recommended', 'pixwell-importer' )
					)
				);
			default :
				return false;
		}
	}
}

/** theme options */
if ( ! function_exists( 'pixwell_theme_option_name' ) ) {
	function pixwell_theme_option_name() {
		return 'pixwell_theme_options';
	}
}

/** setup content */
if ( ! function_exists( 'pixwell_importer_demo_name' ) ) {
	function pixwell_importer_demo_name( $directory ) {
		switch ( $directory ) {
			case '11' :
				return esc_html__( 'Recipe Concept', 'pixwell-importer' );
			case '12' :
				return esc_html__( 'Fashion Concept', 'pixwell-importer' );
			case '13' :
				return esc_html__( 'Technology Concept', 'pixwell-importer' );
			case '14' :
				return esc_html__( 'Travel Concept', 'pixwell-importer' );
			case '15' :
				return esc_html__( 'LifeStyle Concept', 'pixwell-importer' );
			case '16' :
				return esc_html__( 'Photography Concept', 'pixwell-importer' );
			case '17' :
				return esc_html__( 'Baby Concept', 'pixwell-importer' );
			case '18' :
				return esc_html__( 'Blogger Concept', 'pixwell-importer' );
			case '19' :
				return esc_html__( 'Work Portfolio', 'pixwell-importer' );
			case '20' :
				return esc_html__( 'Food Concept', 'pixwell-importer' );
			case '21' :
				return esc_html__( 'Gadget Concept', 'pixwell-importer' );
			case '22' :
				return esc_html__( 'Review & Deal Concept', 'pixwell-importer' );
			case '23' :
				return esc_html__( 'Fashion Deal Concept', 'pixwell-importer' );
			case '24' :
				return esc_html__( 'Freebie Concept', 'pixwell-importer' );
			case '25' :
				return esc_html__( 'Sport Concept', 'pixwell-importer' );
			case '26' :
				return esc_html__( 'RTL Concept', 'pixwell-importer' );
			case '27' :
				return esc_html__( 'Decor Concept', 'pixwell-importer' );
			case '28' :
				return esc_html__( 'Beauty Concept', 'pixwell-importer' );
			case '29' :
				return esc_html__( 'Medical Concept', 'pixwell-importer' );
			case '30' :
				return esc_html__( 'Yoga Concept', 'pixwell-importer' );
			case '31' :
				return esc_html__( 'Marketing Concept', 'pixwell-importer' );
			default:
				return $directory;
		}
	}
}

/** setup content */
if ( ! function_exists( 'pixwell_importer_setup_content' ) ) {
	function pixwell_importer_setup_content( $directory ) {

		$homepage = '';
		switch ( $directory ) {
			case '11' :
				$homepage = 'Home Recipe';
				break;
			case '12' :
				$homepage = 'Home Fashion';
				break;
			case '13' :
				$homepage = 'Home Tech';
				break;
			case '14' :
				$homepage = 'Home Travel';
				break;
			case '15' :
				$homepage = 'Home Lifestyle';
				break;
			case '16' :
				$homepage = 'Home Photography';
				break;
			case '17' :
				$homepage = 'Home Baby';
				break;
			case '18' :
				$homepage = 'Home Blogger';
				break;
			case '19' :
				$homepage = 'Home Work';
				break;
			case '20' :
				$homepage = 'Home Food';
				break;
			case '21' :
				$homepage = 'Home Gadget';
				break;
			case '22' :
				$homepage = 'Home Review';
				break;
			case '23' :
				$homepage = 'Home Fdeal';
				break;
			case '24' :
				$homepage = 'Home Freebie';
				break;
			case '25' :
				$homepage = 'Home Sport';
				break;
			case '26' :
				$homepage = 'Home RTL';
				break;
			case '27' :
				$homepage = 'Home Decor';
				break;
			case '28' :
				$homepage = 'Home Beauty';
				break;
			case '29' :
				$homepage = 'Home Medical';
				break;
			case '30' :
				$homepage = 'Home Yoga';
				break;
			case '31' :
				$homepage = 'Home Marketing';
				break;
		}

		if ( ! empty( $homepage ) ) {
			$page = get_page_by_title( $homepage );
			if ( ! empty( $page->ID ) ) {
				update_option( 'page_on_front', $page->ID );
				update_option( 'show_on_front', 'page' );
				$blog = get_page_by_title( 'Blog' );
				if ( ! empty( $blog->ID ) ) {
					update_option( 'page_for_posts', $blog->ID );
				}
			} else {
				update_option( 'page_on_front', 0 );
				update_option( 'show_on_front', 'posts' );
			}
		}

		/** delete Hello word */
		wp_delete_post( 1 );

		/** setup WC */
		if ( class_exists( 'WC_Install' ) ) {
			WC_Install::create_pages();
		}

		/** setup menu */
		$main_menu   = get_term_by( 'name', 'main', 'nav_menu' );
		$footer_menu = get_term_by( 'name', 'footer', 'nav_menu' );
		$top_menu    = get_term_by( 'name', 'top', 'nav_menu' );

		$menu_locations = array();
		if ( isset( $main_menu->term_id ) ) {
			$menu_locations['pixwell_menu_main']      = $main_menu->term_id;
			$menu_locations['pixwell_menu_offcanvas'] = $main_menu->term_id;
		}
		if ( isset( $footer_menu->term_id ) ) {
			$menu_locations['pixwell_menu_footer'] = $footer_menu->term_id;
		}
		if ( isset( $top_menu->term_id ) ) {
			$menu_locations['pixwell_menu_top'] = $top_menu->term_id;
		}

		set_theme_mod( 'nav_menu_locations', $menu_locations );
	}
}

/** remove duplicated menu */
if ( ! function_exists( 'pixwell_importer_duplicate_menus' ) ) {
	function pixwell_importer_duplicate_menus() {

		$deleted_menus = array( 'main', 'top', 'footer', 'footer-col-1', 'footer-col-2', 'footer-col-3' );
		foreach ( $deleted_menus as $menu ) {
			wp_delete_nav_menu( $menu );
		}

		return false;
	}
}

/** init widgets */
if ( ! function_exists( 'pixwell_importer_init_widgets' ) ) {
	function pixwell_importer_init_widgets() {

		//empty all sidebars
		$sidebars_widgets['pixwell_sidebar_default']       = array();
		$sidebars_widgets['pixwell_sidebar_offcanvas']     = array();
		$sidebars_widgets['pixwell_sidebar_topsite']       = array();
		$sidebars_widgets['pixwell_sidebar_single_top']    = array();
		$sidebars_widgets['pixwell_sidebar_single_bottom'] = array();
		$sidebars_widgets['pixwell_sidebar_fw_footer']     = array();
		$sidebars_widgets['pixwell_sidebar_footer_1']      = array();
		$sidebars_widgets['pixwell_sidebar_footer_2']      = array();
		$sidebars_widgets['pixwell_sidebar_footer_3']      = array();

		/** add sidebars */
		$theme_options                          = get_option( 'pixwell_theme_options' );
		$theme_options['pixwell_multi_sidebar'] = array( 'sb1', 'sb2', 'infinite', 'single' );

		update_option( 'sidebars_widgets', $sidebars_widgets );
		update_option( 'pixwell_theme_options', $theme_options );

		/** register sidebar to import */
		register_sidebar( array(
			'name'          => 'sb1',
			'id'            => 'pixwell_sidebar_multi_sb1',
			'before_widget' => '<div id="%1$s" class="widget widget-sidebar %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>'
		) );

		register_sidebar( array(
			'name'          => 'sb2',
			'id'            => 'pixwell_sidebar_multi_sb2',
			'before_widget' => '<div id="%1$s" class="widget widget-sidebar %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>'
		) );

		register_sidebar( array(
			'name'          => 'infinite',
			'id'            => 'pixwell_sidebar_multi_infinite',
			'before_widget' => '<div id="%1$s" class="widget widget-sidebar %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>'
		) );

		register_sidebar( array(
			'name'          => 'single',
			'id'            => 'pixwell_sidebar_multi_single',
			'before_widget' => '<div id="%1$s" class="widget widget-sidebar %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>'
		) );


		return false;
	}
}

/** setup category */
if ( ! function_exists( 'pixwell_importer_setup_category' ) ) {
	function pixwell_importer_setup_category( $directory, $path ) {

		$category_file = $path . 'categories.txt';
		$data          = array();

		if ( ! file_exists( $category_file ) ) {
			return false;
		}

		WP_Filesystem();
		global $wp_filesystem;
		$content  = $wp_filesystem->get_contents( $category_file );
		$settings = json_decode( $content, true );

		if ( ! is_array( $settings ) ) {
			return false;
		}

		$settings   = array_values( $settings );
		$categories = get_categories( array(
			'hide_empty' => true,
			'info'       => 'post',
		) );
		$index      = 0;
		foreach ( $categories as $category ) {
			$id = $category->term_id;
			if ( isset( $settings[ $index ] ) ) {
				$data[ $id ] = $settings[ $index ];
			}
			$index ++;
		}

		update_option( 'pixwell_meta_categories', $data );

		return false;
	}
}

/** remove css cache */
if ( ! function_exists( 'pixwell_importer_remove_cache' ) ) {
	function pixwell_importer_remove_cache() {

		delete_option( 'pixwell_style_cache' );
		if ( function_exists( 'pixwell_write_dynamic_css' ) ) {
			pixwell_write_dynamic_css();
		}

		return false;
	}
}

if ( ! function_exists( 'pixwell_importer_header' ) ) {
	function pixwell_importer_header() {
		?>
		<div class="importer-header">
			<h2 class="importer-headline">
				<i class="dashicons dashicons-download"></i><?php esc_html_e( 'Ruby Importer - Install Demos' ); ?></h2>
			<div class="importer-desc">
				<p>Importing theme demo, It will allow you to quickly edit everything instead of creating content from
				   scratch. Please <strong>DO NOT navigate away</strong> from this page while the importer is
				   processing. This may take up to 5 ~ 7 minutes, Depend on the server speed.</p>
				<p>We do not have right to include some images of demos in the content due to copyright issue, so images
				   will look different with the demo. The structures of demos will still be left intact so can use your
				   own images in their places if you desire.</p>
			</div>
			<div class="importer-tips">
				<p><strong>Import Tips:</strong></p>
				<p>- Refresh this page and re-import if the process cannot complete after 5 minutes.</p>
				<p>- You can choose Only Pages, Widgets and Theme Options to import if you site already have data.</p>
				<p>- <strong>Don't need</strong> to install or activate Recommended & Optional plugins if you don't want to use it.</p>
				<p>- Install and activate Woocommerce plugin before importing if you would like setup shop (Fashion
				   Concept).</p>
				<p>- You can <strong>disable or delete</strong> the IMPORTER plugin after completed.</p>
				<p>- Online Documentation: <a href="http://help.themeruby.com/pixwell" target="_blank">http://help.themeruby.com/pixwell</a>
				</p>
			</div>
		</div>
	<?php
	}
}