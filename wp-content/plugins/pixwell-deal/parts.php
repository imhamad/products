<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** deal module 1 */
if ( ! function_exists( 'rb_deal_module_1' ) ) {
	function rb_deal_module_1( $settings = array() ) {
		$link = rb_get_meta( 'rb_deal_link' );
		if ( empty( $link ) ) {
			$link =  '#';
		}
		$size      = apply_filters( 'rb_deal_feat_medium', 'medium' );
		$link_text = rb_get_meta( 'rb_deal_link_label' );
		$coupon    = rb_get_meta( 'rb_deal_coupon' );
		$card      = rb_get_meta( 'rb_deal_card' ); ?>
		<div class="deal-module deal-m1">
			<div class="deal-feat">
				<figure class="deal-thumb">
					<a href="<?php echo esc_url( $link ); ?>" target="_blank" rel="nofollow" title="<?php echo wp_strip_all_tags( get_the_title() ); ?>">
						<span class="rb-iwrap pc-75"><?php the_post_thumbnail( $size ); ?></span>
					</a>
				</figure>
				<div class="deal-cards">
					<?php if ( ! empty( $card ) ) : ?>
						<div class="card-label"><span class="h6"><?php echo esc_html( $card ); ?></span></div>
					<?php endif;
					if ( ! empty( $coupon ) ) : ?>
						<div class="coupon-label tooltips-n">
							<span title="<?php esc_html_e( 'Coupon', 'pixwell-deal' ) ?>" class="h6 tipsy-el"><i class="rbi rbi-tag-round"></i><?php echo esc_html( $coupon ); ?></span>
						</div>
					<?php endif; ?>
				</div>
				<?php if ( current_user_can( 'edit_posts' ) ) : ?>
					<?php edit_post_link( esc_html__( 'edit', 'pixwell-deal' ) ); ?>
				<?php endif; ?>
			</div>
			<div class="inner">
				<h5 class="deal-title h4">
					<a class="p-url" href="<?php echo esc_url( $link ); ?>" target="_blank" rel="nofollow" title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"><?php the_title(); ?></a>
				</h5>
				<div class="deal-description comment-content">
					<?php the_content(); ?>
				</div>
				<div class="deal-link">
					<a class="btn p-link" href="<?php echo esc_url( $link ); ?>" target="_blank" rel="nofollow" title="<?php echo wp_strip_all_tags( $link_text ); ?>"><span><?php echo esc_html( $link_text ); ?></span><i class="rbi rbi-arrow-up-right"></i></a>
				</div>
			</div>
		</div>
	<?php
	}
}

/** render deal listing */
if ( ! function_exists( 'rb_deal_render_listing' ) ) {
	function rb_deal_render_listing( $attrs ) {

		$settings = shortcode_atts( array(
			'term_slugs'  => '',
			'post_not_in' => '',
			'post_in'     => '',
			'author'      => '',
			'total'       => '4',
			'columns'     => '4'
		), $attrs );

		$query_data = rb_deal_query( $settings );
		ob_start();

		if ( $query_data->have_posts() ) :
			$wrapper_classes = 'deals-wrap';
			if ( ! empty( $settings['columns'] ) ) {
				$column = absint( $settings['columns'] );
				if ( $column < 1 || $column > 5 ) {
					$column = 5;
				}
				$wrapper_classes .= ' is-cols-' . $column;
			} ?>
			<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
				<div class="deals-inner">
					<?php while ( $query_data->have_posts() ) :
						$query_data->the_post();
						echo '<div class="deal-outer">';
						rb_deal_module_1();
						echo '</div>';
					endwhile; ?>
				</div>
			</div>
			<?php wp_reset_postdata();
		else:
			rb_deal_not_found();
		endif;

		return ob_get_clean();
	}
}


/** no deal found */
if ( ! function_exists( 'rb_deal_not_found' ) ) {
	function rb_deal_not_found() {
		if ( is_admin() ) {
			echo esc_html__( 'No found deals, Please change query filters in your shortcode.', 'rb-deal' );
		}
	}
}