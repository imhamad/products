<?php
/**
 * @param $attrs
 * fullwidth newsletter
 */
if ( ! function_exists( 'pixwell_rbc_fw_search' ) ) {
	function pixwell_rbc_fw_search( $attrs ) {
		$settings = shortcode_atts( array(
			'uuid'               => '',
			'title'              => '',
			'description'        => '',
			'form_holder'        => '',
			'text_style'         => '',
			'form_style'         => '',
			'form_bg_color'      => '',
			'form_text_color'    => '',
			'form_border_radius' => '',
		), $attrs );

		$settings['classes'] = 'fw-sbox none-margin';
		if ( ! empty( $settings['form_style'] ) && 'bg' == $settings['form_style'] ) {
			$settings['classes'] .= ' is-bg-style';
		} else {
			$settings['classes'] .= ' is-border-style';
		}

		if ( ! empty( $settings['form_border_radius'] ) ) {
			$settings['classes'] .= ' is-radius';
		}
		ob_start();
		pixwell_block_open( $settings ); ?>
		<div class="content-wrap">
			<div class="sbox-header">
				<?php if ( ! empty( $settings['title'] ) ) : ?>
					<h2 class="sbox-title"><?php echo esc_html( $settings['title'] ); ?></h2>
				<?php endif;
				if ( ! empty( $settings['description'] ) ) : ?>
					<p class="sbox-description"><?php echo wp_kses_post( $settings['description'] ); ?></p>
				<?php endif; ?>
			</div>
			<?php $classname = 'sbox-form search-form'; ?>
			<div class="sbox-form-wrap">
				<form method="get" class="<?php echo esc_attr( $classname ); ?>" action="<?php echo esc_url( home_url( '/' ) ) ?>">
					<input type="search" class="search-field" placeholder="<?php if ( ! empty( $settings['form_holder'] ) ) {
						echo esc_attr( $settings['form_holder'] );
					} ?>" value="" name="s" autocomplete="off">
					<input type="submit" class="search-submit" value="">
					<i class="sbox-icon"><svg class="rb-svg" viewBox="0 0 100 100"><use xlink:href="#symbol-bsearch"></use></svg></i>
				</form>
			</div>
		</div>
		<?php pixwell_block_close();
		add_action( 'wp_footer', 'rb_embed_search_svg', 90 );
		return ob_get_clean();
	}
}

/** embed icon */
if ( ! function_exists( 'rb_embed_search_svg' ) ) :
	function rb_embed_search_svg() { ?>
		<svg style="display:none; visibility:hidden">
			<symbol id="symbol-bsearch" viewBox="0 0 513.28 513.28" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				<path d="M495.04 404.48L410.56 320c15.36-30.72 25.6-66.56 25.6-102.4C436.16 97.28 338.88 0 218.56 0S.96 97.28.96 217.6s97.28 217.6 217.6 217.6c35.84 0 71.68-10.24 102.4-25.6l84.48 84.48c25.6 25.6 64 25.6 89.6 0 23.04-25.6 23.04-64 0-89.6zM218.56 384c-92.16 0-166.4-74.24-166.4-166.4S126.4 51.2 218.56 51.2s166.4 74.24 166.4 166.4S310.72 384 218.56 384z"/>
			</symbol>
		</svg>
	<?php
	}
endif;

/**
 * @param $blocks
 *
 * @return array
 * register block settings
 */
