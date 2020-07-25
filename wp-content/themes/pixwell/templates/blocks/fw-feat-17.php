<?php
/**
 * @param $attrs
 * fullwidth feat 17
 */
if ( ! function_exists( 'pixwell_rbc_fw_feat_17' ) ) {
	function pixwell_rbc_fw_feat_17( $attrs ) {
		$settings = shortcode_atts( array(
			'uuid'               => '',
			'category'           => '',
			'categories'         => '',
			'format'             => '',
			'tags'               => '',
			'tag_not_in'         => '',
			'author'             => '',
			'post_not_in'        => '',
			'post_in'            => '',
			'order'              => '',
			'offset'             => '',
			'sub_category'       => '',
			'categories_sub'     => '',
			'sub_format'         => '',
			'sub_tags'           => '',
			'sub_tag_not_in'     => '',
			'sub_author'         => '',
			'sub_post_not_in'    => '',
			'sub_post_in'        => '',
			'sub_order'          => '',
			'sub_posts_per_page' => '5',
			'sub_offset'         => '',
			'title'              => '',
			'sub_title'          => '',
			'viewmore_title'     => '',
			'viewmore_link'      => '',
			'columns'            => true
		), $attrs );

		$settings['classes']         = 'fw-block fw-feat-17 none-margin';
		$settings['posts_per_page']  = 3;
		$settings['no_found_rows']   = true;
		$settings['content_classes'] = 'rb-n10-all';

		$query_data = pixwell_query( $settings );

		ob_start();

		pixwell_block_open( $settings, $query_data );
		pixwell_block_header( $settings );

		if ( ! $query_data->have_posts() || $query_data->post_count < 2 ) {
			pixwell_not_enough( 3 );
		} else {
			pixwell_block_content_open( $settings );
			$counter = 1;
			echo '<div class="sleft rb-col-d8 rb-col-m12">';
			while ( $query_data->have_posts() ) :
				$query_data->the_post();
				if ( 1 == $counter ) {
					echo '<div class="p-outer rb-p10-all rb-col-m12">';
					pixwell_post_overlay_1( $settings );
					echo '</div>';
				} elseif ( 2 == $counter ) {
					echo '<div class="p-outer rb-p10-all rb-col-d6 rb-col-t12">';
					$settings['h_tag']   = 'h4';
					$settings['h_class'] = '';
					pixwell_post_list_4( $settings );
					echo '</div>';
				} else {
					echo '<div class="p-outer rb-p10-all rb-col-d6 rb-col-t12">';
					pixwell_post_list_4( $settings );
					echo '</div>';
				}
				$counter ++;
				if ( $counter > 3 ) {
					break;
				}

			endwhile;
			echo '</div>';
			wp_reset_postdata();

			unset( $settings['h_tag'] );
			unset( $settings['h_class'] );

			/** sub section  */
			$sub_params = array(
				'category'       => $settings['sub_category'],
				'categories'     => $settings['categories_sub'],
				'format'         => $settings['sub_format'],
				'tags'           => $settings['sub_tags'],
				'tag_not_in'     => $settings['sub_tag_not_in'],
				'author'         => $settings['sub_author'],
				'post_not_in'    => $settings['sub_post_not_in'],
				'post_in'        => $settings['sub_post_in'],
				'order'          => $settings['sub_order'],
				'posts_per_page' => $settings['sub_posts_per_page'],
				'offset'         => $settings['sub_offset']
			);

			echo '<div class="sright rb-col-d4 rb-col-m12">';
			echo '<div class="sub-inner rb-p10-all">';
			if ( ! empty( $settings['sub_title'] ) ) {
				echo '<div class="sub-header"><h3 class="widget-title">' . esc_html( $settings['sub_title'] ) . '</h3></div>';
			}

			$query_data = pixwell_query( $sub_params );
			if ( $query_data->have_posts() ) {
				while ( $query_data->have_posts() ) :
					$query_data->the_post();
					pixwell_post_list_8( $settings );
				endwhile;
				wp_reset_postdata();
			}

			echo '</div>';
			echo '</div>';

			pixwell_block_content_close();
		}

		pixwell_block_close();

		return ob_get_clean();
	}
}


/**
 * @param $blocks
 *
 * @return array
 * register block settings
 */
