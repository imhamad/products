<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'rb_covid', 'rb_render_covid_status' );
add_shortcode( 'rb_categories', 'rb_render_cat_list' );
add_shortcode( 'rb_users', 'rb_render_user_list' );
add_shortcode('rb_button', 'rb_render_btn_shortcode');

if ( ! function_exists( 'rb_get_covid_data' ) ) {
	function rb_get_covid_data( $country = 'all' ) {

		$data = get_transient( 'rb_covid_' . $country );
		if ( ! empty( $data['confirmed'] ) ) {
			return $data;
		}

		$data = array(
			'confirmed' => 0,
			'deaths'    => 0,
		);

		$params = array(
			'sslverify' => false,
			'timeout'   => 100
		);

		if ( 'all' == $country ) {
			$response = wp_remote_get( 'https://coronavirus-tracker-api.herokuapp.com/v2/latest', $params );
		} else {
			$response = wp_remote_get( 'https://coronavirus-tracker-api.herokuapp.com/v2/locations?country_code=' . esc_attr( $country ), $params );
		}

		if ( ! is_wp_error( $response ) && isset( $response['response']['code'] ) && '200' == $response['response']['code'] ) {
			$response = json_decode( wp_remote_retrieve_body( $response ) );
		}

		if ( ! empty( $response->latest ) ) {

			$latest = $response->latest;
			if ( ! empty( $latest->confirmed ) ) {
				$data['confirmed'] = $latest->confirmed;
			}
			if ( ! empty( $latest->deaths ) ) {
				$data['deaths'] = $latest->deaths;
			}
			set_transient( 'rb_covid_' . $country, $data, 43200 );
		}

		return $data;
	}
}

/** render covid static */
if ( ! function_exists( 'rb_render_covid_status' ) ) {
	function rb_render_covid_status( $attrs ) {
		$settings = shortcode_atts( array(
			'countries_code'  => '',
			'countries_label' => '',
			'deaths'          => '1',
			'columns'         => '3',
			'confirmed_label' => esc_html__( 'Confirmed Cases', 'pixwell-core' ),
			'death_label'     => esc_html__( 'Deaths Cases', 'pixwell-core' ),
			'date'            => '1'
		), $attrs );

		if ( empty( $settings['countries_code'] ) ) {
			return false;
		}

		$data = array();

		$index           = 0;
		$countries_code  = explode( ',', $settings['countries_code'] );
		$countries_label = explode( ',', $settings['countries_label'] );
		ob_start();

		if ( is_array( $countries_code ) ) {
			foreach ( $countries_code as $code ) {

				$status = rb_get_covid_data( trim( $code ) );

				if ( ! empty( $countries_label[ $index ] ) ) {
					$status['name'] = trim( $countries_label[ $index ] );
				}
				$index ++;
				array_push( $data, $status );
			} ?>
			<div class="rb-covid-statics is-cols-<?php echo esc_attr( $settings['columns'] ); ?>">
				<div class="statics-inner rb-n20-gutter">
					<?php foreach ( $data as $country ) : ?>
						<div class="statics-el rb-p20-gutter">
							<div class="inner">
								<?php if ( ! empty( $country['name'] ) ) : ?>
									<div class="country-name h5">
										<span><?php echo esc_html( $country['name'] ); ?></span></div>
								<?php endif; ?>
								<?php if ( isset( $country['confirmed'] ) ) : ?>
									<div class="country-confirmed">
										<div class="counter h2">
											<span><?php echo esc_html( $country['confirmed'] ); ?></span></div>
										<span class="label rb-sdesc"><?php echo esc_html( $settings['confirmed_label'] ); ?></span>
									</div>
								<?php endif;
								if ( ! empty( $settings['deaths'] ) && isset( $country['deaths'] ) ) : ?>
									<div class="country-dcount">
										<div class="counter h2"><span><?php echo esc_html( $country['deaths'] ); ?></span>
										</div>
										<span class="label rb-sdesc"><?php echo esc_html( $settings['death_label'] ); ?></span>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<?php if ( ! empty( $settings['date'] ) ) : ?>
					<div class="date-info rb-sdesc rb-p20-gutter">
						<span><?php echo date( get_option( 'date_format' ) ); ?></span></div>
				<?php endif; ?>
			</div>
		<?php
		}

		return ob_get_clean();
	}
}

