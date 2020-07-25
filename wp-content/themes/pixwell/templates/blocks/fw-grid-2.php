<?php
/**
 * @param array $settings
 * fullwidth grid 2
 */
if ( ! function_exists( 'pixwell_rbc_fw_grid_2' ) ) {
	function pixwell_rbc_fw_grid_2( $attrs ) {
		$settings = shortcode_atts( array(
			'uuid'               => '',
			'name'               => 'fw_grid_2',
			'category'           => '',
			'categories'         => '',
			'format'             => '',
			'tags'               => '',
			'tag_not_in'         => '',
			'author'             => '',
			'post_not_in'        => '',
			'post_in'            => '',
			'order'              => '',
			'posts_per_page'     => '',
			'offset'             => '',
			'title'              => '',
			'viewmore_title'     => '',
			'viewmore_link'      => '',
			'quick_filter'       => '',
			'quick_filter_ids'   => '',
			'quick_filter_label' => '',
			'pagination'         => '',
			'text_style'         => '',
			'infeed_ad'          => '',
			'infeed_description' => '',
			'html_advert'        => '',
			'ad_attachment'      => '',
			'ad_destination'     => '',
			'ad_pos_1'           => '',
			'ad_pos_2'           => '',
			'columns'            => true
		), $attrs );

		$settings['classes']         = 'fw-block fw-grid-2';
		$settings['content_classes'] = 'rb-n15-gutter';

		$total_posts = $settings['posts_per_page'];
		if ( ! empty( $settings['infeed_ad'] ) ) {
			$total_ads = 0;
			if ( ! empty( $settings['ad_pos_1'] ) ) {
				$total_ads ++;
			}
			if ( ! empty( $settings['ad_pos_2'] ) ) {
				$total_ads ++;
			}
			$settings['posts_per_page'] = intval( $total_posts - $total_ads );
		}

		$query_data = pixwell_query( $settings );

		$settings['posts_per_page'] = $total_posts;

		ob_start();

		pixwell_block_open( $settings, $query_data );
		pixwell_block_header( $settings );
		if ( $query_data->have_posts() ) :
			pixwell_block_content_open( $settings );
			pixwell_rbc_fw_grid_2_listing( $settings, $query_data );
			pixwell_block_content_close();
			pixwell_render_pagination( $settings, $query_data );
			wp_reset_postdata();
		else:
			pixwell_no_post();
		endif;
		pixwell_block_close();

		return ob_get_clean();
	}
}

/**
 * fw grid 2 listing
 */
if ( ! function_exists( 'pixwell_rbc_fw_grid_2_listing' ) ) :
	function pixwell_rbc_fw_grid_2_listing( $settings = array(), $query_data ) {
		if ( method_exists( $query_data, 'have_posts' ) ) :
			$counter = 1;

			while ( $query_data->have_posts() ) :
				$query_data->the_post();

				/** render in-feed adverts */
				if ( ! empty( $settings['infeed_ad'] ) && ! wp_doing_ajax() ) :
					if ( ( ! empty( $settings['ad_pos_1'] ) && intval( $settings['ad_pos_1'] ) == $counter ) || ( ! empty( $settings['ad_pos_2'] ) && intval( $settings['ad_pos_2'] ) == $counter ) ) {
						echo '<div class="rb-col-m6 rb-col-d3 rb-p15-gutter infeed-outer">';
						pixwell_infeed_advert( $settings );
						echo '</div>';
					}
					$counter ++;
				endif;

				echo '<div class="rb-col-m6 rb-col-d3 rb-p15-gutter">';
				pixwell_post_grid_2( $settings );
				echo '</div>';

			endwhile;

			/** render in-feed adverts at the end */
			if ( ! empty( $settings['infeed_ad'] ) && ! wp_doing_ajax() ) :
				if ( ( ! empty( $settings['ad_pos_1'] ) && intval( $settings['ad_pos_1'] ) >= $settings['posts_per_page'] ) || ( ! empty( $settings['ad_pos_2'] ) && intval( $settings['ad_pos_2'] ) >= $settings['posts_per_page'] ) ) {
					echo '<div class="rb-col-m6 rb-col-d3 rb-p15-gutter infeed-outer">';
					pixwell_infeed_advert( $settings );
					echo '</div>';
				}
			endif;

		endif;
	}
endif;

/**
 * @param $blocks
 *
 * @return array
 * register block settings
 */
