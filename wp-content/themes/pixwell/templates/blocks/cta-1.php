<?php
/**
 * @param $attrs
 * about block
 */
if ( ! function_exists( 'pixwell_rbc_cta_1' ) ) {
	function pixwell_rbc_cta_1( $attrs ) {
		$settings = shortcode_atts( array(
			'uuid'             => '',
			'cta_image'        => '',
			'html_cta_tagline' => '',
			'html_cta_title'   => '',
			'html_cta_desc'    => '',
			'btn_1'            => '',
			'btn_1_url'        => '',
			'btn_1_icon'       => '',
			'btn_2'            => '',
			'btn_2_url'        => '',
			'btn_2_icon'       => '',
			'tagline_tag'      => 'h6',
			'title_tag'        => 'h2',
			'btn_1_style'      => 'border',
			'btn_2_style'      => 'bg',
			'target'           => '',
			'text_style'       => '',
			'content_align'    => '',
			'icon_position'    => '',
			'elementor_block'  => ''
		), $attrs );

		if ( function_exists( 'pixwell_decode_shortcode' ) ) {
			if ( empty( $settings['elementor_block'] ) ) {
				$settings['html_cta_tagline'] = pixwell_decode_shortcode( $settings['html_cta_tagline'] );
				$settings['html_cta_title']   = pixwell_decode_shortcode( $settings['html_cta_title'] );
				$settings['html_cta_desc']    = pixwell_decode_shortcode( $settings['html_cta_desc'] );
			}
		} else {
			$settings['html_cta_title'] = esc_html__( 'requesting Pixwell Core plugin to work', 'pixwell' );
		}

		if ( empty( $settings['target'] ) ) {
			$settings['target'] = 'self';
		} else {
			$settings['target'] = '_blank';
		}

		$settings['id']      = $settings['uuid'];
		$settings['classes'] = 'fw-block block-cta-1 none-margin';

		if ( ! empty( $settings['content_align'] ) && 'left' == $settings['content_align'] ) {
			$settings['classes'] .= ' is-left';
		}

		if ( ! empty( $settings['icon_position']) ) {
			$settings['classes'] .= ' icon-before';
		}

		$settings['block_tag'] = 'div';

		ob_start();
		pixwell_block_open( $settings );  ?>
		<div class="rbc-container cta-inner rb-p20-gutter">

			<?php if ( ! empty( $settings['cta_image'] ) ) : ?>
				<div class="cta-image">
					<img src="<?php echo esc_url( $settings['cta_image'] ); ?>" alt="<?php echo wp_strip_all_tags( $settings['html_cta_title'] ); ?>"/>
				</div>
			<?php endif;
			if ( ! empty( $settings['html_cta_tagline'] ) ) : ?>
				<<?php echo esc_attr( $settings['tagline_tag'] ); ?> class="cta-tagline"><?php echo wp_kses_post( $settings['html_cta_tagline'] ); ?></<?php echo esc_attr( $settings['tagline_tag'] ); ?>>
			<?php endif;
			if ( ! empty( $settings['html_cta_title'] ) ) : ?>
				<<?php echo esc_attr( $settings['title_tag'] ); ?> class="cta-title"><?php echo wp_kses_post( $settings['html_cta_title'] ); ?></<?php echo esc_attr( $settings['title_tag'] ); ?>>
			<?php endif;
			if ( ! empty( $settings['html_cta_desc'] ) ) : ?>
				<p class="cta-description"><?php echo wp_kses_post( $settings['html_cta_desc'] ); ?></p>
			<?php endif; ?>
			<div class="cta-btn-wrap">
				<?php if ( ! empty( $settings['btn_1_url'] ) ) : ?>
					<a href="<?php echo esc_url( $settings['btn_1_url'] ); ?>" class="cta-btn cta-btn-1 is-<?php echo esc_attr( $settings['btn_1_style'] ); ?>" target="<?php echo esc_attr( $settings['target'] ); ?>"><span><?php echo esc_html( $settings['btn_1'] ); ?></span><?php if ( ! empty( $settings['btn_1_icon'] ) ): ?><span class="rbi <?php echo esc_attr($settings['btn_1_icon']); ?>"></span><?php endif; ?></a>
				<?php endif;
				if ( ! empty( $settings['btn_2_url'] ) ) : ?>
					<a href="<?php echo esc_url( $settings['btn_2_url'] ); ?>" class="cta-btn cta-btn-2 is-<?php echo esc_attr( $settings['btn_2_style'] ); ?>" target="<?php echo esc_attr( $settings['target'] ); ?>"><span><?php echo esc_html( $settings['btn_2'] ); ?></span><?php if ( ! empty( $settings['btn_2_icon'] ) ): ?><span class="rbi <?php echo esc_attr($settings['btn_2_icon']); ?>"></span><?php endif; ?></a>
				<?php endif; ?>
			</div>
		</div>
		<?php
		pixwell_block_close( $settings );
		return ob_get_clean();
	}
}


