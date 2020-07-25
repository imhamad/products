<?php
/** widget banner */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'pixwell_widget_banner' ) ) :
	class pixwell_widget_banner extends WP_Widget {

		function __construct() {
			$widget_ops = array(
				'classname'   => 'widget-banner',
				'description' => esc_html__( '[Sidebar Widget] Display banner with image background.', 'pixwell-core' )
			);
			parent::__construct( 'banner', esc_html__( '- [Sidebar] Banner -', 'pixwell-core' ), $widget_ops );
		}

		function widget( $args, $instance ) {

			$instance = wp_parse_args( $instance, array(
				'title'       => '',
				'description' => '',
				'image'       => '',
				'text_style'  => '',
				'submit'      => '',
				'url'         => '',
			) );

			$instance['title']       = apply_filters( 'the_title', $instance['title'] );
			$instance['submit']      = apply_filters( 'the_title', $instance['submit'] );

			$cover    = '';
			$lazyload = pixwell_get_option( 'lazy_load' );

			if ( ! empty( $instance['image'] ) ) {
				$cover = wp_get_attachment_image_url( $instance['image'], 'full' );
			}

			$classes = 'w-banner';
			if ( empty( $instance['text_style'] ) ) {
				$classes .= ' is-light-text';
			}

			echo $args['before_widget']; ?>
			<div class="<?php echo esc_attr( $classes ); ?>">
				<?php if ( ! empty( $cover ) ) : ?>
					<div class="banner-bg">
						<?php if ( empty( $lazyload ) ) : ?>
							<img src="<?php echo esc_url( $cover ); ?>" alt="<?php echo esc_attr( $instance['description'] ); ?>"/>
						<?php else : ?>
							<span class="rb-iwrap s-lazyload"><img class="rb-lazyload" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" data-src="<?php echo esc_url( $cover ); ?>" alt="<?php echo esc_attr( $instance['description'] ); ?>"/></span>

						<?php endif; ?>
					</div>
				<?php endif; ?>
				<div class="w-banner-content">
					<div class="content-inner">
						<?php if ( ! empty( $instance['title'] ) ) : ?>
							<h5 class="w-banner-title h2"><?php echo html_entity_decode( $instance['title'] ); ?></h5>
						<?php endif;
						if ( ! empty( $instance['description'] ) ) : ?>
							<div class="w-banner-desc element-desc"><?php echo html_entity_decode( $instance['description'] ); ?></div>
						<?php endif;
						if ( ! empty( $instance['url'] ) ) : ?>
							<div class="banner-btn">
								<a href="<?php echo esc_url( $instance['url'] ) ?>" target="_blank" rel="nofollow"><?php echo esc_html( $instance['submit'] ) ?></a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php echo $args['after_widget'];
		}

		function update( $new_instance, $old_instance ) {

			$instance                = $old_instance;
			if ( current_user_can( 'unfiltered_html' ) ) {
				$instance['description'] = $new_instance['description'];
				$instance['title']       = $new_instance['title'];
			} else {
				$instance['title']       = esc_html( $new_instance['title'] );
				$instance['description'] = wp_filter_post_kses( $new_instance['description'] );
			}
			$instance['image']       = esc_html( $new_instance['image'] );
			$instance['text_style']  = esc_html( $new_instance['text_style'] );
			$instance['submit']      = esc_html( $new_instance['submit'] );
			$instance['url']         = esc_url( $new_instance['url'] );
			return $instance;
		}

		function form( $instance ) {
			$defaults = array(
				'title'       => '',
				'description' => '',
				'image'       => '',
				'text_style'  => '',
				'url'         => '',
				'submit'      => esc_html__( 'Learn More', 'pixwell-core' )
			);
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><strong><?php esc_html_e( 'Title', 'pixwell-core' ) ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php esc_html_e( $instance['title'] ); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><strong><?php esc_html_e( 'Description', 'pixwell-core' ) ?></strong></label>
				<textarea rows="5" cols="20" id="<?php echo esc_attr($this->get_field_id( 'description' )); ?>" name="<?php echo esc_attr($this->get_field_name('description')); ?>" class="widefat"><?php echo $instance['description']; ?></textarea>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"><strong><?php esc_html_e( 'Cover Image', 'pixwell-core' ) ?></strong></label>
				<span id="<?php echo esc_attr( $this->get_field_id( 'preview' ) ); ?>" class="w-img-preview">
						<?php if ( ! empty( $instance['image'] ) ) : ?><img src="<?php echo wp_get_attachment_url( $instance['image'], 'full' ); ?>"><?php endif; ?>
				</span>
				<input data-id="<?php echo esc_attr($this->get_field_id('') ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'upload' ) ); ?>" class="w-upload-img button" type="button" value="<?php esc_attr_e('+Add Background Image', 'pixwell-core'); ?>"/>
				<input data-id="<?php echo esc_attr($this->get_field_id('') ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'clear' ) ); ?>" class="w-clear-img button" type="button" value="<?php esc_attr_e('Remove', 'pixwell-core'); ?>"/>
				<input class="w-image-id" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>" type="hidden" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" value="<?php esc_html_e( $instance['image'] ); ?>">
			</p>
			<p><?php esc_html__( 'Recommended added dark overlay or light overlay on the image to make the text can be easy to read.', 'pixwell-core' ) ?></p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'text_style' )); ?>"><?php esc_html_e('Text Color Style', 'pixwell-core'); ?></label>
				<select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'text_style' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'text_style' )); ?>">
					<option value="0" <?php if( !empty($instance['text_style']) && $instance['text_style'] == '0' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Light Text', 'pixwell-core'); ?></option>
					<option value="1" <?php if( !empty($instance['text_style']) && $instance['text_style'] == '1' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Dark Text', 'pixwell-core'); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>"><strong><?php esc_html_e( 'Button URL', 'pixwell-core' ) ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'url' ) ); ?>" value="<?php esc_html_e( $instance['url'] ); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'submit' ) ); ?>"><strong><?php esc_html_e( 'Button Text', 'pixwell-core' ) ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'submit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'submit' ) ); ?>" value="<?php esc_html_e( $instance['submit'] ); ?>"/>
			</p>
		<?php
		}
	}
endif;