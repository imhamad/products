<?php
/**
 * @param $attrs
 * render gallery
 */
if ( ! function_exists( 'rb_gallery_shortcode' ) ) :
	function rb_gallery_shortcode( $attrs ) {
		$settings = shortcode_atts( array(
			'id'      => '',
			'columns' => '3',
			'size'    => 'full',
			'wrap'    => '0',
			'share'   => '',
		), $attrs );

		if ( empty( $settings['id'] ) ) {
			return false;
		};

		$gallery_data = rb_get_meta( 'rb_gallery', $settings['id'] );
		if ( empty( $gallery_data ) || ! is_string( $gallery_data ) ) {
			return false;
		}

		$class_name = 'rb-gallery-wrap rb-sizer-' . esc_attr( $settings['columns'] );
		if (empty( $settings['wrap'] )) {
			$class_name .= ' is-wide';
		}
		$data = explode( ',', $gallery_data );
		if ( is_array( $data ) ) :
			$index = 0; ?>
			<div class="<?php echo esc_attr($class_name); ?>">
				<?php if (empty( $settings['wrap'] )) : ?>
				<div class="rbc-container rb-p20-gutter">
				<?php endif; ?>
					<div class="gallery-inner gallery-loading">
						<?php foreach ( $data as $attachment_id ) :
							if ( ! empty( $attachment_id ) ) : ?>
								<div class="rb-gallery-el">
									<a href="#" class="rb-gallery-link" data-gallery="#rb-lightbox-<?php echo get_the_ID(); ?>" data-index="<?php echo esc_attr( $index ); ?>">
										<?php echo rb_add_lazyload( wp_get_attachment_image( $attachment_id, $settings['size'] ) ); ?>
									</a>
								</div>
								<?php $index ++;
							endif;
						endforeach; ?>
					</div>
					<div class="clearfix"></div>
					<?php if ( ! empty( $settings['share'] ) && 1 == $settings['share'] ) :
						$socials = array(
							'facebook'  => pixwell_get_option( 'gallery_share_facebook' ),
							'twitter'   => pixwell_get_option( 'gallery_share_twitter' ),
							'pinterest' => pixwell_get_option( 'gallery_share_pinterest' ),
							'whatsapp'  => pixwell_get_option( 'gallery_share_whatsapp' ),
							'linkedin'  => pixwell_get_option( 'gallery_share_linkedin' ),
							'tumblr'    => pixwell_get_option( 'gallery_share_tumblr' ),
							'reddit'    => pixwell_get_option( 'gallery_share_reddit' ),
							'vk'        => pixwell_get_option( 'gallery_share_vk' ),
							'telegram'  => pixwell_get_option( 'gallery_share_telegram' ),
							'email'     => pixwell_get_option( 'gallery_share_email' ),
						); ?>
						<div class="gallery-shares">
							<div class="inner tooltips-n">
								<?php pixwell_render_share_icon( $socials ); ?>
							</div>
						</div>
					<?php endif;
				if (empty( $settings['wrap'] )) : ?>
				</div>
				<?php endif; ?>
			</div>
			<?php rb_gallery_light_box( $data );
		endif;
	}
endif;


/* gallery light box */
if ( ! function_exists( 'rb_gallery_light_box' ) ) :
	function rb_gallery_light_box( $data ) {
		$post_id = get_the_ID(); ?>
		<aside id="rb-lightbox-<?php echo esc_attr( $post_id ); ?>" class="mfp-hide">
			<?php foreach ( $data as $attachment_id ) :
				if ( ! empty( $attachment_id ) ) :
					$attachment  = get_post( $attachment_id );
					$title       = get_the_title( $attachment_id );
					$caption     = $attachment->post_excerpt;
					$description = wpautop( $attachment->post_content ); ?>
					<div class="gallery-el">
						<?php rb_gallery_selection( $data ); ?>
						<div class="gallery-popup-holder post-type-gallery">
							<span class="image-title is-hidden"><?php echo esc_html( $title ); ?></span>

							<div class="gallery-popup-image">
								<?php echo wp_get_attachment_image( $attachment_id, 'full' ); ?>
							</div>
							<?php if ( ! empty( $caption ) || ! empty( $description ) ) : ?>
								<div class="gallery-popup-entry is-light-text">
									<h4 class="image-popup-caption h3"><?php echo wp_kses_post( $caption ); ?></h4>
									<div class="image-popup-description entry"><?php echo wp_kses_post( $description ); ?></div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endif;
			endforeach; ?>
		</aside>
	<?php
	}
endif;


/**
 * @param array $data
 * gallery selection list
 */
if ( ! function_exists( 'rb_gallery_selection' ) ) {
	function rb_gallery_selection( $data = array() ) {
		if ( ! empty( $data ) ):
			$index = 0; ?>
			<div class="gallery-popup-selection post-type-gallery">
				<?php foreach ( $data as $attachment_id ) :
					echo '<a href="#" class="gallery-popup-select" data-index="' . $index . '">';
					echo wp_get_attachment_image( $attachment_id, 'thumbnail' );
					echo '</a>';
					$index ++;
				endforeach; ?>
			</div>
		<?php endif;
	}
}


