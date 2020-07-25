<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** register metaboxes */
function rb_gallery_register_metaboxes( $pixwell_meta = array() ) {
	$pixwell_meta[] = array(
		'id'         => 'rb_gallery_options',
		'title'      => esc_html__( 'Gallery Options', 'pixwell-core' ),
		'context'    => 'normal',
		'post_types' => array( 'rb-gallery' ),
		'fields'     => array(
			array(
				'id'      => 'rb_gallery',
				'name'    => esc_html__( 'Upload Gallery', 'pixwell-core' ),
				'desc'    => esc_html__( 'Upload your images for this gallery, The gallery popup will get the title, caption and description of each image to display.', 'pixwell-core' ),
				'type'    => 'images',
				'default' => ''
			),
			array(
				'id'      => 'gallery_columns',
				'name'    => esc_html__( 'Columns of Grid', 'pixwell-core' ),
				'desc'    => esc_html__( 'Select total columns to display.', 'pixwell-core' ),
				'type'    => 'select',
				'options' => array(
					'2' => esc_html__( '2 Columns', 'pixwell-core' ),
					'3' => esc_html__( '3 Columns', 'pixwell-core' ),
					'4' => esc_html__( '4 Columns', 'pixwell-core' ),
					'5' => esc_html__( '5 Columns', 'pixwell-core' ),
				),
				'default' => '3'
			),
			array(
				'id'      => 'gallery_style',
				'name'    => esc_html__( 'Gallery Style', 'pixwell-core' ),
				'desc'    => esc_html__( 'Select the gallery style.', 'pixwell-core' ),
				'type'    => 'select',
				'options' => array(
					'light' => esc_html__( 'Light Style', 'pixwell-core' ),
					'dark'  => esc_html__( 'Dark Style', 'pixwell-core' ),
				),
				'default' => 'light'
			),
			array(
				'id'      => 'gallery_wrap',
				'name'    => esc_html__( 'Gallery Width', 'pixwell-core' ),
				'desc'    => esc_html__( 'Select max-width for gallery.', 'pixwell-core' ),
				'type'    => 'select',
				'options' => array(
					'0' => esc_html__( '- Wrapper - ', 'pixwell-core' ),
					'1' => esc_html__( 'Full Wide (100%)', 'pixwell-core' ),
				),
				'default' => '0'
			),
			array(
				'id'      => 'gallery_share_bottom',
				'name'    => esc_html__( 'Share on Socials', 'pixwell-core' ),
				'desc'    => esc_html__( 'Display the sharing bar at the bottom of this gallery.', 'pixwell-core' ),
				'type'    => 'select',
				'options' => array(
					'0'  => esc_html__( '- Default from Theme Options -', 'pixwell-core' ),
					'-1' => esc_html__( 'Disable', 'pixwell-core' ),
					'1'  => esc_html__( 'Enable', 'pixwell-core' ),
				),
				'default' => '0'
			),
		),
	);

	return $pixwell_meta;
}


/**
 * @param $template
 *
 * @return string
 * template redirect
 */
if ( ! function_exists( 'rb_gallery_template_redirect' ) ) {
	function rb_gallery_template_redirect( $template ) {

		global $wp_query;
		global $post;
		$file = '';
		if ( is_single() && get_post_type() == 'rb-gallery' ) {
			$file = 'single-gallery.php';
		} elseif ( is_tax( 'gallery-category' ) || is_post_type_archive( 'rb-gallery' ) ) {
			$file = 'archive-gallery.php';
		}

		if ( ! empty( $file ) ) {
			$template = locate_template( $file );
			if ( ! $template ) {
				$template = RB_GALLERY_PATH . '/templates/' . $file;
			}
		}

		return $template;
	}
}


/* show all gallery without navigation */
if ( ! function_exists( 'rb_gallery_show_all' ) ) {
	function rb_gallery_show_all( $query ) {

		if ( is_admin() ) {
			return false;
		}

		if ( $query->is_main_query() && ( is_tax( 'gallery-category' ) || is_post_type_archive( 'rb-gallery' ) ) ) {
			$query->set( 'posts_per_page', '-1' );
		}
	}
}