if ( ! function_exists( 'pixwell_register_fw_feat_17' ) ) {
	function pixwell_register_fw_feat_17( $blocks ) {

		if ( ! is_array( $blocks ) ) {
			$blocks = array();
		}

		$blocks[] = array(
			'name'        => 'fw_feat_17',
			'title'       => esc_html__( 'Marketing Grid', 'pixwell' ),
			'tagline'     => esc_html__( 'Wrapper', 'pixwell' ),
			'description' => esc_html__( 'Display your posts as a featured grid in the fullwidth section.', 'pixwell' ),
			'section'     => 'fullwidth',
			'img'         => get_theme_file_uri( 'assets/images/fw-feat-17.png' ),
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
					'name'        => 'offset',
					'type'        => 'number',
					'tab'         => 'filter',
					'title'       => esc_html__( 'Post Offset', 'pixwell' ),
					'description' => esc_html__( 'Select number of posts to pass over. Leave blank or set 0 if you want to show at the beginning.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'sub_category',
					'type'        => 'category',
					'tab'         => 'subsection',
					'title'       => esc_html__( 'Sub - Category Filter', 'pixwell' ),
					'description' => esc_html__( 'Select category filter for this block.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'categories_sub',
					'type'        => 'categories',
					'tab'         => 'subsection',
					'title'       => esc_html__( 'Sub - Categories Filter', 'pixwell' ),
					'description' => esc_html__( 'Select categories filter for this block. This option will override category filter option.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'sub_tags',
					'type'        => 'text',
					'tab'         => 'subsection',
					'title'       => esc_html__( 'Sub - Tags Slug Filter', 'pixwell' ),
					'description' => esc_html__( 'Filter posts by tags slug, separated by commas (for example: tagslug1,tagslug2,tagslug3).', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'sub_tag_not_in',
					'type'        => 'text',
					'tab'         => 'subsection',
					'title'       => esc_html__( 'Sub - Exclude Tags Slug', 'pixwell' ),
					'description' => esc_html__( 'Exclude some tags slug from this block, separated by commas (for example: tagslug1,tagslug2,tagslug3).', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'sub_format',
					'type'        => 'format',
					'tab'         => 'subsection',
					'title'       => esc_html__( 'Sub - Post Format', 'pixwell' ),
					'description' => esc_html__( 'Filter posts by post format', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'sub_author',
					'type'        => 'author',
					'tab'         => 'subsection',
					'title'       => esc_html__( 'Sub - Author Filter', 'pixwell' ),
					'description' => esc_html__( 'Filter posts by the author.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'sub_post_not_in',
					'type'        => 'text',
					'tab'         => 'subsection',
					'title'       => esc_html__( 'Sub - Exclude Post IDs', 'pixwell' ),
					'description' => esc_html__( 'Exclude some post IDs from this block, separated by commas (for example: 1,2,3).', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'sub_post_in',
					'type'        => 'text',
					'tab'         => 'subsection',
					'title'       => esc_html__( 'Sub - Post IDs Filter', 'pixwell' ),
					'description' => esc_html__( 'Filter posts by post IDs. separated by commas (for example: 1,2,3)', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'sub_order',
					'type'        => 'order',
					'tab'         => 'subsection',
					'title'       => esc_html__( 'Sub - Sort Order', 'pixwell' ),
					'description' => esc_html__( 'Select sort order type for this block.', 'pixwell' ),
					'default'     => 'popular'
				),
				array(
					'name'        => 'sub_offset',
					'type'        => 'number',
					'tab'         => 'subsection',
					'title'       => esc_html__( 'Sub - Post Offset', 'pixwell' ),
					'description' => esc_html__( 'Select number of posts to pass over. Leave blank or set 0 if you want to show at the beginning.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'sub_posts_per_page',
					'type'        => 'number',
					'tab'         => 'subsection',
					'title'       => esc_html__( 'Sub - Posts per Page', 'pixwell' ),
					'description' => esc_html__( 'Select number of posts per page (total posts to show).', 'pixwell' ),
					'default'     => 5
				),
				array(
					'name'        => 'title',
					'type'        => 'text',
					'tab'         => 'header',
					'title'       => esc_html__( 'Block Title', 'pixwell' ),
					'description' => esc_html__( 'Input block title, Leave blank if you want to disable block header.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'sub_title',
					'type'        => 'text',
					'tab'         => 'header',
					'title'       => esc_html__( 'Sub Section Title', 'pixwell' ),
					'description' => esc_html__( 'Input the sub section tile, Leave blank if you want to disable block header.', 'pixwell' ),
					'default'     => esc_html__( 'Popular News', 'pixwell' )
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
					)
				),
				array(
					'name'        => 'header_color',
					'type'        => 'text',
					'tab'         => 'design',
					'title'       => esc_html__( 'Header Style Color', 'pixwell' ),
					'description' => esc_html__( 'Input hex color value (ie: #ff8763) for the block header title.', 'pixwell' )
				),
				array(
					'name'        => 'sub_header_color',
					'type'        => 'text',
					'tab'         => 'design',
					'title'       => esc_html__( 'Sub Section Title Color', 'pixwell' ),
					'description' => esc_html__( 'Input hex color value (ie: #ff8763) for the sub section title.', 'pixwell' )
				),
			)
		);

		return $blocks;
	}
}

add_filter( 'rbc_add_block', 'pixwell_register_fw_feat_17', 19 );