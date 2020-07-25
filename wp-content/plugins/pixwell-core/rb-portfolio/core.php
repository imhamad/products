<?php
/**
 * @param array $pixwell_meta
 *
 * @return array
 * metaboxes configs
 */
function rb_portfolio_register_metaboxes( $pixwell_meta = array() ) {
	$pixwell_meta[] = array(
		'id'         => 'rb_portfolio_options',
		'title'      => esc_html__( 'Portfolio Options', 'pixwell-core' ),
		'context'    => 'normal',
		'post_types' => array( 'rb-portfolio' ),
		'fields'     => array(
			array(
				'id'      => 'rb_portfolio_gallery',
				'name'    => esc_html__( 'Upload Portfolio Gallery', 'pixwell-core' ),
				'desc'    => esc_html__( 'Upload images for this portfolio. This will show in the single portfolio.', 'pixwell-core' ),
				'type'    => 'images',
				'default' => ''
			),
			array(
				'id'      => 'rb_portfolio_project',
				'name'    => esc_html__( 'Project Info', 'pixwell-core' ),
				'desc'    => esc_html__( 'Input project name information for this portfolio. Allow raw HTML', 'pixwell-core' ),
				'type'    => 'textarea',
				'default' => ''
			),
			array(
				'id'      => 'rb_portfolio_client',
				'name'    => esc_html__( 'Client Info', 'pixwell-core' ),
				'desc'    => esc_html__( 'Input client information for this portfolio. Allow raw HTML', 'pixwell-core' ),
				'type'    => 'textarea',
				'default' => ''
			),
			array(
				'id'      => 'rb_portfolio_service',
				'name'    => esc_html__( 'Service Info', 'pixwell-core' ),
				'desc'    => esc_html__( 'Input service information for this portfolio. Allow raw HTML', 'pixwell-core' ),
				'type'    => 'textarea',
				'default' => ''
			),
			array(
				'id'      => 'rb_portfolio_location',
				'name'    => esc_html__( 'Location Info', 'pixwell-core' ),
				'desc'    => esc_html__( 'Input location information for this portfolio. Allow raw HTML', 'pixwell-core' ),
				'type'    => 'textarea',
				'default' => ''
			)
		),
	);

	return $pixwell_meta;
}


/**
 * @param $template
 *
 * @return string
 * template redirect
 */
if ( ! function_exists( 'rb_portfolio_template_redirect' ) ) {
	function rb_portfolio_template_redirect( $template ) {

		global $wp_query;
		global $post;
		$file = '';
		if ( is_single() && get_post_type() == 'rb-portfolio' ) {
			$file = 'single-portfolio.php';
		} elseif ( is_tax( 'portfolio-category' ) || is_post_type_archive( 'rb-portfolio' ) ) {
			$file = 'archive-portfolio.php';
		}

		if ( ! empty( $file ) ) {
			$template = locate_template( $file );
			if ( ! $template ) {
				$template = RB_PORTFOLIO_PATH . '/templates/' . $file;
			}
		}

		return $template;
	}
}