if ( ! function_exists( 'pixwell_register_fw_grid_2' ) ) {
	function pixwell_register_fw_grid_2( $blocks ) {

		if ( ! is_array( $blocks ) ) {
			$blocks = array();
		}

		$blocks[] = array(
			'name'        => 'fw_grid_2',
			'title'       => esc_html__( 'Grid 2', 'pixwell' ),
			'description' => esc_html__( 'Display your posts as a grid (4 columns) in the fullwidth section.', 'pixwell' ),
			'section'     => 'fullwidth',
			'img'         => get_theme_file_uri( 'assets/images/fw-grid-2.png' ),
			'inputs'      => array(
				array(
					'type'        => 'category',
					'tab'         => 'filter',
					'title'       => esc_html__( 'Category Filter', 'pixwell' ),
					'description' => esc_html__( 'Select category filter for this block.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'type'        => 'categories',
					'tab'         => 'filter',
					'title'       => esc_html__( 'Categories Filter', 'pixwell' ),
					'description' => esc_html__( 'Select categories filter for this block. This option will override category filter option.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'tags',
					'type'        => 'text',
					'tab'         => 'filter',
					'title'       => esc_html__( 'Tags Slug Filter', 'pixwell' ),
					'description' => esc_html__( 'Filter posts by tags slug, separated by commas (for example: tagslug1,tagslug2,tagslug3).', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'tag_not_in',
					'type'        => 'text',
					'tab'         => 'filter',
					'title'       => esc_html__( 'Exclude Tags Slug', 'pixwell' ),
					'description' => esc_html__( 'Exclude some tags slug from this block, separated by commas (for example: tagslug1,tagslug2,tagslug3).', 'pixwell' ),
					'default'     => ''
				),
				array(
					'type'        => 'format',
					'tab'         => 'filter',
					'title'       => esc_html__( 'Post Format', 'pixwell' ),
					'description' => esc_html__( 'Filter posts by post format', 'pixwell' ),
					'default'     => ''
				),
				array(
					'type'        => 'author',
					'tab'         => 'filter',
					'title'       => esc_html__( 'Author Filter', 'pixwell' ),
					'description' => esc_html__( 'Filter posts by the author.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'post_not_in',
					'type'        => 'text',
					'tab'         => 'filter',
					'title'       => esc_html__( 'Exclude Post IDs', 'pixwell' ),
					'description' => esc_html__( 'Exclude some post IDs from this block, separated by commas (for example: 1,2,3).', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'post_in',
					'type'        => 'text',
					'tab'         => 'filter',
					'title'       => esc_html__( 'Post IDs Filter', 'pixwell' ),
					'description' => esc_html__( 'Filter posts by post IDs. separated by commas (for example: 1,2,3)', 'pixwell' ),
					'default'     => ''
				),
				array(
					'type'        => 'order',
					'tab'         => 'filter',
					'title'       => esc_html__( 'Sort Order', 'pixwell' ),
					'description' => esc_html__( 'Select sort order type for this block.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'posts_per_page',
					'type'        => 'number',
					'tab'         => 'filter',
					'title'       => esc_html__( 'Posts per Page', 'pixwell' ),
					'description' => esc_html__( 'Select number of posts per page (total posts to show).', 'pixwell' ),
					'default'     => 4
				),
				array(
					'name'        => 'offset',
					'type'        => 'number',
					'tab'         => 'filter',
					'title'       => esc_html__( 'Post Offset', 'pixwell' ),
					'description' => esc_html__( 'Select number of posts to pass over. Leave blank or set 0 if you want to show at the beginning.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'title',
					'type'        => 'text',
					'tab'         => 'header',
					'title'       => esc_html__( 'Block Title', 'pixwell' ),
					'description' => esc_html__( 'Input block title, Leave blank if you want to disable block header.', 'pixwell' ),
					'default'     => esc_html__( 'Latest News', 'pixwell' )
				),
				array(
					'name'        => 'viewmore_link',
					'type'        => 'text',
					'tab'         => 'header',
					'title'       => esc_html__( 'View More URL', 'pixwell' ),
					'description' => esc_html__( 'Input the block destination link, Leave blank if you want to disable clickable on the block header.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'viewmore_title',
					'type'        => 'text',
					'tab'         => 'header',
					'title'       => esc_html__( 'View More Label', 'pixwell' ),
					'description' => esc_html__( 'Input the block header tagline, this is description display below the block title.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'quick_filter',
					'type'        => 'select',
					'tab'         => 'pagination',
					'title'       => esc_html__( 'Quick Filter', 'pixwell' ),
					'description' => esc_html__( 'Select a type for quick filters for displaying in the block header.', 'pixwell' ),
					'options'     => array(
						'0'        => esc_html__( '-Disable-', 'pixwell' ),
						'category' => esc_html__( 'by Categories', 'pixwell' ),
						'tag'      => esc_html__( 'by Tags', 'pixwell' )
					),
					'default'     => 0
				),
				array(
					'name'        => 'quick_filter_ids',
					'type'        => 'text',
					'tab'         => 'pagination',
					'title'       => esc_html__( 'Filter Data', 'pixwell' ),
					'description' => esc_html__( 'Input the IDs for your quick filters, depending on the type you choose: Category Ids or Tags. separated by commas (for example: 1,2,3)', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'quick_filter_label',
					'type'        => 'text',
					'tab'         => 'pagination',
					'title'       => esc_html__( 'Filter Default Label', 'pixwell' ),
					'description' => esc_html__( 'Input text for default filter label.', 'pixwell' ),
					'default'     => esc_html__( 'All', 'pixwell' )
				),
				array(
					'name'        => 'pagination',
					'type'        => 'select',
					'tab'         => 'pagination',
					'title'       => esc_html__( 'Pagination', 'pixwell' ),
					'description' => esc_html__( 'Select ajax pagination for this block, default is disable.', 'pixwell' ),
					'options'     => array(
						'0'               => esc_html__( '-Disable-', 'pixwell' ),
						'next_prev'       => esc_html__( 'Next Prev', 'pixwell' ),
						'loadmore'        => esc_html__( 'Load More', 'pixwell' ),
						'infinite_scroll' => esc_html__( 'infinite Scroll', 'pixwell' )
					),
					'default'     => 0
				),
				array(
					'name'        => 'margin',
					'type'        => 'dimension',
					'tab'         => 'design',
					'title'       => esc_html__( 'Margin', 'pixwell' ),
					'description' => esc_html__( 'Select margin top and bottom values (in px) for this block, default is 50px', 'pixwell' ),
					'default'     => array(
						'top'    => 0,
						'bottom' => 50
					),
				),
				array(
					'name'        => 'mobile_margin',
					'type'        => 'dimension',
					'tab'         => 'design',
					'title'       => esc_html__( 'Mobile - Margin', 'pixwell' ),
					'description' => esc_html__( 'Select margin top and bottom values (in px) for this block in mobile devices, default is 35px', 'pixwell' ),
					'default'     => array(
						'top'    => 0,
						'bottom' => 35
					),
				),
				array(
					'name'        => 'header_color',
					'type'        => 'text',
					'tab'         => 'design',
					'title'       => esc_html__( 'Header Style Color', 'pixwell' ),
					'description' => esc_html__( 'Input hex color value (ie: #ff8763) for the block header title.', 'pixwell' )
				),
				array(
					'name'        => 'text_style',
					'type'        => 'select',
					'tab'         => 'design',
					'title'       => esc_html__( 'Text Style', 'pixwell' ),
					'description' => esc_html__( 'Select block text style, Select light if you have a dark background.', 'pixwell' ),
					'options'     => array(
						'0'     => esc_html__( '-Dark-', 'pixwell' ),
						'light' => esc_html__( 'Light', 'pixwell' )
					),
					'default'     => 0
				),
				array(
					'name'        => 'infeed_ad',
					'type'        => 'select',
					'tab'         => 'advert',
					'title'       => esc_html__( 'In-feed Advertising', 'pixwell' ),
					'description' => esc_html__( 'Select in-feed adverts for this block. This feature is not compatible with ajax pagination in some cases.', 'pixwell' ),
					'options'     => array(
						'0'     => esc_html__( '-Disable-', 'pixwell' ),
						'code'  => esc_html__( 'Script Code', 'pixwell' ),
						'image' => esc_html__( 'Custom Image', 'pixwell' )
					),
					'default'     => 0
				),
				array(
					'name'        => 'html_advert',
					'type'        => 'textarea',
					'tab'         => 'advert',
					'title'       => esc_html__( 'In-feed Ad Code', 'pixwell' ),
					'description' => esc_html__( 'Input your in-feed Adsense or your script code.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'ad_attachment',
					'type'        => 'text',
					'tab'         => 'advert',
					'title'       => esc_html__( 'In-feed Ad Image', 'pixwell' ),
					'description' => esc_html__( 'Input your advert image URL (attachment URL).', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'ad_destination',
					'type'        => 'text',
					'tab'         => 'advert',
					'title'       => esc_html__( 'In-feed Ad Destination', 'pixwell' ),
					'description' => esc_html__( 'Input destination URL for your advert.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'infeed_description',
					'type'        => 'text',
					'tab'         => 'advert',
					'title'       => esc_html__( 'In-feed Ad Description', 'pixwell' ),
					'description' => esc_html__( 'Small description to show at top of adverts.', 'pixwell' ),
					'default'     => esc_html__( '- Advertisement -', 'pixwell' )
				),
				array(
					'name'        => 'ad_pos_1',
					'type'        => 'text',
					'tab'         => 'advert',
					'title'       => esc_html__( 'Position 1', 'pixwell' ),
					'description' => esc_html__( 'Display advert before the article X (smaller than posts per page value), allow integer number, leave blank if you want to disable this position.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'ad_pos_2',
					'type'        => 'text',
					'tab'         => 'advert',
					'title'       => esc_html__( 'Position 2', 'pixwell' ),
					'description' => esc_html__( 'Display advert before the article X (smaller than posts per page value), allow integer number, leave blank if you want to disable this position.', 'pixwell' ),
					'default'     => ''
				)
			)
		);

		return $blocks;
	}
}

add_filter( 'rbc_add_block', 'pixwell_register_fw_grid_2', 21 );

