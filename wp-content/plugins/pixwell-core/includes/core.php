<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** pixwell name to ID */
if ( ! function_exists( 'pixwell_convert_to_id' ) ) {
	function pixwell_convert_to_id( $name ) {
		$name = strtolower( strip_tags( $name ) );
		$name = str_replace( ' ', '-', $name );

		return preg_replace( '/[^A-Za-z0-9\-]/', '', $name );
	}
}

/** get protocol */
if ( ! function_exists( 'pixwell_protocol' ) ) {
	function pixwell_protocol() {
		$protocol = 'https';
		if ( ! is_ssl() ) {
			$protocol = 'http';
		}

		return $protocol;
	}
}


/**
 * @param $text
 *
 * @return bool|string
 * pixwell translation
 */
if ( ! function_exists( 'pixwell_translate' ) ) {
	function pixwell_translate( $text = null ) {

		if ( empty( $text ) ) {
			return false;
		}

		$text             = trim( $text );
		$translation_data = pixwell_translation_default();

		$key       = 't_' . $text;
		$translate = pixwell_get_option( $key );
		if ( ! empty( $translate ) ) {
			return esc_html( $translate );
		}

		if ( isset( $translation_data[ $text ] ) ) {
			return $translation_data[ $text ];
		};

		return false;
	}
}

/**
 * @param $shortcode
 *
 * @return string
 * pixwell_decode_shortcode
 */
if ( ! function_exists( 'pixwell_decode_shortcode' ) ) {
	function pixwell_decode_shortcode( $shortcode ) {
		return base64_decode( $shortcode, true );
	}
}

/**
 * @return array|mixed|void
 * return default data
 */
if ( ! function_exists( 'pixwell_translation_default' ) ) {
	function pixwell_translation_default() {

		$translation_data = array(
			'share_email'    => esc_html__( 'I found this article interesting and thought of sharing it with you. Check it out:', 'pixwell-core' ),
			'read_later'     => esc_html__( 'Read it Later', 'pixwell-core' ),
			'bookmark_empty' => esc_html__( 'Please add some posts to see your added bookmarks.', 'pixwell-core' ),
			'facebook'       => esc_html__( 'Facebook', 'pixwell-core' ),
			'fans'           => esc_html__( 'fans', 'pixwell-core' ),
			'like'           => esc_html__( 'like', 'pixwell-core' ),
			'twitter'        => esc_html__( 'Twitter', 'pixwell-core' ),
			'followers'      => esc_html__( 'followers', 'pixwell-core' ),
			'follow'         => esc_html__( 'follow', 'pixwell-core' ),
			'pinterest'      => esc_html__( 'Pinterest', 'pixwell-core' ),
			'pin'            => esc_html__( 'pin', 'pixwell-core' ),
			'love'           => esc_html__( 'Love', 'pixwell-core' ),
			'sad'            => esc_html__( 'Sad', 'pixwell-core' ),
			'happy'          => esc_html__( 'Happy', 'pixwell-core' ),
			'sleepy'         => esc_html__( 'Sleepy', 'pixwell-core' ),
			'angry'          => esc_html__( 'Angry', 'pixwell-core' ),
			'dead'           => esc_html__( 'Dead', 'pixwell-core' ),
			'wink'           => esc_html__( 'Wink', 'pixwell-core' ),
			'page'           => esc_html__( 'Page', 'pixwell-core' ),
		);
		$translation_data = apply_filters( 'pixwell_translation_data', $translation_data );

		return $translation_data;
	}
}


/**
 * @param $video_url
 *
 * @return bool|string
 * check video host
 */
if ( ! function_exists( 'pixwell_video_detect_url' ) ) {
	function pixwell_video_detect_url( $video_url ) {

		$video_url = strtolower( $video_url );

		if ( strpos( $video_url, 'youtube.com' ) !== false or strpos( $video_url, 'youtu.be' ) !== false ) {
			return 'youtube';
		}
		if ( strpos( $video_url, 'dailymotion.com' ) !== false ) {
			return 'dailymotion';
		}
		if ( strpos( $video_url, 'vimeo.com' ) !== false ) {
			return 'vimeo';
		}

		return false;
	}
}


/**
 * @param $video_url
 *
 * @return mixed
 * get youtube video ID
 */