/** category list */
if ( ! function_exists( 'rb_render_cat_list' ) ) {
	function rb_render_cat_list( $attrs ) {
		$settings = shortcode_atts( array(
			'categories'  => '',
			'layout'      => 'list',
			'columns'     => '2',
			'total'       => '',
			'offset'      => '',
			'exclude_ids' => '',
			'description' => '',
			'hide_empty'  => true
		), $attrs );

		$category_options = get_option( 'pixwell_meta_categories', array() );
		$lazyload         = pixwell_get_option( 'lazy_load' );
		$categories       = array();

		if ( empty( $settings['categories'] ) ) {
			$params = array(
				'taxonomy'   => 'category',
				'hide_empty' => $settings['hide_empty']
			);
			if ( ! empty( $settings['total'] ) ) {
				$params['number'] = intval( $settings['total'] );
			}
			if ( ! empty( $settings['offset'] ) ) {
				$params['offset'] = intval( $settings['offset'] );
			}

			if ( ! empty( $settings['exclude_ids'] ) ) {
				$params['exclude']  = $settings['exclude_ids'];
			}

			$categories = get_terms( $params );

		} else {
			$settings['categories'] = explode( ',', strval( $settings['categories'] ) );
			$settings['categories'] = array_unique( array_map( 'trim', $settings['categories'] ) );
			foreach ( $settings['categories'] as $key => $cat ) {
				if ( ! is_int( $cat ) ) {
					$category = get_term_by( 'slug', $cat, 'category' );
				} else {
					$category = get_term_by( 'term_id', $cat, 'category' );
				}
				array_push( $categories, $category );
			}
		}

		$wrapper_classes = 'rb-categories is-cols-' . intval( $settings['columns'] );
		$item_classes       = 'rb-citem citem-list';
		$t_classes       = 'citem-title h3';

		switch ( $settings['layout'] ) {
			case 'grid' :
				$wrapper_classes .= ' layout-grid';
				$item_classes = 'rb-citem citem-grid';
				break;
			case 'circle' :
				$wrapper_classes .= ' layout-grid circled';
				$item_classes = 'rb-citem citem-grid';
				break;
			default:
				$wrapper_classes .= ' layout-list';
		}

		if ( $settings['columns'] > 3 ) {
			$t_classes = 'citem-title h4';
		}

		ob_start(); ?>
		<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
			<div class="inner rb-n20-gutter">
				<?php foreach ( $categories as $category ) :

					$cat_id       = $category->term_id;
					$cat_name     = $category->name;
					$cat_link     = get_category_link( $category );
					$cat_featured = '';

					if ( ! empty( $category_options[ $cat_id ]['cat_featured'][0] ) ) {
						$cat_featured = wp_get_attachment_image_url( $category_options[ $cat_id ]['cat_featured'][0], 'pixwell_780x0-2x' );
						if ( empty( $cat_featured ) ) {
							$cat_featured = esc_url( $category_options[ $cat_id ]['cat_featured'][0] );
						}
					};

					if ( empty( $cat_featured ) ) {
						$cat_featured = 'data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=';
					}

					if ( ! empty( $settings['description'] ) ) {
						if ( $settings['description'] == '-1' ) {
							$cat_description = term_description( $cat_id );
						} else {
							$cat_description = wp_trim_words( term_description( $cat_id ), intval( $settings['description'] ), '' );
						}
					} ?>
					<div class="citem-outer rb-p20-gutter">
						<div class="<?php echo esc_attr( $item_classes ); ?>">
							<div class="citem-feat">
								<a href="<?php echo esc_url( $cat_link ); ?>" title="<?php echo esc_attr( $cat_name ); ?>">
									<span class="rb-iwrap">
										<?php if ( empty( $lazyload ) ) : ?>
											<img src="<?php echo esc_url( $cat_featured ); ?>" alt="<?php echo esc_html( $cat_name ); ?>">
										<?php else : ?>
											<img class="rb-lazyload" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" data-src="<?php echo esc_html( $cat_featured ); ?>" alt="<?php echo esc_html( $cat_name ); ?>">
										<?php endif; ?>
									</span>
								</a>
							</div>
							<div class="citem-content">
								<?php if ( ! empty( $cat_name ) ) : ?>
									<h6 class="<?php echo esc_attr( $t_classes ); ?>"><a href="<?php echo esc_url( $cat_link ); ?>"><?php echo esc_html( $cat_name ); ?></a></h6>
								<?php endif;
								if ( ! empty( $cat_description ) ) : ?>
									<div class="citem-decs rb-sdecs"><?php ?><?php echo wp_kses_post( $cat_description ); ?></div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}


/** user list */
if ( ! function_exists( 'rb_render_user_list' ) ) {
	function rb_render_user_list( $attrs ) {

		if ( ! function_exists( 'pixwell_render_author_box' ) ) {
			return false;
		}

		$settings = shortcode_atts( array(
			'users'         => '',
			'role'          => '',
			'exclude_users' => '',
			'columns'       => '1',
			'layout'        => 'list',
		), $attrs );

		$params = array(
			'blog_id' => get_current_blog_id(),
			'orderby' => 'nicename'
		);

		if ( ! empty( $settings['users'] ) ) {
			$settings['users']      = explode( ',', strval( $settings['users'] ) );
			$params['nicename__in'] = array_unique( array_map( 'trim', $settings['users'] ) );
			$params['orderby']      = 'nicename__in';
		};

		if ( ! empty( $settings['exclude_users'] ) ) {
			$settings['exclude_users']  = explode( ',', strval( $settings['exclude_users'] ) );
			$params['nicename__not_in'] = array_unique( array_map( 'trim', $settings['exclude_users'] ) );
		}

		if ( ! empty( $settings['role'] ) ) {
			$settings['role']   = explode( ',', strval( $settings['role'] ) );
			$params['role__in'] = array_unique( array_map( 'trim', $settings['role'] ) );
			$params['orderby']  = 'role__in';
		}

		$blogusers = get_users( $params );

		if ( empty( $blogusers ) ) {
			return false;
		}

		$wrapper_classes = 'rb-users layout-' . trim( $settings['layout'] ) . ' is-cols-' . intval( $settings['columns'] );

		ob_start(); ?>
		<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
			<div class="inner rb-n15-gutter">
				<?php foreach ( $blogusers as $user ) :
					echo '<div class="rb-uitem rb-p15-gutter">';
					pixwell_render_author_box( $user->ID );
					echo '</div>';
				endforeach; ?>
			</div>
		</div>
		<?php return ob_get_clean();
	}
}

/** button shortcode */
if ( ! function_exists( 'rb_render_btn_shortcode' ) ) {
	function rb_render_btn_shortcode( $attrs ) {
		$settings = shortcode_atts( array(
			'id'               => '',
			'label'            => '',
			'url'              => '#',
			'rel'              => '',
			'target'           => '',
			'background'       => '',
			'color'            => '',
			'style'            => '',
			'hover_style'      => '',
			'hover_color'      => '',
			'hover_background' => '',
			'hover_shadow'     => '',
			'size'             => '',
			'border'           => ''
		), $attrs );

		$target       = '';
		$id_attr      = '';
		$inline_style = '.rb-btn .rb-btn-link {';
		$hover        = '.rb-btn .rb-btn-link:hover {';
		$rel          = 'rel="nofollow"';
		$class_name   = 'rb-btn';

		if ( ! empty( $settings['id'] ) ) {
			$id_attr      = 'id= "rb-btn-' . esc_attr( $settings['id'] ) . '"';
			$inline_style = '#rb-btn-' . esc_attr( $settings['id'] ) . ' .rb-btn-link { ';
			$hover        = '#rb-btn-' . esc_attr( $settings['id'] ) . ' .rb-btn-link:hover{ ';
		}

		$inline_style .= 'visibility: visible !important;';

		if ( ! empty( $settings['size'] ) ) {
			$inline_style .= 'font-size:' . esc_attr( $settings['size'] ) . ';';
		}

		if ( ! empty( $settings['border'] ) ) {
			$inline_style .= 'border-radius:' . esc_attr( $settings['border'] ) . ';';
			$inline_style .= '-webkit-border-radius:' . esc_attr( $settings['border'] ) . ';';
		}

		if ( empty( $settings['style'] ) || 'border' != $settings['style'] ) {
			if ( ! empty( $settings['background'] ) ) {
				$inline_style .= 'background:' . esc_attr( $settings['background'] ) . ';';
			} else {
				$inline_style .= 'background: #ff8763;';
			}
			if ( ! empty( $settings['color'] ) ) {
				$inline_style .= 'color:' . esc_attr( $settings['color'] ) . ';';
				$inline_style .= 'border-color:' . esc_attr( $settings['color'] ) . ';';
			} else {
				$inline_style .= 'color: #fff;';
			}
		} else {

			$inline_style .= 'background:none;';
			if ( ! empty( $settings['color'] ) ) {
				$inline_style .= 'color:' . esc_attr( $settings['color'] ) . ';';
				$inline_style .= 'border-color:' . esc_attr( $settings['color'] ) . ';';
			} else {
				$inline_style .= 'color: inherit;';
				$inline_style .= 'border-color: inherit;';
			}
		}
		$inline_style .= '}';

		/** hover */
		if ( empty( $settings['hover_style'] ) || 'border' != $settings['hover_style'] ) {
			if ( ! empty( $settings['hover_background'] ) ) {
				$hover .= 'background:' . esc_attr( $settings['hover_background'] ) . ';';
				$hover .= 'border-color:' . esc_attr( $settings['hover_background'] ) . ';';
			} else {
				$hover .= 'background: #333;';
				$hover .= 'border-color:#333;';
			}

			if ( ! empty( $settings['hover_color'] ) ) {
				$hover .= 'color:' . esc_attr( $settings['hover_color'] ) . ';';
			} else {
				$hover .= 'color: #fff;';
			}
		} else {
			$inline_style .= 'background:none;';
			if ( ! empty( $settings['color'] ) ) {
				$inline_style .= 'color:' . esc_attr( $settings['color'] ) . ';';
				$inline_style .= 'border-color:' . esc_attr( $settings['color'] ) . ';';
			} else {
				$inline_style .= 'color: #fff;';
				$inline_style .= 'border-color: #fff;';
			}
		}

		$hover .= '}';
		$inline_style .= $hover;

		if ( ! empty( $settings['rel'] ) ) {
			$rel = 'rel="' . esc_attr( $settings['rel'] ) . '"';
		}

		if ( ! empty( $settings['target'] ) ) {
			$target = 'target="_blank"';
		}

		if ( ! empty( $settings['hover_shadow'] ) ) {
			$class_name .= ' h-shadow';
		}

		wp_add_inline_style( 'pixwell-shortcode', $inline_style );

		return '<div ' . $id_attr . ' class="' . esc_attr( $class_name ) . '"><a class="rb-btn-link" href="' . esc_url( $settings['url'] ) . '" ' . $target . ' ' . $rel . '>' . esc_html( $settings['label'] ) . '</a></div>';
	}
}