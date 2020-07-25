<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @param $str
 *
 * @return int
 * extract number
 */
if ( ! function_exists( 'pixwell_extract_number' ) ) {
	function pixwell_extract_number( $str ) {
		return intval( preg_replace( '/[^0-9]+/', '', $str ), 10 );
	}
}

/**
 * @param $page
 * @param $token
 * @param $widget_id
 *
 * @return int
 * facebook follower
 */
if ( ! function_exists( 'pixwell_follower_fb' ) ) {
	function pixwell_follower_fb( $data ) {

		if ( empty( $data['facebook_page'] ) ) {
			return false;
		}

		$cache = get_transient( 'social_follower' );
		if ( isset( $cache[ $data['widget_id'] ]['facebook'] ) ) {
			return $cache[ $data['widget_id'] ]['facebook'];
		}

		$fan    = false;
		$params = array(
			'sslverify' => false,
			'timeout'   => 100
		);

		if ( ! empty( $data['facebook_api'] ) ) {
			$response = wp_remote_get( 'https://graph.facebook.com/v2.9/' . urlencode( $data['facebook_page'] ) . '?access_token=' . $data['facebook_api'] . '&fields=fan_count', $params );
			if ( ! is_wp_error( $response ) && isset( $response['response']['code'] ) && '200' == $response['response']['code'] ) {
				$response = json_decode( wp_remote_retrieve_body( $response ) );
			}

			if ( ! empty( $response->fan_count ) ) {
				$fan = $response->fan_count;
			}
		}

		if ( false == $fan ) {
			$filter = array(
				array(
					'start_1' => 'id="PagesLikesCountDOMID"',
					'start_2' => '<span',
					'start_3' => '>',
					'end_4'   => '<span',
				),
				array(
					'start_1' => '["PagesLikesTab","renderLikesData",["',
					'start_2' => '},',
					'start_3' => '],',
					'end_4'   => '],[]],["PagesLikesTab"',
				)
			);

			$response = wp_remote_get( 'https://www.facebook.com/' . $data['facebook_page'] );

			if ( is_wp_error( $response ) || empty( $response['response']['code'] ) || '200' != $response['response']['code'] ) {
				$response = wp_remote_get( 'https://www.facebook.com/' . $data['facebook_page'], $params );
			}

			if ( ! is_wp_error( $response ) && ! empty( $response['response']['code'] ) && '200' == $response['response']['code'] ) {
				$response = wp_remote_retrieve_body( $response );
				if ( ! empty( $response ) && $response !== false ) {
					$flag            = false;
					$response_backup = $response;

					foreach ( $filter as $filter_el ) {
						$response = $response_backup;
						foreach ( $filter_el as $key => $value ) {

							$key = explode( '_', $key );
							$key = $key[0];

							if ( $key == 'start' ) {
								$key = false;
							} elseif ( $value == 'end' ) {
								$key = true;
							}

							$key = (bool) $key;

							$index = strpos( $response, $value );
							if ( $index === false ) {
								break;
							}

							if ( $key ) {
								$response = substr( $response, 0, $index );
								$flag     = true;

							} else {
								$response = substr( $response, $index + strlen( $value ) );
							}
						}

						if ( $flag ) {
							break;
						}
					}

					if ( strlen( $response ) < 150 ) {
						$count = pixwell_extract_number( $response );

						if ( is_numeric( $count ) && strlen( number_format( $count ) ) < 16 ) {
							$fan = intval( $count );
						}
					}
				}
			}
		}

		$cache[ $data['widget_id'] ]['facebook'] = $fan;
		set_transient( 'social_follower', $cache, 12000 );

		return $fan;
	}
}


/**
 * @param $data
 *
 * @return bool|int
 * twitter counter
 */
