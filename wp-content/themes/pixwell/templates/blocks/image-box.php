<?php
/**
 * @param $attrs
 * about block
 */
if ( ! function_exists( 'pixwell_rbc_image_box' ) ) {
	function pixwell_rbc_image_box( $attrs ) {
		$settings = shortcode_atts( array(
			'uuid'            => '',
			'title'           => '',
			'c1_image'        => '',
			'c1_title'        => '',
			'c1_link'         => '',
			'c1_btn'          => '',
			'html_c1_desc'    => '',
			'c2_image'        => '',
			'c2_title'        => '',
			'c2_link'         => '',
			'c2_btn'          => '',
			'html_c2_desc'    => '',
			'c3_image'        => '',
			'c3_title'        => '',
			'c3_link'         => '',
			'c3_btn'          => '',
			'html_c3_desc'    => '',
			'content_align'   => '',
			'text_style'      => '',
			'target'          => '',
			'image_width'     => '',
			'elementor_block' => '',
		), $attrs );

		if ( function_exists( 'pixwell_decode_shortcode' ) ) {
			if ( empty( $settings['elementor_block'] ) ) {
				$settings['html_c1_desc'] = pixwell_decode_shortcode( $settings['html_c1_desc'] );
				$settings['html_c2_desc'] = pixwell_decode_shortcode( $settings['html_c2_desc'] );
				$settings['html_c3_desc'] = pixwell_decode_shortcode( $settings['html_c3_desc'] );
			}
		}

		$settings['id']      = $settings['uuid'];
		$settings['classes'] = 'fw-block block-ibox none-margin';
		if ( ! empty( $settings['content_align'] ) && 'center' == $settings['content_align'] ) {
			$settings['classes'] .= ' is-center';
		} elseif ( ! empty( $settings['content_align'] ) && 'right' == $settings['content_align'] ) {
			$settings['classes'] .= ' is-right';
		}
		$settings['block_tag'] = 'div';

		ob_start();
		pixwell_block_open( $settings );
		pixwell_block_content_open( $settings );
		pixwell_block_header( $settings ); ?>
		<div class="ibox-wrap rb-row rb-n20-gutter">
			<div class="ibox-outer rb-col-t4 rb-col-m12 rb-p20-gutter">
				<?php pixwell_render_image_box( $settings['c1_image'], $settings['c1_title'], $settings['c1_link'], $settings['c1_btn'], $settings['html_c1_desc'], $settings['target'], $settings['image_width'] ); ?>
			</div>
			<div class="ibox-outer rb-col-t4 rb-col-m12 rb-p20-gutter">
				<?php pixwell_render_image_box( $settings['c2_image'], $settings['c2_title'], $settings['c2_link'], $settings['c2_btn'], $settings['html_c2_desc'], $settings['target'], $settings['image_width'] ); ?>
			</div>
			<div class="ibox-outer rb-col-t4 rb-col-m12 rb-p20-gutter">
				<?php pixwell_render_image_box( $settings['c3_image'], $settings['c3_title'], $settings['c3_link'], $settings['c3_btn'], $settings['html_c3_desc'], $settings['target'], $settings['image_width'] ); ?>
			</div>
		</div>
		<?php
		pixwell_block_content_close();
		pixwell_block_close( $settings );

		return ob_get_clean();
	}
}

/**
 * @param string $image
 * @param string $title
 * @param string $link
 * @param string $button
 * @param string $description
 * @param string $target
 * render image box
 */
