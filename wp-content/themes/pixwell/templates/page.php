<?php
/** render single page */
if ( ! function_exists( 'pixwell_single_page' ) ):
	function pixwell_single_page() {
		while ( have_posts() ) : the_post();
			$settings        = pixwell_get_page_settings();
			$content_classes = array();
			$header_layout   = 'pageh-default';

			$content_classes[] = 'site-content rbc-container rb-p20-gutter';
			if ( ! empty( $settings['layout'] ) && '1' == $settings['layout'] ) {
				$content_classes[] = 'rbc-content-section has-sidebar is-sidebar-' . $settings['sidebar_pos'];
			} else {
				$content_classes[] = 'rbc-fw-section';
			};
			$content_classes = implode( ' ', $content_classes );

			if ( ! empty( $settings['header_layout'] ) && 1 == $settings['header_layout'] ) {
				$header_layout = 'pageh-fullwide';
			} ?>
			<div class="wrap <?php echo esc_attr(  $header_layout ); ?>">
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="<?php echo pixwell_protocol(); ?>://schema.org/WebPage">
					<?php if ( empty( $settings['title'] ) || '1' != $settings['title'] ){
						if ( 'pageh-default' == $header_layout ) {
							pixwell_pageh_default();
						} else {
							pixwell_pageh_fullwide();
						}
					} ?>
					<div class="<?php echo esc_attr( $content_classes ); ?>">
						<div class="rbc-wrap">
							<main id="main" class="site-main rbc-content">
								<div class="single-content-wrap">
									<div class="entry-content clearfix">
										<?php the_content(); ?>
										<div class="clearfix"></div>
										<?php
										wp_link_pages( array(
											'before' => '<aside class="page-links pagination-wrap pagination-number">' . pixwell_translate( 'pages' ),
											'type'   => 'plain',
											'after'  => '</aside>',
										) );
										?>
									</div>
									<?php pixwell_single_comment( true ); ?>
								</div>
							</main>
							<?php if ( ! empty( $settings['layout'] ) && '1' == $settings['layout'] ) :
								pixwell_render_sidebar( $settings['sidebar_name'] );
							endif; ?>
						</div>
					</div>
				</article>
			</div>
		<?php endwhile;
	}
endif;

/** header default */
if ( ! function_exists( 'pixwell_pageh_default' ) ):
	function pixwell_pageh_default() { ?>
		<header class="single-page-header entry-header">
			<div class="rbc-container rb-p20-gutter">
				<?php
				if ( ! is_front_page() ) {
					pixwell_breadcrumb();
				}
				the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				<?php if ( has_post_thumbnail() ): ?>
					<div class="page-featured"><?php the_post_thumbnail( 'pixwell_780x0-2x' ); ?></div>
				<?php endif; ?>
			</div>
		</header>
	<?php }
endif;

/** header fullwide */
if ( ! function_exists( 'pixwell_pageh_fullwide' ) ):
	function pixwell_pageh_fullwide() { ?>
		<header class="single-page-header entry-header">
			<div class="pageh-feat-holder">
				<?php if ( has_post_thumbnail() ): ?>
					<div class="pageh-feat-full"><?php the_post_thumbnail( 'pixwell_780x0-2x' ); ?></div>
				<?php endif; ?>
				<div class="rbc-container rb-p20-gutter is-light-text pageh-inner">
					<?php
						if ( ! is_front_page() ) {
							pixwell_breadcrumb();
						}
						the_title( '<h1 class="entry-title">', '</h1>' );
					?>
				</div>
			</div>
		</header>
	<?php }
endif;