if ( ! function_exists( 'pixwell_follower_twitter' ) ) {
	function pixwell_follower_twitter( $data ) {

		if ( empty( $data['user'] ) ) {
			return false;
		}

		$cache = get_transient( 'social_follower' );
		if ( isset( $cache[ $data['widget_id'] ]['twitter'] ) ) {
			return $cache[ $data['widget_id'] ]['twitter'];
		}

		$fan = false;

		if ( ! empty( $data['consumer_key'] ) && ! empty( $data['consumer_secret'] ) ) {

			$credentials = $data['consumer_key'] . ':' . $data['consumer_secret'];
			$to_send     = base64_encode( $credentials );
			$token       = get_option( 'pixwell_twitter_token' );

			if ( empty( $token ) ) {
				$args = array(
					'method'      => 'POST',
					'httpversion' => '1.1',
					'blocking'    => true,
					'headers'     => array(
						'Authorization' => 'Basic ' . $to_send,
						'Content-Type'  => 'application/x-www-form-urlencoded',
					),
					'body'        => array( 'grant_type' => 'client_credentials' )
				);
				add_filter( 'https_ssl_verify', '__return_false' );
				$response = wp_remote_post( 'https://api.twitter.com/oauth2/token', $args );
				$keys     = json_decode( wp_remote_retrieve_body( $response ) );
				if ( $keys ) {
					$token = $keys->access_token;
					update_option( 'pixwell_twitter_token', $token );
				};
			}

			$args = array(
				'httpversion' => '1.1',
				'blocking'    => true,
				'headers'     => array(
					'Authorization' => "Bearer $token"
				)
			);
			add_filter( 'https_ssl_verify', '__return_false' );
			$api_url  = 'https://api.twitter.com/1.1/users/show.json?screen_name=' . $data['user'];
			$response = wp_remote_get( $api_url, $args );
			if ( ! is_wp_error( $response ) ) {
				$followers = json_decode( wp_remote_retrieve_body( $response ) );
				if ( ! empty( $followers->followers_count ) ) {
					$fan = $followers->followers_count;
				}
			}
		}

		if ( false == $fan ) {

			$params = array(
				'timeout'   => 100,
				'sslverify' => false
			);

			$filter = array(
				'start_1' => 'ProfileNav-item--followers',
				'start_2' => 'title',
				'end'     => '>'
			);

			$response = wp_remote_get( 'https://twitter.com/' . $data['user'], $params );

			if ( ! is_wp_error( $response ) && ! empty( $response['response']['code'] ) && '200' == $response['response']['code'] ) {
				$response = wp_remote_retrieve_body( $response );

				if ( ! empty( $response ) && $response !== false ) {
					foreach ( $filter as $key => $value ) {

						$key = explode( '_', $key );
						$key = $key[0];

						if ( $key == 'start' ) {
							$key = false;
						} else if ( $value == 'end' ) {
							$key = true;
						}
						$key = (bool) $key;

						$index = strpos( $response, $value );
						if ( $index === false ) {
							break;
						}
						if ( $key ) {
							$response = substr( $response, 0, $index );
						} else {
							$response = substr( $response, $index + strlen( $value ) );
						}
					}

					if ( strlen( $response ) < 100 ) {
						$fan = pixwell_extract_number( $response );
						if ( is_numeric( $fan ) && strlen( number_format( $fan ) ) < 15 ) {
							$fan = intval( $fan );
						}
					}

				}
			}
		}

		$cache[ $data['widget_id'] ]['twitter'] = $fan;
		set_transient( 'social_follower', $cache, 12000 );

		return $fan;
	}
}


/**
 * @param $data
 *
 * @return int
 * pinterest counter
 */
if ( ! function_exists( 'pixwell_follower_pin' ) ) {
	function pixwell_follower_pin( $data ) {

		if ( empty( $data['user'] ) ) {
			return false;
		}

		$cache = get_transient( 'social_follower' );

		if ( isset( $cache[ $data['widget_id'] ]['pinterest'] ) ) {
			return $cache[ $data['widget_id'] ]['pinterest'];
		}

		$fan = 0;

		$response = get_meta_tags( 'http://pinterest.com/' . $data['user'] . '/' );
		if ( ! empty( $response ) && ! empty( $response['pinterestapp:followers'] ) ) {
			$fan = intval( strip_tags( $response['pinterestapp:followers'] ) );
		}

		$cache[ $data['widget_id'] ]['pinterest'] = $fan;
		set_transient( 'social_follower', $cache, 12000 );

		return $fan;
	}
}

/**
 * @param array $settings
 *
 * @return array|mixed|object
 * get Instagram images with token
 */
