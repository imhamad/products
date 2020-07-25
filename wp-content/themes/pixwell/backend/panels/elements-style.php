<?php
/* global styling */
if ( ! function_exists( 'pixwell_register_options_styling_global' ) ) {
	function pixwell_register_options_styling_global() {
		return array(
			'id'     => 'pixwell_config_section_styling_global',
			'title'  => esc_html__( 'Styles & Design', 'pixwell' ),
			'icon'   => 'el el-puzzle',
			'desc'   => esc_html__( 'Select style options for blocks and elements on your site. Those options will apply to whole pages.', 'pixwell' ),
			'fields' => array(
				array(
					'id'     => 'section_start_site_layout',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'The Site Layout', 'pixwell' ),
					'indent' => true
				),
				array(
					'id'       => 'site_layout',
					'type'     => 'select',
					'title'    => esc_html__( 'Site Layout', 'pixwell' ),
					'subtitle' => esc_html__( 'Select the layout for your website. This option will apply to whole your website.', 'pixwell' ),
					'options'  => array(
						'0'        => esc_html__( 'Full Width', 'pixwell' ),
						'is-boxed' => esc_html__( 'Boxed', 'pixwell' )
					),
					'default'  => 0
				),
				array(
					'id'          => 'boxed_bg',
					'type'        => 'background',
					'transparent' => false,
					'title'       => esc_html__( 'Boxed - Site Background', 'pixwell' ),
					'subtitle'    => esc_html__( 'Site background with image, color, etc', 'pixwell' ),
					'required'    => array( 'site_layout', '=', 'is-boxed' ),
					'default'     => array(
						'background-color'      => '#fafafa',
						'background-size'       => 'cover',
						'background-attachment' => 'fixed',
						'background-position'   => 'center center',
						'background-repeat'     => 'no-repeat'
					),
					'output'      => array( 'body.boxed' ),
				),
				array(
					'id'     => 'section_end_site_layout',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_styling_block',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Block & Widget Header Styles', 'pixwell' ),
					'indent' => true
				),
				array(
					'id'       => 'block_header_style',
					'title'    => esc_html__( 'Block Header Style', 'pixwell' ),
					'subtitle' => esc_html__( 'Select style for the header block/section, this option will apply to all header of composer block and other page sections.', 'pixwell' ),
					'type'     => 'select',
					'options'  => array(
						'dot' => esc_html__( '--Default-- (Dot)', 'pixwell' ),
						'1'   => esc_html__( 'Style 1 (Small Border)', 'pixwell' ),
						'2'   => esc_html__( 'Style 2 (Centered & Small Line)', 'pixwell' ),
						'3'   => esc_html__( 'Style 3 (Left No Border Radius)', 'pixwell' ),
						'4'   => esc_html__( 'Style 4 (Title with Background)', 'pixwell' ),
						'5'   => esc_html__( 'Style 5 (Centered and Bold Line)', 'pixwell' ),
						'6'   => esc_html__( 'Style 6 (Left Title with Big Dot)', 'pixwell' ),
						'7'   => esc_html__( 'Style 7 (Left Title with Big Border)', 'pixwell' )
					),
					'default'  => 'dot',
				),
				array(
					'id'       => 'widget_header_style',
					'title'    => esc_html__( 'Sidebar - Widget Header Style', 'pixwell' ),
					'subtitle' => esc_html__( 'Select header style for sidebar widgets.', 'pixwell' ),
					'type'     => 'select',
					'options'  => array(
						'1' => esc_html__( '--Default-- (Only Title)', 'pixwell' ),
						'2' => esc_html__( 'Style 2 (Centered)', 'pixwell' ),
						'3' => esc_html__( 'Style 3 (Background)', 'pixwell' )
					),
					'default'  => 1,
				),
				array(
					'id'       => 'entry_meta_style',
					'title'    => esc_html__( 'Entry Meta Style', 'pixwell' ),
					'subtitle' => esc_html__( 'Select a style for post entry meta. This setting will apply big List and Classic layouts.', 'pixwell' ),
					'type'     => 'select',
					'options'  => array(
						'0'      => esc_html__( '--Default--', 'pixwell' ),
						'border' => esc_html__( 'Top Border (Fashion Style)', 'pixwell' )
					),
					'default'  => 0,
				),
				array(
					'id'     => 'section_end_styling_block',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_styling_post',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Elements Style & Animation', 'pixwell' ),
					'indent' => true
				),
				array(
					'id'       => 'style_element',
					'title'    => esc_html__( 'Elements Style', 'pixwell' ),
					'subtitle' => esc_html__( 'Select a style for almost elements and buttons in your website.', 'pixwell' ),
					'type'     => 'select',
					'options'  => array(
						'none'      => esc_html__( 'Rectangle', 'pixwell' ),
						'round'     => esc_html__( 'Rounded Corner (Without Featured)', 'pixwell' ),
						'round_all' => esc_html__( 'Rounded Corner (With Featured)', 'pixwell' ),
					),
					'default'  => 'none',
				),
				array(
					'id'       => 'style_cat_icon',
					'title'    => esc_html__( 'Category Icon Style', 'pixwell' ),
					'subtitle' => esc_html__( 'Select the category meta info (The category icon is displayed overlay on the featured image.) style.', 'pixwell' ),
					'type'     => 'select',
					'options'  => array(
						'radius' => esc_html__( '--Default-- (Square)', 'pixwell' ),
						'round'  => esc_html__( 'Rounded Corner', 'pixwell' ),
						'square' => esc_html__( 'Small Square', 'pixwell' ),
						'line'   => esc_html__( 'Underline Text', 'pixwell' ),
						'simple' => esc_html__( 'Text Only', 'pixwell' )
					),
					'default'  => 'radius',
				),
				array(
					'id'       => 'meta_shop_post',
					'title'    => esc_html__( 'Shop the Post Meta', 'pixwell' ),
					'subtitle' => esc_html__( 'Display shop the post text with icon, replace to entry meta info of post enabled this feature.', 'pixwell' ),
					'type'     => 'switch',
					'default'  => 1,
				),
				array(
					'id'       => 'readmore_icon',
					'title'    => esc_html__( 'Read More Arrow Icon', 'pixwell' ),
					'subtitle' => esc_html__( 'Show arrow icon after the read more text.', 'pixwell' ),
					'type'     => 'switch',
					'default'  => 1,
				),
				array(
					'id'       => 'readmore_mobile',
					'title'    => esc_html__( 'Hide Read More on Mobile', 'pixwell' ),
					'subtitle' => esc_html__( 'Hide the read more link on mobile devices.', 'pixwell' ),
					'type'     => 'switch',
					'default'  => 0,
				),
				array(
					'id'       => 'excerpt_mobile',
					'title'    => esc_html__( 'Hide Excerpt on Mobile', 'pixwell' ),
					'subtitle' => esc_html__( 'Hide post excerpt on mobile devices.', 'pixwell' ),
					'type'     => 'switch',
					'default'  => 1,
				),
				array(
					'id'       => 'pos_feat',
					'type'     => 'select',
					'title'    => esc_html__( 'Featured Image - Crop Position', 'pixwell' ),
					'subtitle' => esc_html__( 'Select position to crop featured images. Recommended select top position if you have people images.', 'pixwell' ),
					'desc'     => esc_html__( 'Run the Regenerate thumbnail plugin to make the change applies to old images.', 'pixwell' ),
					'options'  => array(
						'center' => esc_html__( 'From The Center', 'pixwell' ),
						'top'    => esc_html__( 'From The Top', 'pixwell' ),
					),
					'default'  => 'center'
				),
				array(
					'id'       => 'feat_overlay',
					'type'     => 'switch',
					'title'    => esc_html__( 'Dark Overlay on Featured Image', 'pixwell' ),
					'subtitle' => esc_html__( 'Enable or disable overlay on the featured images.', 'pixwell' ),
					'switch'   => true,
					'default'  => 1
				),
				array(
					'id'       => 'pagination_layout',
					'type'     => 'select',
					'title'    => esc_html__( 'Pagination Layout', 'pixwell' ),
					'subtitle' => esc_html__( 'Select pagination button color style for your website.', 'pixwell' ),
					'options'  => array(
						'light' => esc_html__( 'Light Background', 'pixwell' ),
						'dark'  => esc_html__( 'Dark Background', 'pixwell' ),
					),
					'default'  => 'light'
				),
				array(
					'id'     => 'section_end_styling_post',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_styling_entry_meta',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Post Entry Meta Style', 'pixwell' ),
					'indent' => true
				),
				array(
					'id'       => 'meta_date_icon',
					'title'    => esc_html__( 'Icon before Published/Updated Date Meta', 'pixwell' ),
					'subtitle' => esc_html__( 'Show the clock icon before the date entry meta.', 'pixwell' ),
					'type'     => 'switch',
					'default'  => false,
				),
				array(
					'id'       => 'human_time',
					'title'    => esc_html__( 'Human Time Format', 'pixwell' ),
					'subtitle' => esc_html__( 'Enable or disable the human time format ("ago") for the date.', 'pixwell' ),
					'type'     => 'switch',
					'default'  => 0
				),
				array(
					'id'       => 'meta_author_icon',
					'title'    => esc_html__( 'Avatar before Author Meta', 'pixwell' ),
					'subtitle' => esc_html__( 'Show avatar image before the author entry meta.', 'pixwell' ),
					'type'     => 'switch',
					'default'  => 1,
				),
				array(
					'id'       => 'meta_comment_icon',
					'title'    => esc_html__( 'Icon before Comment Meta', 'pixwell' ),
					'subtitle' => esc_html__( 'Show icon before the comment entry meta.', 'pixwell' ),
					'type'     => 'switch',
					'default'  => false,
				),
				array(
					'id'       => 'meta_view_icon',
					'title'    => esc_html__( 'Icon before View Meta', 'pixwell' ),
					'subtitle' => esc_html__( 'Show eye icon before the view entry meta.', 'pixwell' ),
					'type'     => 'switch',
					'default'  => false,
				),
				array(
					'id'       => 'meta_read_icon',
					'title'    => esc_html__( 'Icon before Reading Time', 'pixwell' ),
					'subtitle' => esc_html__( 'Show the clock icon before the reading time meta.', 'pixwell' ),
					'type'     => 'switch',
					'default'  => false,
				),
				array(
					'id'       => 'read_speed',
					'title'    => esc_html__( 'Reading Speed (Word per minute)', 'pixwell' ),
					'subtitle' => esc_html__( 'Input number of word per minute. Default is 130', 'pixwell' ),
					'type'     => 'text',
					'default'  => 130,
				),
				array(
					'id'     => 'section_end_styling_entry_meta',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_styling_custom_meta',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Custom Entry Meta Settings', 'pixwell' ),
					'indent' => true
				),
				array(
					'id'       => 'meta_custom',
					'title'    => esc_html__( 'Create Custom Entry Meta', 'pixwell' ),
					'subtitle' => esc_html__( 'The new small entry meta will appear after the category icon on the featured image. This feature allow you can create your own entry meta.', 'pixwell' ),
					'type'     => 'switch',
					'default'  => 1,
				),
				array(
					'id'       => 'meta_custom_icon',
					'title'    => esc_html__( 'Icon CSS Class Name', 'pixwell' ),
					'subtitle' => esc_html__( 'Input the CSS class name of the icon to display at the beginning of the meta.', 'pixwell' ),
					'type'     => 'text',
					'default'  => 'rbi-fish-eye',
				),
				array(
					'id'       => 'meta_custom_text',
					'title'    => esc_html__( 'Custom Entry Meta - Text', 'pixwell' ),
					'subtitle' => esc_html__( 'Input your custom meta text, This text will combine with the value in the post editor (append to the end) to display.', 'pixwell' ),
					'type'     => 'text',
					'default'  => ''
				),
				array(
					'id'       => 'meta_custom_pos',
					'title'    => esc_html__( 'Custom Entry Meta - Text Position', 'pixwell' ),
					'subtitle' => esc_html__( 'Select the position of the text.', 'pixwell' ),
					'type'     => 'select',
					'options'  => array(
						'end'   => esc_html__( 'Suffixes (at the end)', 'pixwell' ),
						'begin' => esc_html__( 'Prefix (at the begin)', 'pixwell' ),

					),
					'default'  => 'end',
				),
				array(
					'id'     => 'section_end_styling_custom_meta',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_styling_post_format',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Post Format Icons', 'pixwell' ),
					'indent' => true
				),
				array(
					'id'       => 'post_icon_video',
					'title'    => esc_html__( 'Video Icon', 'pixwell' ),
					'subtitle' => esc_html__( 'Enable or disable the video icon in the featured image.', 'pixwell' ),
					'type'     => 'switch',
					'default'  => 1
				),
				array(
					'id'       => 'post_icon_gallery',
					'title'    => esc_html__( 'Gallery Icon', 'pixwell' ),
					'subtitle' => esc_html__( 'Enable or disable the gallery icon in the featured image.', 'pixwell' ),
					'type'     => 'switch',
					'default'  => 0
				),
				array(
					'id'       => 'post_icon_audio',
					'title'    => esc_html__( 'Audio Icon', 'pixwell' ),
					'subtitle' => esc_html__( 'Enable or disable the audio icon in the featured image.', 'pixwell' ),
					'type'     => 'switch',
					'default'  => 0
				),
				array(
					'id'     => 'section_end_styling_post_format',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_styling_slider',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Slider Settings', 'pixwell' ),
					'indent' => true
				),
				array(
					'id'       => 'slider_play',
					'type'     => 'switch',
					'title'    => esc_html__( 'Auto Play Next Slides', 'pixwell' ),
					'subtitle' => esc_html__( 'Enable or disable auto play next slides for all sliders in your site.', 'pixwell' ),
					'switch'   => true,
					'default'  => 0
				),
				array(
					'id'       => 'slider_speed',
					'type'     => 'text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Auto Play Speed', 'pixwell' ),
					'subtitle' => esc_html__( 'Select the item to next a slide in milliseconds (default is 5500).', 'pixwell' ),
					'default'  => ''
				),
				array(
					'id'       => 'slider_dot',
					'type'     => 'switch',
					'title'    => esc_html__( 'Slider Dot', 'pixwell' ),
					'subtitle' => esc_html__( 'Enable or disable slider dot in your website.', 'pixwell' ),
					'switch'   => true,
					'default'  => 1
				),
				array(
					'id'       => 'slider_animation',
					'type'     => 'select',
					'title'    => esc_html__( 'Slider Animation', 'pixwell' ),
					'subtitle' => esc_html__( 'Select animation for sliders. This setting will apply to the slider (not carousel)', 'pixwell' ),
					'options'  => array(
						'0' => esc_html__( 'Slide', 'pixwell' ),
						'1' => esc_html__( 'Fade', 'pixwell' ),
					),
					'default'  => 0
				),
				array(
					'id'     => 'section_end_styling_slider',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false
				)
			),
		);
	}
}