if ( ! function_exists( 'pixwell_video_id_youtube' ) ) {
	function pixwell_video_id_youtube( $video_url ) {
		$s = array();
		parse_str( parse_url( $video_url, PHP_URL_QUERY ), $s );

		if ( empty( $s["v"] ) ) {
			$youtube_sl_explode = explode( '?', $video_url );

			$youtube_sl = explode( '/', $youtube_sl_explode[0] );
			if ( ! empty( $youtube_sl[3] ) ) {
				return $youtube_sl [3];
			}

			return $youtube_sl [0];
		} else {
			return $s["v"];
		}
	}
}


/**
 * @param $video_url
 *
 * @return mixed
 * get vimeo video ID
 */
if ( ! function_exists( 'pixwell_video_id_vimeo' ) ) {
	function pixwell_video_id_vimeo( $video_url ) {
		sscanf( parse_url( $video_url, PHP_URL_PATH ), '/%d', $video_id );

		return $video_id;
	}
}


if ( ! function_exists( 'pixwell_video_id_dailymotion' ) ) {
	function pixwell_video_id_dailymotion( $video_url ) {

		$video_id = strtok( basename( $video_url ), '_' );
		if ( strpos( $video_id, '#video=' ) !== false ) {
			$video_parts = explode( '#video=', $video_id );
			if ( ! empty( $video_parts[1] ) ) {
				return $video_parts[1];
			}
		};

		return $video_id;
	}
}


/**
 * @param $image_url
 *
 * @return bool
 * check response
 */
if ( ! function_exists( 'pixwell_video_feat_response' ) ) {
	function  pixwell_video_feat_response( $image_url ) {
		$headers = @get_headers( $image_url );
		if ( ! empty( $headers[0] ) and strpos( $headers[0], '404' ) !== false ) {
			return true;
		}

		return false;
	}
}


/**
 * @param $video_url
 * get video thumbnail youtube
 */
if ( ! function_exists( 'pixwell_video_get_feat_youtube' ) ) {
	function pixwell_video_get_feat_youtube( $video_url ) {

		$protocol = pixwell_protocol();
		$video_id = pixwell_video_id_youtube( $video_url );

		$image_url_1920 = $protocol . '://img.youtube.com/vi/' . $video_id . '/maxresdefault.jpg';
		$image_url_640  = $protocol . '://img.youtube.com/vi/' . $video_id . '/sddefault.jpg';
		$image_url_480  = $protocol . '://img.youtube.com/vi/' . $video_id . '/hqdefault.jpg';

		if ( ! pixwell_video_feat_response( $image_url_1920 ) ) {
			return $image_url_1920;
		} elseif ( ! pixwell_video_feat_response( $image_url_640 ) ) {
			return $image_url_640;
		} elseif ( ! pixwell_video_feat_response( $image_url_480 ) ) {
			return $image_url_480;
		} else {
			return false;
		}
	}
}


/**
 * @param $video_url
 *
 * @return bool
 * get vimeo featured image
 */
if ( ! function_exists( 'pixwell_video_get_feat_vimeo' ) ) {
	function pixwell_video_get_feat_vimeo( $video_url ) {

		$protocol = pixwell_protocol();
		$video_id = pixwell_video_id_vimeo( $video_url );
		$api_url  = $protocol . '://vimeo.com/api/oembed.json?url=https://vimeo.com/' . $video_id;

		$data_response = wp_remote_get( $api_url, array(
				'timeout'    => 60,
				'sslverify'  => false,
				'user-agent' => 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0'
			)
		);

		if ( ! is_wp_error( $data_response ) ) {
			$data_response = wp_remote_retrieve_body( $data_response );
			$data_response = json_decode( $data_response );
			$image_url     = $data_response->thumbnail_url;

			return $image_url;
		} else {
			return false;
		}
	}
}


/**
 * @param $video_url
 *
 * @return bool
 * get dailymotion featured image
 */
if ( ! function_exists( 'pixwell_video_get_feat_dailymotion' ) ) {
	function pixwell_video_get_feat_dailymotion( $video_url ) {

		$video_id = pixwell_video_id_dailymotion( $video_url );
		$protocol = pixwell_protocol();

		$param         = $protocol . '://api.dailymotion.com/video/' . $video_id . '?fields=thumbnail_url';
		$data_response = wp_remote_get( $param );
		if ( ! is_wp_error( $data_response ) ) {
			$data_response = json_decode( $data_response['body'] );
			$image_url     = $data_response->thumbnail_url;

			return $image_url;
		} else {
			return false;
		}
	}
}

