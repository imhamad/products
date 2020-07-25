<?php
namespace PixwellElementorElement\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Fw_Ruby_About_Me extends Widget_Base {

	public function get_name() {
		return 'rb-about-me';
	}

	public function get_title() {
		return esc_html__( 'Pixwell - About Me', 'pixwell-core' );
	}

	public function get_icon() {
		return 'eicon-user-circle-o';
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
			'html_about_tagline',
			array(
				'label'       => esc_html__( 'About Tagline', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => esc_html__( 'Input the about me tagline for this block.', 'pixwell-core' ),
				'default'     => '',
			)
		);
		$this->add_control(
			'html_about_title',
			array(
				'label'       => esc_html__( 'About Title', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => esc_html__( 'Input about me title for this bock.', 'pixwell-core' ),
				'default'     => '',
			)
		);
		$this->add_control(
			'html_about_desc',
			array(
				'label'       => esc_html__( 'About Description', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => esc_html__( 'Input about me title for this bock.', 'pixwell-core' ),
				'default'     => '',
			)
		);
		$this->add_control(
			'about_sign',
			array(
				'label'       => esc_html__( 'About Signature Image', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input signature image link (attachment URL .jpg) for this block.  Recommended image width: 700px.', 'pixwell-core' ),
				'default'     => '',
			)
		);
		$this->add_control(
			'about_image',
			array(
				'label'       => esc_html__( 'About Image', 'pixwell-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input about me image link (attachment URL .jpg) to display at the right side for this block. Recommended image height: 100px.', 'pixwell-core' ),
				'default'     => '',
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
		if ( function_exists( 'pixwell_rbc_about' ) ) {
			$settings                    = $this->get_settings();
			$settings['uuid']            = 'uid_' . $this->get_id();
			$settings['elementor_block'] = 'elementor';

			echo \pixwell_rbc_about( $settings );
		}
	}
}