/* featured image */
if ( ! function_exists( 'pixwell_register_options_feat_img' ) ) {
	function pixwell_register_options_feat_img() {
		return array(
			'id'     => 'pixwell_config_section_feat_img',
			'title'  => esc_html__( 'Featured Image', 'pixwell' ),
			'icon'   => 'el el-picture',
			'desc'   => esc_html__( 'Select style options for the featured image on your website.', 'pixwell' ),
			'fields' => array(
				array(
					'id'     => 'section_start_lazy_load',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Lazy Load Settings', 'pixwell' ),
					'indent' => true
				),
				array(
					'id'       => 'lazy_load',
					'type'     => 'switch',
					'title'    => esc_html__( 'Lazy Load Featured Images', 'pixwell' ),
					'subtitle' => esc_html__( 'Enable or disable the lazy load feature images.', 'pixwell' ),
					'switch'   => true,
					'default'  => 1
				),
				array(
					'id'       => 'lazy_content',
					'type'     => 'switch',
					'title'    => esc_html__( 'Lazy Load Content Images', 'pixwell' ),
					'subtitle' => esc_html__( 'Enable or disable lazy load for images of the single post content.', 'pixwell' ),
					'switch'   => true,
					'default'  => 1
				),
				array(
					'id'       => 'lazy_animate',
					'type'     => 'switch',
					'title'    => esc_html__( 'PlaceHolder Animation', 'pixwell' ),
					'subtitle' => esc_html__( 'Enable or disable the placeholder animation.', 'pixwell' ),
					'switch'   => true,
					'default'  => 1
				),
				array(
					'id'     => 'section_end_lazy_load',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_feat_size',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Image Size Settings', 'pixwell' ),
					'indent' => true
				),
				array(
					'id'    => 'image_size_information',
					'type'  => 'info',
					'title' => esc_html__( 'Please Note:', 'pixwell' ),
					'style' => 'success',
					'desc'  => html_entity_decode( esc_html__( 'WordPress will crop uploaded images to ensure your website use the best image size for layouts.<br>
											Below is the sizes list, You can enable or disable any size you do not use.<br>
											If the thumbnail image is disabled for a specific module that you use, the retina (2x) or full size image will be loaded.', 'pixwell' ) ),
				),
				array(
					'id'    => 'image_size_note',
					'type'  => 'info',
					'title' => esc_html__( 'Please note: REGENERATE THUMBNAILS if you change the below settings.', 'pixwell' ),
					'style' => 'warning',
					'desc'  => html_entity_decode( esc_html__( 'Refer <a href="https://help.themeruby.com/pixwell/what-to-do-when-images-are-not-displaying-consistent-in-size/" target="_blank">the documentation</a> for further information.', 'pixwell' ) ),
				),
				array(
					'id'       => 'image_size_v1',
					'type'     => 'switch',
					'title'    => esc_html__( '370x250', 'pixwell' ),
					'subtitle' => esc_html__( 'This image is used for modules: Grid 1, Overlay 2', 'pixwell' ),
					'switch'   => true,
					'default'  => 1
				),
				array(
					'id'       => 'image_size_v2',
					'type'     => 'switch',
					'title'    => esc_html__( '740x500', 'pixwell' ),
					'subtitle' => esc_html__( 'This image is used for modules: Classic 1, Classic 2, List 1, List 6, Support retina for the size 370x250.', 'pixwell' ),
					'switch'   => true,
					'default'  => 1
				),
				array(
					'id'       => 'image_size_v3',
					'type'     => 'switch',
					'title'    => esc_html__( '1110x750', 'pixwell' ),
					'subtitle' => esc_html__( 'This image is used for modules: Overlay 1, Support retina for the size 740x500.', 'pixwell' ),
					'switch'   => true,
					'default'  => 1
				),
				array(
					'id'       => 'image_size_v4',
					'type'     => 'switch',
					'title'    => esc_html__( '280x210', 'pixwell' ),
					'subtitle' => esc_html__( 'This image is used for modules: Grid 2, Grid 4, Grid W1, List 2, List 3, List 4, Left Side Article, Deals, Ruby Newsletter', 'pixwell' ),
					'switch'   => true,
					'default'  => 1
				),
				array(
					'id'       => 'image_size_v5',
					'type'     => 'switch',
					'title'    => esc_html__( '560x420', 'pixwell' ),
					'subtitle' => esc_html__( 'This image is used for modules: Overlay 5, support retina for the size 280x210.', 'pixwell' ),
					'switch'   => true,
					'default'  => 1
				),
				array(
					'id'       => 'image_size_h1',
					'type'     => 'switch',
					'title'    => esc_html__( '400x450', 'pixwell' ),
					'subtitle' => esc_html__( 'This image is used for modules: Grid 5, Grid 6, Overlay 3, Category List.', 'pixwell' ),
					'switch'   => true,
					'default'  => 1
				),
				array(
					'id'       => 'image_size_h2',
					'type'     => 'switch',
					'title'    => esc_html__( '400x600', 'pixwell' ),
					'subtitle' => esc_html__( 'This image is used for modules: Overlay 8, Mix Grid (Wrapper).', 'pixwell' ),
					'switch'   => true,
					'default'  => 1
				),
				array(
					'id'       => 'image_size_z1',
					'type'     => 'switch',
					'title'    => esc_html__( '450x0', 'pixwell' ),
					'subtitle' => esc_html__( 'This image is used for modules: Masonry 1, Portfolio layout.', 'pixwell' ),
					'switch'   => true,
					'default'  => 1
				),
				array(
					'id'       => 'image_size_z2',
					'type'     => 'switch',
					'title'    => esc_html__( '780x0', 'pixwell' ),
					'subtitle' => esc_html__( 'This image is used for modules: Classic 2, Single Post Page, Mix Grid (Wrapper), support retina for the size 450x0.', 'pixwell' ),
					'switch'   => true,
					'default'  => 1
				),
				array(
					'id'       => 'image_size_z3',
					'type'     => 'switch',
					'title'    => esc_html__( '1600x0', 'pixwell' ),
					'subtitle' => esc_html__( 'This image is used for modules: Overlay 4, Overlay 7, Overlay 9, Single Post Page (without sidebar, Fullwidth and Fullscreen), Gallery. Support retina for the size 780x0.', 'pixwell' ),
					'switch'   => true,
					'default'  => 1
				),
				array(
					'id'     => 'section_end_feat_size',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false
				),
			)
		);
	}
}
