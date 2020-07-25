<?php
namespace PixwellElementorElement\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Fw_Ruby_Newsletter extends Widget_Base {

	public function get_name() {
		return 'rb-newsletter';
	}

	public function get_title() {
		return esc_html__( 'Pixwell - Ruby Newsletter', 'pixwell-core' );
	}

	public function get_icon() {
		return 'eicon-mail';
	}

	public function get_categories() {
		return array( 'pixwell-fw' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'query_filters', array(
				'label' => esc_html__( 'Newsletter', 'pixwell-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Block Title', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input block title, Leave blank if you want to disable block header.', 'pixwell-core' ),
				'default'     => esc_html__( 'Subscribe Newsletter', 'pixwell-core' ),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'       => esc_html__( 'Block Description', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => esc_html__( 'Input a short description for this block.', 'pixwell-core' ),
				'default'     => esc_html__( 'Get our latest news straight into your inbox', 'pixwell-core' ),
			)
		);

		$this->add_control(
			'privacy',
			array(
				'label'       => esc_html__( 'Privacy Text', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input your privacy text. Leave blank you would like to disable.', 'pixwell-core' ),
				'default'     => '',
			)
		);

		$this->add_control(
			'submit',
			array(
				'label'       => esc_html__( 'Submit Text', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input the submit button text. The icon will display if you leave blank this option.', 'pixwell-core' ),
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
			'layout',
			array(
				'label'       => esc_html__( 'Block Layout', 'pixwell-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select the layout for this block.', 'pixwell-core' ),
				'options'     => array(
					'1' => esc_html__( '-Default (Right Form)-', 'pixwell-core' ),
					'2' => esc_html__( 'Style 2 (Bottom Form)', 'pixwell-core' )
				),
				'default'     => '1',
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
		if ( function_exists( 'pixwell_rbc_newsletter' ) ) {
			$settings         = $this->get_settings();
			$settings['uuid'] = 'uid_' . $this->get_id();

			echo \pixwell_rbc_newsletter( $settings );
		}
	}
}
