<?php
namespace PixwellElementorElement\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Fw_Ruby_Banner extends Widget_Base {

	public function get_name() {
		return 'fw-banner';
	}

	public function get_title() {
		return esc_html__( 'Pixwell - Ruby Banner (Wrapper)', 'pixwell-core' );
	}

	public function get_icon() {
		return 'eicon-banner';
	}

	public function get_categories() {
		return array( 'pixwell-fw' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'query_filters', array(
				'label' => esc_html__( 'Content', 'pixwell-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			's1_title',
			array(
				'label'       => esc_html__( '---- SECTION 1: Section Title', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input title for section 1.', 'pixwell-core' ),
				'default'     => ''
			)
		);
		$this->add_control(
			's1_url',
			array(
				'label'       => esc_html__( 'Destination URL', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input destination URL for section 1.', 'pixwell-core' ),
				'default'     => ''
			)
		);
		$this->add_control(
			's1_image',
			array(
				'label'       => esc_html__( 'Attachment Image', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input attachment Image URL for section 1.', 'pixwell-core' ),
				'default'     => ''
			)
		);

		$this->add_control(
			's1_newtab',
			array(
				'label'       => esc_html__( 'Open New Tab', 'pixwell-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Enable or disable open in a new tab.', 'pixwell-core' ),
				'options'     => array(
					'1'  => esc_html__( 'New Tab', 'pixwell' ),
					'-1' => esc_html__( 'Self Window', 'pixwell' ),
				),
				'default'     => '1'
			)
		);

		$this->add_control(
			's2_title',
			array(
				'label'       => esc_html__( '---- SECTION 2: Section Title', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input title for section 2.', 'pixwell-core' ),
				'default'     => ''
			)
		);

		$this->add_control(
			's2_url',
			array(
				'label'       => esc_html__( 'Destination URL', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input destination URL for section 2.', 'pixwell-core' ),
				'default'     => ''
			)
		);
		$this->add_control(
			's2_image',
			array(
				'label'       => esc_html__( 'Attachment Image', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input attachment Image URL for section 2.', 'pixwell-core' ),
				'default'     => ''
			)
		);
		$this->add_control(
			's2_newtab',
			array(
				'label'       => esc_html__( 'Open New Tab', 'pixwell-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Enable or disable open in a new tab.', 'pixwell-core' ),
				'options'     => array(
					'1'  => esc_html__( 'New Tab', 'pixwell' ),
					'-1' => esc_html__( 'Self Window', 'pixwell' ),
				),
				'default'     => '1'
			)
		);

		$this->add_control(
			's3_title',
			array(
				'label'       => esc_html__( '---- SECTION 3: Section Title', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input title for section 3.', 'pixwell-core' ),
				'default'     => ''
			)
		);
		$this->add_control(
			's3_url',
			array(
				'label'       => esc_html__( 'Destination URL', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input destination URL for section 3.', 'pixwell-core' ),
				'default'     => ''
			)
		);
		$this->add_control(
			's3_image',
			array(
				'label'       => esc_html__( 'Attachment Image', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input attachment Image URL for section 3.', 'pixwell-core' ),
				'default'     => ''
			)
		);

		$this->add_control(
			's3_newtab',
			array(
				'label'       => esc_html__( 'Open New Tab', 'pixwell-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Enable or disable open in a new tab.', 'pixwell-core' ),
				'options'     => array(
					'1'  => esc_html__( 'New Tab', 'pixwell' ),
					'-1' => esc_html__( 'Self Window', 'pixwell' ),
				),
				'default'     => '1'
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
				'default'     => ''
			)
		);

		$this->add_control(
			'viewmore_link',
			array(
				'label'       => esc_html__( 'View More URL', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input the block destination link, Leave blank if you want to disable clickable on the block header.', 'pixwell-core' ),
				'default'     => ''
			)
		);

		$this->add_control(
			'viewmore_title',
			array(
				'label'       => esc_html__( 'View More Label', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input the block header tagline, this is description display below the block title.', 'pixwell-core' ),
				'default'     => ''
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
				'default'     => '0'
			)
		);

		$this->end_controls_section();

	}

	/** render */
	protected function render() {
		if ( function_exists( 'pixwell_rbc_fw_banner' ) ) {
			$settings         = $this->get_settings();
			$settings['uuid'] = 'uid_' . $this->get_id();

			echo \pixwell_rbc_fw_banner( $settings );
		}
	}
}