/**
 * @param $blocks
 *
 * @return array
 * register block settings
 */
if ( ! function_exists( 'pixwell_register_cta_1' ) ) {
	function pixwell_register_cta_1( $blocks ) {

		if ( ! is_array( $blocks ) ) {
			$blocks = array();
		}

		$blocks[] = array(
			'name'        => 'cta_1',
			'title'       => esc_html__( 'Call to Action', 'pixwell' ),
			'description' => esc_html__( 'Display call to action block in the fullwidth and content sections.', 'pixwell' ),
			'section'     => array( 'fullwidth', 'content' ),
			'img'         => get_theme_file_uri( 'assets/images/cta-1.png' ),
			'inputs'      => array(
				array(
					'name'        => 'cta_image',
					'type'        => 'text',
					'tab'         => 'content',
					'title'       => esc_html__( 'Top Image (Attachment URL)', 'pixwell' ),
					'description' => esc_html__( 'Input image attachment to display at the top of the block. Leave blank if you want to disable it.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'html_cta_tagline',
					'type'        => 'textarea',
					'tab'         => 'content',
					'title'       => esc_html__( 'Tagline', 'pixwell' ),
					'description' => esc_html__( 'Input the call to action tagline for this block. (Allow Raw HTML)', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'html_cta_title',
					'type'        => 'textarea',
					'tab'         => 'content',
					'title'       => esc_html__( 'Title', 'pixwell' ),
					'description' => esc_html__( 'Input call to action title for this bock (Allow Raw HTML).', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'html_cta_desc',
					'type'        => 'textarea',
					'tab'         => 'content',
					'title'       => esc_html__( 'About Description', 'pixwell' ),
					'description' => esc_html__( 'Input description for this block (Allow Raw HTML).', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'btn_1',
					'type'        => 'text',
					'tab'         => 'content',
					'title'       => esc_html__( 'Button 1 - Text', 'pixwell' ),
					'description' => esc_html__( 'Input label for button 1', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'btn_1_url',
					'type'        => 'text',
					'tab'         => 'content',
					'title'       => esc_html__( 'Button 1 - URL', 'pixwell' ),
					'description' => esc_html__( 'Input destination URL for button 1', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'btn_1_icon',
					'type'        => 'text',
					'tab'         => 'content',
					'title'       => esc_html__( 'Button 1 - Icon', 'pixwell' ),
					'description' => esc_html__( 'Input ruby icon classname, ie: rbi-arrow-right (icons.themeruby.com/pixwell).', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'btn_2',
					'type'        => 'text',
					'tab'         => 'content',
					'title'       => esc_html__( 'Button 2 - Text', 'pixwell' ),
					'description' => esc_html__( 'Input label for button 2', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'btn_2_url',
					'type'        => 'text',
					'tab'         => 'content',
					'title'       => esc_html__( 'Button 2 - URL', 'pixwell' ),
					'description' => esc_html__( 'Input destination URL for button 2', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'btn_2_icon',
					'type'        => 'text',
					'tab'         => 'content',
					'title'       => esc_html__( 'Button 2 - Icon', 'pixwell' ),
					'description' => esc_html__( 'Input ruby icon classname, ie: rbi-arrow-right (icons.themeruby.com/pixwell).', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'tagline_tag',
					'type'        => 'select',
					'tab'         => 'design',
					'title'       => esc_html__( 'Tagline Tag', 'pixwell' ),
					'description' => esc_html__( 'Select html tag for this tagline.', 'pixwell' ),
					'options'     => array(
						'h6' => esc_html__( '- h6 -', 'pixwell' ),
						'h1' => esc_html__( 'h1', 'pixwell' ),
						'h2' => esc_html__( 'h2', 'pixwell' ),
						'h3' => esc_html__( 'h3', 'pixwell' ),
						'h4' => esc_html__( 'h4', 'pixwell' ),
						'h5' => esc_html__( 'h5', 'pixwell' ),
					),
					'default'     => 'h6'
				),
				array(
					'name'        => 'title_tag',
					'type'        => 'select',
					'tab'         => 'design',
					'title'       => esc_html__( 'Title Tag', 'pixwell' ),
					'description' => esc_html__( 'Select html tag for the title.', 'pixwell' ),
					'options'     => array(
						'h2' => esc_html__( '- h2 -', 'pixwell' ),
						'h1' => esc_html__( 'h1', 'pixwell' ),
						'h3' => esc_html__( 'h3', 'pixwell' ),
						'h4' => esc_html__( 'h4', 'pixwell' ),
						'h5' => esc_html__( 'h5', 'pixwell' ),
						'h6' => esc_html__( 'h6', 'pixwell' ),
					),
					'default'     => 'h2'
				),
				array(
					'name'        => 'btn_1_style',
					'type'        => 'select',
					'tab'         => 'design',
					'title'       => esc_html__( 'Button 1 - Style', 'pixwell' ),
					'description' => esc_html__( 'Select style for the button 1.', 'pixwell' ),
					'options'     => array(
						'border' => esc_html__( '- Border -', 'pixwell' ),
						'bg'     => esc_html__( 'Background', 'pixwell' ),

					),
					'default'     => 'border'
				),
				array(
					'name'        => 'btn_1_color',
					'type'        => 'text',
					'tab'         => 'design',
					'title'       => esc_html__( 'Button 1 - Text Color', 'pixwell' ),
					'description' => esc_html__( 'Input text color value for this button.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'btn_1_bg',
					'type'        => 'text',
					'tab'         => 'design',
					'title'       => esc_html__( 'Button 1 - Background Color', 'pixwell' ),
					'description' => esc_html__( 'Input background color value for this button.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'btn_1_hover_color',
					'type'        => 'text',
					'tab'         => 'design',
					'title'       => esc_html__( 'Button 1 - Hover Text Color', 'pixwell' ),
					'description' => esc_html__( 'Input hover text color value for this button.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'btn_1_hover_bg',
					'type'        => 'text',
					'tab'         => 'design',
					'title'       => esc_html__( 'Button 1 - Hover Background Color', 'pixwell' ),
					'description' => esc_html__( 'Input hover background color value for this button.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'btn_2_style',
					'type'        => 'select',
					'tab'         => 'design',
					'title'       => esc_html__( 'Button 2 - Style', 'pixwell' ),
					'description' => esc_html__( 'Select style for the button 2.', 'pixwell' ),
					'options'     => array(
						'border' => esc_html__( 'Border', 'pixwell' ),
						'bg'     => esc_html__( '- Background -', 'pixwell' ),
					),
					'default'     => 'bg'
				),
				array(
					'name'        => 'btn_2_color',
					'type'        => 'text',
					'tab'         => 'design',
					'title'       => esc_html__( 'Button 2 - Text Color', 'pixwell' ),
					'description' => esc_html__( 'Input text color value for this button.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'btn_2_bg',
					'type'        => 'text',
					'tab'         => 'design',
					'title'       => esc_html__( 'Button 2 - Background Color', 'pixwell' ),
					'description' => esc_html__( 'Input background color value for this button.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'btn_2_hover_color',
					'type'        => 'text',
					'tab'         => 'design',
					'title'       => esc_html__( 'Button 2 - Hover Text Color', 'pixwell' ),
					'description' => esc_html__( 'Input hover text color value for this button.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'btn_2_hover_bg',
					'type'        => 'text',
					'tab'         => 'design',
					'title'       => esc_html__( 'Button 2 - Hover Background Color', 'pixwell' ),
					'description' => esc_html__( 'Input hover background color value for this button.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'padding',
					'type'        => 'dimension',
					'tab'         => 'design',
					'title'       => esc_html__( 'Padding', 'pixwell' ),
					'description' => esc_html__( 'Select padding values (in px) for this block.', 'pixwell' ),
					'default'     => array(
						'top'    => '150',
						'right'  => '0',
						'bottom' => '150',
						'left'   => '0'
					)
				),
				array(
					'name'        => 'mobile_padding',
					'type'        => 'dimension',
					'tab'         => 'design',
					'title'       => esc_html__( 'Mobile - Padding', 'pixwell' ),
					'description' => esc_html__( 'Select padding values (in px) for this block in mobile devices', 'pixwell' ),
					'default'     => array(
						'top'    => '70',
						'right'  => '0',
						'bottom' => '70',
						'left'   => '0'
					)
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
					'name'        => 'bg_color',
					'type'        => 'color',
					'tab'         => 'design',
					'title'       => esc_html__( 'Background Color', 'pixwell' ),
					'description' => esc_html__( 'Select background color for this block.', 'pixwell' ),
					'default'     => '',
				),
				array(
					'name'        => 'bg_image',
					'type'        => 'text',
					'tab'         => 'design',
					'title'       => esc_html__( 'Background Image', 'pixwell' ),
					'description' => esc_html__( 'Input background image for this block, allow attachment image URL (.png or .jpg at the end)', 'pixwell' ),
					'default'     => '',
				),
				array(
					'name'        => 'bg_display',
					'type'        => 'select',
					'tab'         => 'design',
					'title'       => esc_html__( 'Background Image Display', 'pixwell' ),
					'description' => esc_html__( 'Select background display for this block (if you use the background image)', 'pixwell' ),
					'options'     => array(
						'cover'   => esc_html__( 'Cover', 'pixwell' ),
						'pattern' => esc_html__( 'Pattern', 'pixwell' ),
					),
					'default'     => 'cover'
				),
				array(
					'name'        => 'bg_position',
					'type'        => 'select',
					'tab'         => 'design',
					'title'       => esc_html__( 'Background Image Position', 'pixwell' ),
					'description' => esc_html__( 'Select background position for this block (if you use the background image)', 'pixwell' ),
					'options'     => array(
						'center' => esc_html__( 'Center', 'pixwell' ),
						'top'    => esc_html__( 'Top', 'pixwell' ),
						'bottom' => esc_html__( 'Bottom', 'pixwell' )
					),
					'default'     => 'center'
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
						'0'     => esc_html__( 'Dark', 'pixwell' ),
						'light' => esc_html__( '- Light -', 'pixwell' )
					),
					'default'     => 'light'
				),
				array(
					'name'        => 'content_align',
					'type'        => 'select',
					'tab'         => 'design',
					'title'       => esc_html__( 'Content Align', 'pixwell' ),
					'description' => esc_html__( 'Select align content for this block, this option will apply on the desktop and table devices.', 'pixwell' ),
					'options'     => array(
						'0'     => esc_html__( '- Center -', 'pixwell' ),
						'left' => esc_html__( 'Left', 'pixwell' )
					),
					'default'     => '0'
				),
				array(
					'name'        => 'icon_position',
					'type'        => 'select',
					'tab'         => 'design',
					'title'       => esc_html__( 'Button Icon Position', 'pixwell' ),
					'description' => esc_html__( 'Select icon position for the button', 'pixwell' ),
					'options'     => array(
						'0'     => esc_html__( '- After Button Label -', 'pixwell' ),
						'1' => esc_html__( 'Before Button Label', 'pixwell' )
					),
					'default'     => '0'
				)
			),
		);

		return $blocks;
	}
}

add_filter( 'rbc_add_block', 'pixwell_register_cta_1', 2025 );