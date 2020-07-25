<?php
/** site logo */
if ( ! function_exists( 'pixwell_register_options_logo' ) ) {
	function pixwell_register_options_logo() {
		return array(
			'id'     => 'pixwell_config_section_site_logo',
			'title'  => esc_html__( 'Logo Settings', 'pixwell' ),
			'desc'   => esc_html__( 'Select or upload logos for you website.', 'pixwell' ),
			'icon'   => 'el el-star',
			'fields' => array(
				array(
					'id'    => 'info_add_favico',
					'type'  => 'info',
					'title' => esc_html__( 'Please note: Favicon Settings', 'pixwell' ),
					'style' => 'success',
					'desc'  => esc_html__( 'To add favicon, Navigate to Appearance > Customize > Site Identity > Site Icon. Please read Documentation for further information.', 'pixwell' ),
				),
				array(
					'id'     => 'section_start_site_logo',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Main Site (Header) Logo', 'pixwell' ),
					'indent' => true
				),
				array(
					'id'       => 'site_logo',
					'type'     => 'media',
					'title'    => esc_html__( 'Main Site (Header) Logo', 'pixwell' ),
					'subtitle' => esc_html__( 'Select or upload site logo. Recommended logo height value is 60px, allowed extensions are .jpg, .png and .gif.', 'pixwell' )
				),
				array(
					'id'       => 'retina_site_logo',
					'type'     => 'media',
					'title'    => esc_html__( 'Main Site (Header) Retina Logo', 'pixwell' ),
					'subtitle' => esc_html__( 'Select or upload retina (2x) log. Recommended logo height value is 120px, allowed extensions are .jpg, .png and .gif', 'pixwell' )
				),
				array(
					'id'     => 'section_end_site_logo',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_mobile_logo',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Mobile Header Logo', 'pixwell' ),
					'indent' => true
				),
				array(
					'id'       => 'mobile_logo',
					'type'     => 'media',
					'title'    => esc_html__( 'Mobile Header Logo', 'pixwell' ),
					'subtitle' => esc_html__( 'Upload a retina logo for displaying on mobile device. Recommended logo height value is 120px, allowed extensions are .jpg, .png and .gif', 'pixwell' )
				),
				array(
					'id'     => 'section_end_mobile_logo',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_sticky_logo',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Sticky Navigation Logo', 'pixwell' ),
					'indent' => true
				),
				array(
					'id'       => 'sticky_logo',
					'type'     => 'media',
					'title'    => esc_html__( 'Sticky Logo', 'pixwell' ),
					'subtitle' => esc_html__( 'Upload a logo for the sticky navigation. Recommended logo height value is 60px.', 'pixwell' )
				),
				array(
					'id'       => 'retina_sticky_logo',
					'type'     => 'media',
					'title'    => esc_html__( 'Sticky Retina Logo', 'pixwell' ),
					'subtitle' => esc_html__( 'Upload a retina (2x) logo the sticky navigation. Recommended logo height value is 120px.', 'pixwell' )
				),
				array(
					'id'     => 'section_end_sticky_logo',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_logo_float',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Transparent Header Logo', 'pixwell' ),
					'indent' => true
				),
				array(
					'id'       => 'site_logo_float',
					'type'     => 'media',
					'title'    => esc_html__( 'Header Transparent Logo', 'pixwell' ),
					'subtitle' => esc_html__( 'Upload a logo for the transparent header. Recommended logo height value is 60px.', 'pixwell' )
				),
				array(
					'id'       => 'retina_site_logo_float',
					'type'     => 'media',
					'title'    => esc_html__( 'Header Transparent Retina Logo', 'pixwell' ),
					'subtitle' => esc_html__( 'Upload a retina (2x) logo for the transparent header. Recommended logo height value is 120px.', 'pixwell' )
				),
				array(
					'id'     => 'section_end_logo_float',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_logo_ls',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Left Side Section Logo', 'pixwell' ),
					'indent' => true
				),
				array(
					'id'       => 'off_canvas_header_logo',
					'type'     => 'media',
					'title'    => esc_html__( 'Left Side Section Logo', 'pixwell' ),
					'subtitle' => esc_html__( 'Upload a logo for the left side section. Recommended logo height value is 180px and light color image..', 'pixwell' ),
					'default'  => ''
				),
				array(
					'id'       => 'off_canvas_header_logo_height',
					'type'     => 'text',
					'validate' => 'numeric',
					'class'    => 'small-text',
					'title'    => esc_html__( 'Logo Height', 'pixwell' ),
					'subtitle' => esc_html__( 'Select a max-height value for this logo, We recommend this value should be about half of your logo height (90px).', 'pixwell' ),
					'default'  => ''
				),
				array(
					'id'     => 'section_end_logo_ls',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_logo_footer',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Footer Logo', 'pixwell' ),
					'indent' => true
				),
				array(
					'id'       => 'footer_logo',
					'type'     => 'media',
					'title'    => esc_html__( 'Footer Logo', 'pixwell' ),
					'subtitle' => esc_html__( 'Select or upload the footer logo.', 'pixwell' )
				),
				array(
					'id'       => 'footer_logo_retina',
					'type'     => 'media',
					'title'    => esc_html__( 'Footer Logo Retina', 'pixwell' ),
					'subtitle' => esc_html__( 'Upload 2x logo retina for your footer logo.', 'pixwell' )
				),
				array(
					'id'     => 'section_end_logo_footer',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_font_logo',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Header Text Logo', 'pixwell' ),
					'indent' => true
				),
				array(
					'id'             => 'font_logo_text',
					'type'           => 'typography',
					'title'          => esc_html__( 'Main Site (Header) Text Logo', 'pixwell' ),
					'subtitle'       => esc_html__( 'Select font values for the header text logo if you would like to use logo text.', 'pixwell' ),
					'desc'           => esc_html__( 'Default: Montserrat [ font-size: 32px | font-weight: 900 ]', 'pixwell' ),
					'google'         => true,
					'font-backup'    => true,
					'text-align'     => false,
					'color'          => true,
					'text-transform' => true,
					'letter-spacing' => true,
					'line-height'    => false,
					'all_styles'     => false,
					'units'          => 'px',
					'output'         => array(
						'.is-logo-text .logo-title'
					)
				),
				array(
					'id'     => 'section_end_font_logo',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false
				)
			)
		);
	}
}
