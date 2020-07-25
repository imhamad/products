<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** composer shortcodes */
if ( ! function_exists( 'pixwell_add_shortcode' ) ) {
	function pixwell_add_shortcode( $name, $func ) {
		if ( function_exists( $func ) ) {
			add_shortcode( $name, $func );
		}
	}
}

if ( ! function_exists( 'pixwell_register_shortcodes' ) ) {
	function pixwell_register_shortcodes() {

		$shortcodes = array(
			'rbc_fw_feat_1'      => 'pixwell_rbc_fw_feat_1',
			'rbc_fw_feat_2'      => 'pixwell_rbc_fw_feat_2',
			'rbc_fw_feat_3'      => 'pixwell_rbc_fw_feat_3',
			'rbc_fw_feat_4'      => 'pixwell_rbc_fw_feat_4',
			'rbc_fw_feat_5'      => 'pixwell_rbc_fw_feat_5',
			'rbc_fw_feat_6'      => 'pixwell_rbc_fw_feat_6',
			'rbc_fw_feat_7'      => 'pixwell_rbc_fw_feat_7',
			'rbc_fw_feat_8'      => 'pixwell_rbc_fw_feat_8',
			'rbc_fw_feat_9'      => 'pixwell_rbc_fw_feat_9',
			'rbc_fw_feat_10'     => 'pixwell_rbc_fw_feat_10',
			'rbc_fw_feat_11'     => 'pixwell_rbc_fw_feat_11',
			'rbc_fw_feat_12'     => 'pixwell_rbc_fw_feat_12',
			'rbc_fw_feat_13'     => 'pixwell_rbc_fw_feat_13',
			'rbc_fw_feat_14'     => 'pixwell_rbc_fw_feat_14',
			'rbc_fw_feat_15'     => 'pixwell_rbc_fw_feat_15',
			'rbc_fw_feat_16'     => 'pixwell_rbc_fw_feat_16',
			'rbc_fw_feat_17'     => 'pixwell_rbc_fw_feat_17',
			'rbc_fw_grid_1'      => 'pixwell_rbc_fw_grid_1',
			'rbc_fw_grid_2'      => 'pixwell_rbc_fw_grid_2',
			'rbc_fw_grid_3'      => 'pixwell_rbc_fw_grid_3',
			'rbc_fw_grid_4'      => 'pixwell_rbc_fw_grid_4',
			'rbc_fw_list_1'      => 'pixwell_rbc_fw_list_1',
			'rbc_fw_list_2'      => 'pixwell_rbc_fw_list_2',
			'rbc_fw_mix_1'       => 'pixwell_rbc_fw_mix_1',
			'rbc_fw_mix_2'       => 'pixwell_rbc_fw_mix_2',
			'rbc_fw_category_1'  => 'pixwell_rbc_fw_category_1',
			'rbc_fw_category_2'  => 'pixwell_rbc_fw_category_2',
			'rbc_fw_portfolio_1' => 'pixwell_rbc_fw_portfolio_1',
			'rbc_ct_classic'     => 'pixwell_rbc_ct_classic',
			'rbc_ct_list'        => 'pixwell_rbc_ct_list',
			'rbc_ct_grid_1'      => 'pixwell_rbc_ct_grid_1',
			'rbc_ct_grid_2'      => 'pixwell_rbc_ct_grid_2',
			'rbc_ct_mix_1'       => 'pixwell_rbc_ct_mix_1',
			'rbc_ct_mix_2'       => 'pixwell_rbc_ct_mix_2',
			'rbc_subscribe'      => 'pixwell_rbc_subscribe',
			'rbc_newsletter'     => 'pixwell_rbc_newsletter',
			'rbc_fw_raw_html'    => 'pixwell_rbc_raw_html',
			'rbc_advert'         => 'pixwell_rbc_advert',
			'rb_related'         => 'pixwell_rbc_related',
			'rbc_fw_masonry_1'   => 'pixwell_rbc_fw_masonry_1',
			'rbc_ct_masonry_1'   => 'pixwell_rbc_ct_masonry_1',
			'rbc_about'          => 'pixwell_rbc_about',
			'rbc_fw_banner'      => 'pixwell_rbc_fw_banner',
			'rbc_fw_deal_1'      => 'pixwell_rbc_fw_deal_1',
			'rbc_fw_search'      => 'pixwell_rbc_fw_search',
			'rbc_image_box'      => 'pixwell_rbc_image_box',
			'rbc_heading'        => 'pixwell_rbc_heading',
			'rbc_cta_1'          => 'pixwell_rbc_cta_1'
		);

		foreach ( $shortcodes as $name => $func ) {
			pixwell_add_shortcode( $name, $func );
		}

		return false;
	}
}