/**
 * @param $video_url
 *
 * @return bool|string
 * get video featured image
 */
if ( ! function_exists( 'pixwell_video_get_feat' ) ) {
	function pixwell_video_get_feat( $video_url ) {

		if ( empty( $video_url ) ) {
			return false;
		}

		$host_name = pixwell_video_detect_url( $video_url );

		switch ( $host_name ) {
			case 'youtube' :
				return pixwell_video_get_feat_youtube( $video_url );
			case 'vimeo' :
				return pixwell_video_get_feat_vimeo( $video_url );
			case 'dailymotion' :
				return pixwell_video_get_feat_dailymotion( $video_url );
			default :
				return false;
		}
	}
}

/**
 * @param $att_id
 * set featured thumbnail
 */
if ( ! function_exists( 'pixwell_video_set_featured' ) ) {
	function pixwell_video_set_featured( $att_id ) {
		update_post_meta( get_the_ID(), '_thumbnail_id', $att_id );
	}
}


/**
 * @param $post_id
 *
 * @return bool
 * get and save video featured image
 */
add_action( 'save_post', 'pixwell_video_save_featured', 10, 1 );

if ( ! function_exists( 'pixwell_video_save_featured' ) ) {
	function pixwell_video_save_featured( $post_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}

		if ( get_post_status( $post_id ) != 'publish' ) {
			return false;
		}

		$post_type = get_post_type( $post_id );
		$video_url = rb_get_meta( 'video_url', $post_id );

		if ( 'post' != $post_type || empty( $video_url ) ) {
			return false;
		}

		$image_url = pixwell_video_get_feat( $video_url );

		if ( ! empty( $image_url ) && ! has_post_thumbnail( $post_id ) ) {
			add_action( 'add_attachment', 'pixwell_video_set_featured' );
			media_sideload_image( $image_url, $post_id, $post_id );
			remove_action( 'add_attachment', 'pixwell_video_set_featured' );
		};

		return false;
	}
}


/**
 * @param $image
 * @param $attachment_id
 * @param $size
 * @param $icon
 *
 * @return array|false
 * gif support
 */

if ( ! function_exists( 'pixwell_support_gif' ) ) {
	function pixwell_support_gif( $image, $attachment_id, $size, $icon ) {

		$gif_support = pixwell_get_option( 'gif_support' );

		if ( ! empty( $gif_support ) && ! empty( $image[0] ) ) {
			$format = wp_check_filetype( $image[0] );

			if ( ! empty( $format ) && 'gif' == $format['ext'] && 'full' != $size ) {
				return wp_get_attachment_image_src( $attachment_id, $size = 'full', $icon );
			}
		}

		return $image;
	}
}

add_filter( 'wp_get_attachment_image_src', 'pixwell_support_gif', 10, 4 );


/** show over k */
if ( ! function_exists( 'pixwell_show_over_k' ) ) {
	function pixwell_show_over_k( $number ) {
		$number = intval( $number );

		if ( $number > 999999 ) {
			$number = str_replace( '.0', '', number_format( ( $number / 1000000 ), 1 ) ) . esc_attr__( 'M', 'pixwell-core' );
		} elseif ( $number > 999 ) {
			$number = str_replace( '.0', '', number_format( ( $number / 1000 ), 1 ) ) . esc_attr__( 'k', 'pixwell-core' );
		}

		return $number;
	}
}

/**
 * count total word
 */
if ( ! function_exists( 'pixwell_total_word' ) ) {
	function pixwell_total_word( $content = '' ) {

		if ( empty( $content ) ) {
			return false;
		}
		$count   = 0;
		$content = preg_replace( '/(<\/[^>]+?>)(<[^>\/][^>]*?>)/', '$1 $2', $content );
		$content = nl2br( $content );
		$content = strip_tags( $content );

		if ( preg_match( "/[\x{4e00}-\x{9fa5}]+/u", $content ) ) {
			$content = preg_replace( '/[\x80-\xff]{1,3}/', ' ', $content, - 1, $n );
			$count += str_word_count( $content );
		} else {
			$count = count( preg_split( '/\s+/', $content ) );
		}

		return $count;
	}
}