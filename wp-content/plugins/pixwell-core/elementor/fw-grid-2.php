<?php
namespace PixwellElementorElement\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Fw_Grid_2 extends Widget_Base {

	public function get_name() {
		return 'fw-grid-2';
	}

	public function get_title() {
		return esc_html__( 'Pixwell - FullWidth Grid 2 (4 cols)', 'pixwell-core' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return array( 'pixwell-fw' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'query_filters', array(
				'label' => esc_html__( 'Query Filters', 'pixwell-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'category',
			array(
				'label'       => esc_html__( 'Category Filter', 'pixwell-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select category filter for this block.', 'pixwell-core' ),
				'options'     => \PixwellElementorControl\Options::cat_dropdown(),
				'default'     => '0',
			)
		);
		$this->add_control(
			'categories',
			array(
				'label'       => esc_html__( 'Categories Filter', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Filter posts by multiple category IDs, separated category IDs by commas (for example: 1,2,3).', 'pixwell-core' ),
				'default'     => '',
			)
		);
		$this->add_control(
			'tags',
			array(
				'label'       => esc_html__( 'Tags Slug Filter', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Filter posts by tags slug, separated by commas (for example: tagslug1,tagslug2,tagslug3).', 'pixwell-core' ),
				'default'     => '',
			)
		);
		$this->add_control(
			'tag_not_in',
			array(
				'label'       => esc_html__( 'Exclude Tags Slug', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Exclude some tags slug from this block, separated by commas (for example: tagslug1,tagslug2,tagslug3).', 'pixwell-core' ),
				'default'     => '',
			)
		);
		$this->add_control(
			'format',
			array(
				'label'       => esc_html__( 'Post Format', 'pixwell-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Filter posts by post format.', 'pixwell-core' ),
				'options'     => \PixwellElementorControl\Options::format_dropdown(),
				'default'     => '0',
			)
		);
		$this->add_control(
			'author',
			array(
				'label'       => esc_html__( 'Author Filter', 'pixwell-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Filter posts by the author.', 'pixwell-core' ),
				'options'     => \PixwellElementorControl\Options::author_dropdown(),
				'default'     => '0',
			)
		);

		$this->add_control(
			'post_not_in',
			array(
				'label'       => esc_html__( 'Exclude Post IDs', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Exclude some post IDs from this block, separated by commas (for example: 1,2,3).', 'pixwell-core' ),
				'default'     => '',
			)
		);
		$this->add_control(
			'post_in',
			array(
				'label'       => esc_html__( 'Post IDs Filter', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Filter posts by post IDs. separated by commas (for example: 1,2,3).', 'pixwell-core' ),
				'default'     => '',
			)
		);
		$this->add_control(
			'order',
			array(
				'label'       => esc_html__( 'Sort Order', 'pixwell-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select sort order type for this block.', 'pixwell-core' ),
				'options'     => \PixwellElementorControl\Options::order_dropdown(),
				'default'     => 'date_post',
			)
		);
		$this->add_control(
			'posts_per_page',
			array(
				'label'       => esc_html__( 'Posts per Page', 'pixwell-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Select number of posts per page (total posts to show).', 'pixwell-core' ),
				'default'     => '4'
			)
		);
		$this->add_control(
			'offset',
			array(
				'label'       => esc_html__( 'Post Offset', 'pixwell-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Select number of posts to pass over. Leave blank or set 0 if you want to show at the beginning.', 'pixwell-core' ),
				'default'     => '',
			)
		);
		$this->end_controls_section();


		$this->start_controls_section(
			'section_title', array(
				'label' => esc_html__( 'Block Header', 'pixwell-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Block Title', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input block title, Leave blank if you want to disable block header.', 'pixwell-core' ),
				'default'     => esc_html__( 'Latest News', 'pixwell-core' ),
			)
		);

		$this->add_control(
			'viewmore_link',
			array(
				'label'       => esc_html__( 'View More URL', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input the block destination link, Leave blank if you want to disable clickable on the block header.', 'pixwell-core' ),
				'default'     => '',
			)
		);

		$this->add_control(
			'viewmore_title',
			array(
				'label'       => esc_html__( 'View More Label', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input the block header tagline, this is description display below the block title.', 'pixwell-core' ),
				'default'     => '',
			)
		);
		$this->add_control(
			'quick_filter',
			array(
				'label'       => esc_html__( 'Quick Filter', 'pixwell-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select a type for quick filters for displaying in the block header.', 'pixwell-core' ),
				'options'     => \PixwellElementorControl\Options::filter_dropdown(),
				'default'     => '0',
			)
		);
		$this->add_control(
			'quick_filter_ids',
			array(
				'label'       => esc_html__( 'Filter Data', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input the IDs for your quick filters, depending on the type you choose: Category Ids or Tags. separated by commas (for example: 1,2,3)', 'pixwell-core' ),
				'default'     => '',
			)
		);
		$this->add_control(
			'quick_filter_label',
			array(
				'label'       => esc_html__( 'Filter Default Label', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input text for default filter label.', 'pixwell-core' ),
				'default'     => esc_html__( 'All', 'pixwell-core' ),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_pagination', array(
				'label' => esc_html__( 'Block Pagination', 'pixwell-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'pagination',
			array(
				'label'       => esc_html__( 'Pagination', 'pixwell-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select ajax pagination for this block, default is disable.', 'pixwell-core' ),
				'options'     => \PixwellElementorControl\Options::pagination_dropdown(),
				'default'     => '0',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_ad', array(
				'label' => esc_html__( 'Advertising', 'pixwell-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'infeed_ad',
			array(
				'label'       => esc_html__( 'In-feed Advertising', 'pixwell-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select in-feed adverts for this block. This feature is not compatible with ajax pagination in some cases.', 'pixwell-core' ),
				'options'     => \PixwellElementorControl\Options::infeed_dropdown(),
				'default'     => '0',
			)
		);
		$this->add_control(
			'html_advert',
			array(
				'label'       => esc_html__( 'In-feed Ad Code', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => esc_html__( 'Input your in-feed Adsense or your script code.', 'pixwell-core' ),
				'default'     => '',
			)
		);

		$this->add_control(
			'ad_attachment',
			array(
				'label'       => esc_html__( 'In-feed Ad Image', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input your advert image URL (attachment URL).', 'pixwell-core' ),
				'default'     => '',
			)
		);

		$this->add_control(
			'ad_destination',
			array(
				'label'       => esc_html__( 'In-feed Ad Destination', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input destination URL for your advert.', 'pixwell-core' ),
				'default'     => '',
			)
		);

		$this->add_control(
			'infeed_description',
			array(
				'label'       => esc_html__( 'In-feed Ad Description', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Small description to show at top of adverts.', 'pixwell-core' ),
				'default'     => esc_html__( '- Advertisement -', 'pixwell-core' ),
			)
		);

		$this->add_control(
			'ad_pos_1',
			array(
				'label'       => esc_html__( 'Position 1', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Display advert before the article X (smaller than posts per page value), allow integer number, leave blank if you want to disable this position.', 'pixwell-core' ),
				'default'     => '',
			)
		);

		$this->add_control(
			'ad_pos_2',
			array(
				'label'       => esc_html__( 'Position 2', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Display advert before the article X (smaller than posts per page value), allow integer number, leave blank if you want to disable this position.', 'pixwell-core' ),
				'default'     => '',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style', array(
				'label' => esc_html__( 'Style', 'pixwell-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'text_style',
			array(
				'label'       => esc_html__( 'Text Style', 'pixwell-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select block text style, Select light if you have a dark background.', 'pixwell-core' ),
				'options'     => \PixwellElementorControl\Options::textstyle_dropdown(),
				'default'     => '0',
			)
		);

		$this->end_controls_section();
	}

	/** render */
	protected function render() {
		if ( function_exists( 'pixwell_rbc_fw_grid_2' ) ) {
			$settings         = $this->get_settings();
			$settings['uuid'] = 'uid_' . $this->get_id();

			echo \pixwell_rbc_fw_grid_2( $settings );
		}
	}
}
