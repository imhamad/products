<?php
/** widget banner */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'pixwell_widget_mc' ) ) :
	class pixwell_widget_mc extends WP_Widget {

		function __construct() {
			$widget_ops = array(
				'classname'   => 'widget-mc',
				'description' => esc_html__( '[FullWidth Widget] Display Mailchimp sign-up form in full-width section.', 'pixwell-core' )
			);
			parent::__construct( 'rbmc', esc_html__( '- [FullWidth] Mailchimp Form -', 'pixwell-core' ), $widget_ops );
		}

		function widget( $args, $instance ) {

			$instance = wp_parse_args( $instance, array(
				'title'       => '',
				'description' => '',
				'shortcode'   => '',
				'image'       => '',
				'text_style'  => '',
				'bg_color'    => '',
			) );

			$instance['title'] = apply_filters( 'the_title', $instance['title'] );

			$classes = 'rb-mailchimp';
			$style = ' style="';
			if ( ! empty( $instance['image'] ) ) {
				$style .= 'background-image: url( ' . wp_get_attachment_image_url( $instance['image'], 'full' ) . ');';
				$classes .= ' is-bg';
			}
			if ( ! empty( $instance['bg_color'] ) ) {
				$style .= 'background-color:' . esc_attr( $instance['bg_color'] ) . ';';
			}
			$style .= '"';

			if ( empty( $instance['text_style'] ) ) {
				$classes .= ' is-light-text';
			}
			echo $args['before_widget']; ?>
			<div class="<?php echo esc_attr( $classes ); ?>" <?php echo $style; ?>>
				<div class="rbc-container inner rb-p20-gutter">
					<?php if ( ! empty( $instance['title'] ) ) : ?>
						<h5 class="mc-title h2"><?php echo esc_html( $instance['title'] ); ?></h5>
					<?php endif;
					if ( ! empty( $instance['description'] ) ) : ?>
						<div class="mc-desc"><span><?php echo wp_kses_post( $instance['description'] ); ?></span></div>
					<?php endif;
					if ( ! empty( $instance['shortcode'] ) ) : ?>
						<div class="mc-form"><?php echo do_shortcode( $instance['shortcode'] ); ?></div>
					<?php endif; ?>
				</div>
			</div>
			<?php echo $args['after_widget'];
		}

		function update( $new_instance, $old_instance ) {

			$instance = $old_instance;
			if ( current_user_can( 'unfiltered_html' ) ) {
				$instance['title']       = $new_instance['title'];
				$instance['description'] = $new_instance['description'];
				$instance['shortcode']   = $new_instance['shortcode'];
			} else {
				$instance['title']       = esc_html( $new_instance['title'] );
				$instance['description'] = wp_filter_post_kses( $new_instance['description'] );
				$instance['shortcode']   = wp_filter_post_kses( $new_instance['shortcode'] );
			}
			$instance['text_style'] = esc_html( $new_instance['text_style'] );
			$instance['image']      = esc_html( $new_instance['image'] );
			$instance['bg_color']   = esc_url( $new_instance['bg_color'] );

			return $instance;
		}

		function form( $instance ) {
			$defaults = array(
				'title'       => esc_html__( 'Subscribe to Our Newsletter', 'pixwell-core' ),
				'description' => esc_html__( 'Get the latest news, update and special offers delivered directly in your inbox.', 'pixwell-core' ),
				'shortcode'   => '[mc4wp_form]',
				'text_style'  => '1',
				'image'       => '',
				'bg_color'    => '#fafafa',
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
				<label for="<?php echo esc_attr( $this->get_field_id( 'shortcode' ) ); ?>"><strong><?php esc_html_e( 'Mailchimp Form Shortcode', 'pixwell-core' ) ?></strong></label>
				<textarea rows="5" cols="20" id="<?php echo esc_attr($this->get_field_id( 'shortcode' )); ?>" name="<?php echo esc_attr($this->get_field_name('shortcode')); ?>" class="widefat"><?php echo $instance['shortcode']; ?></textarea>
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
				<label for="<?php echo esc_attr( $this->get_field_id( 'bg_color' ) ); ?>"><?php esc_html_e( 'Background Color (Hex value)', 'pixwell-core' ) ?></label>
				<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'bg_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'bg_color' ) ); ?>" value="<?php esc_html_e( $instance['bg_color'] ); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"><strong><?php esc_html_e( 'Background Image', 'pixwell-core' ) ?></strong></label>
				<span id="<?php echo esc_attr( $this->get_field_id( 'preview' ) ); ?>" class="w-img-preview">
						<?php if ( ! empty( $instance['image'] ) ) : ?><img src="<?php echo wp_get_attachment_url( $instance['image'], 'full' ); ?>"><?php endif; ?>
				</span>
				<input data-id="<?php echo esc_attr($this->get_field_id('') ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'upload' ) ); ?>" class="w-upload-img button" type="button" value="<?php esc_attr_e('+Add Background Image', 'pixwell-core'); ?>"/>
				<input data-id="<?php echo esc_attr($this->get_field_id('') ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'clear' ) ); ?>" class="w-clear-img button" type="button" value="<?php esc_attr_e('Remove', 'pixwell-core'); ?>"/>
				<input class="w-image-id" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>" type="hidden" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" value="<?php esc_html_e( $instance['image'] ); ?>">
			</p>
		<?php
		}
	}
endif;