if ( ! function_exists( 'pixwell_render_image_box' ) ) {
	function pixwell_render_image_box( $image = '', $title = '', $link = '', $button = '', $description = '', $target = '', $image_width ='' ) {

		$lazyload = pixwell_get_option( 'lazy_load' );

		if ( ! empty( $target ) ) {
			$target = '_blank';
		} else {
			$target = '_self';
		} ?>
		<div class="ibox">
			<?php if ( ! empty( $image ) ) : ?>
				<div class="ibox-thumb">
					<?php if ( ! empty( $link ) ) : ?>
						<a href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $target ); ?>">
					<?php endif; ?>
						<?php if ( ! empty( $image_width ) ) :
							if ( ! empty( $lazyload ) ) : ?>
								<span class="rb-iwrap"><img class="rb-lazyload" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" data-src="<?php echo esc_url( $image ); ?>" alt="<?php esc_attr( $title ); ?>"/></span>
							<?php else : ?>
								<span class="rb-iwrap"><img src="<?php echo esc_url( $image ); ?>" alt="<?php esc_attr( $title ); ?>"/></span>
							<?php endif;
						else : ?>
							<img class="ibox-img" src="<?php echo esc_url( $image ); ?>" alt="<?php esc_attr( $title ); ?>"/>
						<?php endif; ?>
					<?php if ( ! empty( $link ) ) : ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<h3 class="ibox-title">
				<?php if ( ! empty( $link ) ) : ?>
					<a class="p-url" href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $target ); ?>"><?php echo esc_html( $title ); ?></a>
				<?php else : ?>
					<?php echo esc_html( $title ); ?>
				<?php endif; ?>
			</h3>
			<p class="ibox-desc rb-sdesc"><?php echo do_shortcode( $description ) ?></p>
			<?php if ( ! empty( $button ) && ! empty( $link ) ) : ?>
				<div class="ibox-link">
					<a class="btn p-link" href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $target ); ?>"><span><?php echo esc_html( $button ); ?></span><i class="rbi rbi-arrow-right"></i></a>
				</div>
			<?php endif; ?>
		</div>
	<?php
	}
}


/**
 * @param $blocks
 *
 * @return array
 * register block settings
 */