if ( ! function_exists( 'pixwell_data_instagram_token' ) ) {
	function pixwell_data_instagram_token( $settings = array() ) {

		$cache_name = 'pixwell_instagram_cache';

		if ( ! empty( $settings['cache_id'] ) ) {
			$cache_id = $settings['cache_id'];
		} else {
			$cache_id = 0;
		}

		$cache_data = get_transient( $cache_name );

		if ( ! is_array( $cache_data ) ) {
			$cache_data = array();
		}

		if ( ! empty( $cache_data[ $cache_id ] ) ) {
			return $cache_data[ $cache_id ];
		} else {

			if ( empty( $settings['instagram_token'] ) ) {
				return esc_html__( 'empty Instagram token', 'pixwell-core' );
			}

			$token = trim( $settings['instagram_token'] );
			if ( ! empty( $settings['total_images'] ) ) {
				$total = intval( $settings['total_images'] );
			} else {
				$total = 6;
			}

			$user = explode( ".", $token );

			if ( empty( $user[0] ) ) {
				return esc_html__( 'token error.', 'pixwell-core' );
			} else {

				$params = array(
					'sslverify' => false,
					'timeout'   => 100
				);

				if ( ! empty( $settings['tag'] ) ) {
					$response = wp_remote_get( 'https://api.instagram.com/v1/tags/' . trim( $settings['tag'] ) . '/media/recent/?access_token=' . $token . '&count=' . $total, $params );
				} else {
					$response = wp_remote_get( 'https://api.instagram.com/v1/users/' . $user[0] . '/media/recent/?access_token=' . $token . '&count=' . $total, $params );
				}

				if ( is_wp_error( $response ) || empty( $response['response']['code'] ) || 200 != $response['response']['code'] ) {
					return esc_html__( 'Token expired or no pictures.', 'pixwell-core' );
				};

				$data_images = array();
				$response    = json_decode( wp_remote_retrieve_body( $response ) );

				if ( ! empty( $response->data ) && is_array( $response->data ) ) {
					foreach ( $response->data as $image ) {

						$caption   = esc_html__( 'instagram image', 'pixwell_core' );
						$link      = '#';
						$likes     = '';
						$comments  = '';
						$thumbnail = '#';

						if ( ! empty( $image->link ) ) {
							$link = esc_url( $image->link );
						}
						if ( ! empty( $image->images->standard_resolution->url ) ) {
							$thumbnail = esc_url( $image->images->standard_resolution->url );
						} elseif ( ! empty( $image->images->low_resolution->url ) ) {
							$thumbnail = esc_url( $image->images->low_resolution->url );
						}

						if ( ! empty( $image->$caption ) ) {
							$caption = wp_kses_post( $image->$caption );
						}

						if ( ! empty( $image->likes->count ) ) {
							$likes = intval( $image->likes->count );
						}

						if ( ! empty( $image->comments->count ) ) {
							$comments = intval( $image->comments->count );
						}

						$data_images[] = array(
							'thumbnail_src' => $thumbnail,
							'caption'       => wp_kses_post( $caption ),
							'link'          => $link,
							'likes'         => $likes,
							'comments'      => $comments
						);
					}
				}

				$cache_data[ $cache_id ] = $data_images;

				delete_transient( $cache_name );
				set_transient( $cache_name, $cache_data, 12000 );

				return $data_images;
			}
		}
	}
}


/**
 * @param array $settings
 *
 * @return mixed
 * get instagram images without token
 */
