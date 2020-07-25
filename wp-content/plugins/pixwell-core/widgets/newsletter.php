<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'pixwell_widget_newsletter' ) ) :
	class pixwell_widget_newsletter extends WP_Widget {

		function __construct() {
			$widget_ops = array(
				'classname'   => 'widget-newsletter',
				'description' => esc_html__( '[Sidebar Widget] Display Ruby newsletter subscribe form.', 'pixwell-core' )
			);
			parent::__construct( 'subscribe_box', esc_html__( '- [Sidebar] Ruby Newsletter -', 'pixwell-core' ), $widget_ops );
		}

		function widget( $args, $instance ) {

			echo $args['before_widget'];
			$instance = wp_parse_args( $instance, array(
				'title'         => '',
				'description'   => '',
				'image'         => '',
				'privacy'       => '',
				'submit'        => ''
			) );

			$instance['title'] = apply_filters( 'widget_title', $instance['title'] );
			if ( ! empty( $instance['image'] ) ) {
				$instance['inner_cover'] = wp_get_attachment_image_url( $instance['image'], 'pixwell_280x210' );
			}
			echo rb_render_newsletter($instance); ?>
			<?php echo $args['after_widget'];
		}

		function update( $new_instance, $old_instance ) {

			$instance                  = $old_instance;
			$instance['title']         = esc_html( $new_instance['title'] );
			if ( current_user_can( 'unfiltered_html' ) ) {
				$instance['description'] = $new_instance['description'];
			} else {
				$instance['description'] = wp_filter_post_kses( $new_instance['description'] );
			}
			$instance['image']         = esc_html( $new_instance['image'] );
			$instance['privacy']       = esc_html( $new_instance['privacy'] );
			$instance['submit']        = esc_html( $new_instance['submit'] );
			return $instance;
		}

		function form( $instance ) {
			$defaults = array(
				'title'       => esc_html__( 'Subscribe Newsletter', 'pixwell-core' ),
				'description' => esc_html__( 'Get our latest news straight into your inbox', 'pixwell-core' ),
				'image'       => '',
				'privacy'     => '',
				'submit'      => esc_html__( 'SIGN UP', 'pixwell-core' )
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
				<input data-id="<?php echo esc_attr($this->get_field_id('') ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'upload' ) ); ?>" class="w-upload-img button" type="button" value="<?php esc_attr_e('+Add Cover Image', 'pixwell-core'); ?>"/>
				<input data-id="<?php echo esc_attr($this->get_field_id('') ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'clear' ) ); ?>" class="w-clear-img button" type="button" value="<?php esc_attr_e('Remove', 'pixwell-core'); ?>"/>
				<input class="w-image-id" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>" type="hidden" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" value="<?php esc_html_e( $instance['image'] ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'privacy' ) ); ?>"><strong><?php esc_html_e( 'Privacy text', 'pixwell-core' ) ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'privacy' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'privacy' ) ); ?>" value="<?php esc_html_e( $instance['privacy'] ); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'submit' ) ); ?>"><strong><?php esc_html_e( 'Submit text', 'pixwell-core' ) ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'submit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'submit' ) ); ?>" value="<?php esc_html_e( $instance['submit'] ); ?>"/>
			</p>
		<?php
		}
	}
endif;