if ( ! function_exists( 'pixwell_register_image_box' ) ) {
	function pixwell_register_image_box( $blocks ) {

		if ( ! is_array( $blocks ) ) {
			$blocks = array();
		}

		$blocks[] = array(
			'name'        => 'image_box',
			'title'       => esc_html__( 'Images Box', 'pixwell' ),
			'description' => esc_html__( 'Display images box with 3 columns in fullwidth section.', 'pixwell' ),
			'section'     => array( 'fullwidth' ),
			'img'         => get_theme_file_uri( 'assets/images/image-box.png' ),
			'inputs'      => array(
				array(
					'name'        => 'c1_image',
					'type'        => 'text',
					'tab'         => 'general',
					'title'       => esc_html__( 'Column 1 - Image', 'pixwell' ),
					'description' => esc_html__( 'Input image attachment URL for this column.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'c1_title',
					'type'        => 'text',
					'tab'         => 'general',
					'title'       => esc_html__( 'Column 1 - Title', 'pixwell' ),
					'description' => esc_html__( 'Input a title for column for this column.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'c1_link',
					'type'        => 'text',
					'tab'         => 'general',
					'title'       => esc_html__( 'Column 1 - Link', 'pixwell' ),
					'description' => esc_html__( 'Input a destination link for this column.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'c1_btn',
					'type'        => 'text',
					'tab'         => 'general',
					'title'       => esc_html__( 'Column 1 - Button Text', 'pixwell' ),
					'description' => esc_html__( 'Input a button text for this column.', 'pixwell' ),
					'default'     => esc_html__( 'Learn More', 'pixwell' ),
				),
				array(
					'name'        => 'html_c1_desc',
					'type'        => 'textarea',
					'tab'         => 'general',
					'title'       => esc_html__( 'Column 1 - Description', 'pixwell' ),
					'description' => esc_html__( 'Input description (allow raw HTML) for for this column.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'c2_image',
					'type'        => 'text',
					'tab'         => 'general',
					'title'       => esc_html__( 'Column 2 - Image', 'pixwell' ),
					'description' => esc_html__( 'Input image attachment URL for this column.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'c2_title',
					'type'        => 'text',
					'tab'         => 'general',
					'title'       => esc_html__( 'Column 2 - Title', 'pixwell' ),
					'description' => esc_html__( 'Input a title for column for this column.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'c2_link',
					'type'        => 'text',
					'tab'         => 'general',
					'title'       => esc_html__( 'Column 2 - Link', 'pixwell' ),
					'description' => esc_html__( 'Input a destination link for this column.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'c2_btn',
					'type'        => 'text',
					'tab'         => 'general',
					'title'       => esc_html__( 'Column 2 - Button Text', 'pixwell' ),
					'description' => esc_html__( 'Input a button text for this column.', 'pixwell' ),
					'default'     => esc_html__( 'Learn More', 'pixwell' ),
				),
				array(
					'name'        => 'html_c2_desc',
					'type'        => 'textarea',
					'tab'         => 'general',
					'title'       => esc_html__( 'Column 2 - Description', 'pixwell' ),
					'description' => esc_html__( 'Input description (allow raw HTML) for for this column.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'c3_image',
					'type'        => 'text',
					'tab'         => 'general',
					'title'       => esc_html__( 'Column 3 - Image', 'pixwell' ),
					'description' => esc_html__( 'Input image attachment URL for this column.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'c3_title',
					'type'        => 'text',
					'tab'         => 'general',
					'title'       => esc_html__( 'Column 3 - Title', 'pixwell' ),
					'description' => esc_html__( 'Input a title for column for this column.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'c3_link',
					'type'        => 'text',
					'tab'         => 'general',
					'title'       => esc_html__( 'Column 3 - Link', 'pixwell' ),
					'description' => esc_html__( 'Input a destination link for this column.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'c3_btn',
					'type'        => 'text',
					'tab'         => 'general',
					'title'       => esc_html__( 'Column 3 - Button Text', 'pixwell' ),
					'description' => esc_html__( 'Input a button text for this column.', 'pixwell' ),
					'default'     => esc_html__( 'Learn More', 'pixwell' ),
				),
				array(
					'name'        => 'html_c3_desc',
					'type'        => 'textarea',
					'tab'         => 'general',
					'title'       => esc_html__( 'Column 3 - Description', 'pixwell' ),
					'description' => esc_html__( 'Input description (allow raw HTML) for for this column.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'title',
					'type'        => 'text',
					'tab'         => 'header',
					'title'       => esc_html__( 'Block Title', 'pixwell' ),
					'description' => esc_html__( 'Input block title, Leave blank if you want to disable block header.', 'pixwell' ),
					'default'     => '',
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
					'name'        => 'content_align',
					'type'        => 'select',
					'tab'         => 'design',
					'title'       => esc_html__( 'Content Align', 'pixwell' ),
					'description' => esc_html__( 'Select content align for this block.', 'pixwell' ),
					'options'     => array(
						'0'      => esc_html__( '- Left -', 'pixwell' ),
						'center' => esc_html__( 'Center', 'pixwell' ),
						'right'  => esc_html__( 'Right', 'pixwell' ),
					),
					'default'     => 0
				),
				array(
					'name'        => 'image_width',
					'type'        => 'select',
					'tab'         => 'design',
					'title'       => esc_html__( 'Image Width', 'pixwell' ),
					'description' => esc_html__( 'Select image width for this block.', 'pixwell' ),
					'options'     => array(
						'0' => esc_html__( '-Auto-', 'pixwell' ),
						'1' => esc_html__( 'Full Width', 'pixwell' )
					),
					'default'     => 0
				),
				array(
					'name'        => 'target',
					'type'        => 'select',
					'tab'         => 'design',
					'title'       => esc_html__( 'Link Target', 'pixwell' ),
					'description' => esc_html__( 'Select target type for links in this block.', 'pixwell' ),
					'options'     => array(
						'0' => esc_html__( '- Self -', 'pixwell' ),
						'1' => esc_html__( 'Blank', 'pixwell' )
					),
					'default'     => 0
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
				)
			),
		);

		return $blocks;
	}
}

add_filter( 'rbc_add_block', 'pixwell_register_image_box', 2021 );