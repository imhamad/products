<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'post_thumbnail_html', 'rb_add_lazyload_featured', 10, 5 );
add_filter( 'max_srcset_image_width', 'rb_max_srcset_image_width', 10 );
add_filter( 'the_content', 'rb_add_lazyload_content', 1 );

/** rb_max_srcset_image_width */
if ( ! function_exists( 'rb_max_srcset_image_width' ) ) {
	function rb_max_srcset_image_width() {
		return 1200;
	}
}

/**
 * @param        $img_html
 * @param string $placeholder_url
 *
 * @return mixed
 * lazyload image
 */
if ( ! function_exists( 'rb_add_lazyload' ) ) {
	function rb_add_lazyload( $img_html, $placeholder_url = '' ) {
		if ( empty( $placeholder_url ) ) {
			$placeholder_url = apply_filters( 'rb_lazy_holder', 'data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=' );
		}
		$img_html = preg_replace( '/<img(.*?)src=/is', '<img$1src="' . esc_html( $placeholder_url ) . '" data-src=', $img_html );
		$img_html = str_replace( 'srcset', 'data-srcset', $img_html );
		$img_html = str_replace( 'sizes', 'data-sizes', $img_html );
		$img_html = preg_replace( '/class=(["\'])(.*?)["\']/is', 'class=$1rb-lazyload rb-autosize $2$1', $img_html );

		return $img_html;
	}
}

/** lazyload single content */
if ( ! function_exists( 'rb_add_lazyload_content' ) ) {
	function rb_add_lazyload_content( $content ) {

		$lazyload = pixwell_get_option( 'lazy_content' );

		if ( ! empty( $lazyload ) && is_single() && ! get_query_var( 'amp' ) ) {
			$placeholder_url = apply_filters( 'rb_lazy_holder', 'data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=' );
			$content = preg_replace( '/<figure(.*?)<img(.*?)src=/is', '<figure$1<img$2src="' . esc_html( $placeholder_url ) . '" data-src=', $content );
			$content = preg_replace( '/<figure(.*?)<img(.*?)class=(["\'])(.*?)["\']/is', '<figure$1<img$2class=$3rb-lazyload $4$3', $content );
			$content = preg_replace( '/<figure(.*?)<img(.*?)srcset=/is', '<figure$1<img$2data-srcset=', $content );
		}

		return $content;
	}
}


/**
 * @param $img_html
 * featured image lazy load
 */
if ( ! function_exists( 'rb_add_lazyload_featured' ) ) {
	function rb_add_lazyload_featured( $img_html, $post_id, $post_thumbnail_id, $size, $attr ) {

		$lazyload = pixwell_get_option( 'lazy_load' );

		if ( empty( $lazyload ) || strpos( $img_html, 'rb-no-lazy' ) !== false || get_query_var( 'amp' ) ) {
			return $img_html;
		}

		return rb_add_lazyload( $img_html );
	}
}