if ( ! function_exists( 'pixwell_data_instagram_no_token' ) ) {
	function pixwell_data_instagram_no_token( $settings = array() ) {

		$cache_name = 'pixwell_instagram_cache';

		if ( ! empty( $settings['cache_id'] ) ) {
			$cache_id = $settings['cache_id'];
		} else {
			$cache_id = 0;
		}

		$cache_data = get_transient( $cache_name );

		if ( ! is_array( $cache_data ) ) {
			$cache_data = array();
		}

		if ( ! empty( $cache_data[ $cache_id ] ) ) {
			return $cache_data[ $cache_id ];
		} else {

			if ( empty( $settings['user_name'] ) ) {
				return false;
			}

			$user_name = trim( strtolower( $settings['user_name'] ) );
			switch ( substr( $user_name, 0, 1 ) ) {
				case '#':
					$url = 'https://instagram.com/explore/tags/' . str_replace( '#', '', $user_name );
					break;
				default:
					$url = 'https://instagram.com/' . str_replace( '@', '', $user_name );
					break;
			}

			$params = array(
				'sslverify' => false,
				'timeout'   => 100
			);

			$response = wp_remote_get( $url, $params );
			if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
				return esc_html__( 'Unable to connect to Instagram.', 'pixwell-core' );
			}

			$response = explode( 'window._sharedData = ', $response['body'] );
			$response = explode( ';</script>', $response[1] );
			$response = json_decode( $response[0], true );

			if ( empty( $response ) ) {
				return esc_html__( 'invalid data.', 'pixwell-core' );
			}

			if ( isset( $response['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
				$response = $response['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
			} elseif ( isset( $response['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
				$response = $response['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
			} else {
				return esc_html__( 'invalid data.', 'pixwell-core' );
			}

			if ( ! is_array( $response ) ) {
				return esc_html__( 'invalid data.', 'pixwell-core' );
			}

			$data_images = array();

			foreach ( $response as $image ) {
				$image['thumbnail_src'] = '';

				if ( ! empty( $image['node']['thumbnail_resources'][4]['src'] ) ) {
					$image['thumbnail_src'] = preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] );
				} elseif ( ! empty( $image['node']['thumbnail_resources'][2]['src'] ) ) {
					$image['thumbnail_src'] = preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] );
				} elseif ( ! empty( $image['node']['thumbnail_resources'][0]['src'] ) ) {
					$image['thumbnail_src'] = preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] );
				}

				if ( ! empty( $image['node']['shortcode'] ) ) {
					$link = trailingslashit( '//instagram.com/p/' . $image['node']['shortcode'] );
				} else {
					$link = '#';
				}

				if ( ! empty( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
					$caption = esc_attr( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'] );
				} else {
					$caption = esc_html__( 'instagram image', 'pixwell_core' );
				}

				if ( ! empty( $image['node']['edge_liked_by']['count'] ) ) {
					$likes = intval( $image['node']['edge_liked_by']['count'] );
				} else {
					$likes = '';
				}

				if ( ! empty( $image['node']['edge_media_to_comment']['count'] ) ) {
					$comments = intval( $image['node']['edge_media_to_comment']['count'] );
				} else {
					$comments = '';
				}

				$data_images[] = array(
					'thumbnail_src' => $image['thumbnail_src'],
					'caption'       => wp_kses_post( $caption ),
					'link'          => $link,
					'likes'         => $likes,
					'comments'      => $comments
				);
			}

			$cache_data[ $cache_id ] = $data_images;
			delete_transient( $cache_name );
			set_transient( $cache_name, $cache_data, 12000 );

			return $data_images;
		}
	}
}


/**
 * @param array $settings
 *
 * @return array|mixed|object
 * flickr data
 */
if ( ! function_exists( 'pixwell_data_flickr' ) ) {
	function pixwell_data_flickr( $settings = array() ) {

		if ( empty( $settings['flickr_id'] ) ) {
			return false;
		};

		$cache_name = 'pixwell_flickr_cache';

		if ( ! empty( $settings['cache_id'] ) ) {
			$cache_id = $settings['cache_id'];
		} else {
			$cache_id = 0;
		}

		$cache_data = get_transient( $cache_name );

		if ( ! is_array( $cache_data ) ) {
			$cache_data = array();
		}

		if ( ! empty( $cache_data[ $cache_id ] ) ) {
			return $cache_data[ $cache_id ];
		} else {

			if ( empty( $settings['tag'] ) ) {
				$settings['tag'] = '';
			}

			if ( empty( $settings['total_images'] ) ) {
				$settings['total_images'] = 9;
			}

			$params = array( 'timeout' => 100, 'sslverify' => false );

			$response = wp_remote_get( 'http://api.flickr.com/services/feeds/photos_public.gne?format=json&id=' . urlencode( $settings['flickr_id'] ) . '&nojsoncallback=1&tags=' . urlencode( $settings['tag'] ), $params );

			if ( is_wp_error( $response ) || '200' != $response['response']['code'] ) {
				return false;
			}

			$response    = wp_remote_retrieve_body( $response );
			$response    = str_replace( "\\'", "'", $response );
			$data_images = json_decode( $response, true );

			if ( is_array( $data_images ) ) {
				$data_images = array_slice( $data_images['items'], 0, $settings['total_images'] );

				foreach ( $data_images as $i => $v ) {
					$data_images[ $i ]['media'] = preg_replace( '/_m\.(jp?g|png|gif)$/', '_m.\\1', $v['media']['m'] );
				}

				$cache_data[ $cache_id ] = $data_images;
				delete_transient( $cache_name );
				set_transient( $cache_name, $cache_data, 12000 );

				return $data_images;
			} else {
				return false;
			}
		}
	}
}