if ( ! function_exists( 'pixwell_register_fw_search' ) ) {
	function pixwell_register_fw_search( $blocks ) {

		if ( ! is_array( $blocks ) ) {
			$blocks = array();
		}

		$blocks[] = array(
			'name'        => 'fw_search',
			'title'       => esc_html__( 'Search Box', 'pixwell' ),
			'description' => esc_html__( 'Display live search box in the fullwidth section.', 'pixwell' ),
			'section'     => array( 'fullwidth' ),
			'img'         => get_theme_file_uri( 'assets/images/search.png' ),
			'inputs'      => array(
				array(
					'name'        => 'title',
					'type'        => 'text',
					'tab'         => 'general',
					'title'       => esc_html__( 'Search Header', 'pixwell' ),
					'description' => esc_html__( 'Input the header for this search box.', 'pixwell' ),
					'default'     => esc_html__( 'How we can help?', 'pixwell' )
				),
				array(
					'name'        => 'description',
					'type'        => 'textarea',
					'tab'         => 'general',
					'title'       => esc_html__( 'Search Description', 'pixwell' ),
					'description' => esc_html__( 'Input a short description for this search box.', 'pixwell' ),
					'default'     => ''
				),
				array(
					'name'        => 'form_holder',
					'type'        => 'text',
					'tab'         => 'general',
					'title'       => esc_html__( 'Placeholder', 'pixwell' ),
					'description' => esc_html__( 'Input a placeholder text for this search box.', 'pixwell' ),
					'default'     => esc_html__( 'Input your keyword(s)...', 'pixwell' )
				),
				array(
					'name'        => 'form_style',
					'type'        => 'select',
					'tab'         => 'design',
					'title'       => esc_html__( 'Form Style', 'pixwell' ),
					'description' => esc_html__( 'Select style for the search form.', 'pixwell' ),
					'options'     => array(
						'border' => esc_html__( 'Border (Transparent Background)', 'pixwell' ),
						'bg'     => esc_html__( 'Background', 'pixwell' ),
					),
					'default'     => 'border'
				),
				array(
					'name'        => 'form_bg_color',
					'type'        => 'color',
					'tab'         => 'design',
					'title'       => esc_html__( 'Form Background Color', 'pixwell' ),
					'description' => esc_html__( 'Select background color for this search form, this option will apply to form style: Background.', 'pixwell' ),
					'default'     => '',
				),
				array(
					'name'        => 'form_text_color',
					'type'        => 'color',
					'tab'         => 'design',
					'title'       => esc_html__( 'Form Text Color', 'pixwell' ),
					'description' => esc_html__( 'Select text color for this search form, this option will apply to form style: Background.', 'pixwell' ),
					'default'     => '',
				),
				array(
					'name'        => 'form_border_radius',
					'type'        => 'color',
					'tab'         => 'design',
					'title'       => esc_html__( 'Form Border Radius', 'pixwell' ),
					'description' => esc_html__( 'Select border radius for this search form (in px).', 'pixwell' ),
					'default'     => '',
				),
				array(
					'name'        => 'margin',
					'type'        => 'dimension',
					'tab'         => 'design',
					'title'       => esc_html__( 'Margin', 'pixwell' ),
					'description' => esc_html__( 'Select margin top and bottom values (in px) for this block, default is 0', 'pixwell' ),
					'default'     => array(
						'top'    => 0,
						'bottom' => 0
					),
				),
				array(
					'name'        => 'mobile_margin',
					'type'        => 'dimension',
					'tab'         => 'design',
					'title'       => esc_html__( 'Mobile - Margin', 'pixwell' ),
					'description' => esc_html__( 'Select margin top and bottom values (in px) for this block in mobile devices, default is 0', 'pixwell' ),
					'default'     => array(
						'top'    => 0,
						'bottom' => 0
					)
				),
				array(
					'name'        => 'padding',
					'type'        => 'dimension',
					'tab'         => 'design',
					'title'       => esc_html__( 'Padding', 'pixwell' ),
					'description' => esc_html__( 'Select padding values (in px) for this block.', 'pixwell' ),
					'default'     => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => ''
					)
				),
				array(
					'name'        => 'mobile_padding',
					'type'        => 'dimension',
					'tab'         => 'design',
					'title'       => esc_html__( 'Mobile - Padding', 'pixwell' ),
					'description' => esc_html__( 'Select padding values (in px) for this block in mobile devices', 'pixwell' ),
					'default'     => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => ''
					)
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
			)
		);

		return $blocks;
	}
}

add_filter( 'rbc_add_block', 'pixwell_register_fw_search', 4910 );