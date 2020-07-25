<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'pixwell_widget_youtube_subscribe' ) ) :
	class pixwell_widget_youtube_subscribe extends WP_Widget {
		function __construct() {
			$widget_ops = array(
				'classname'   => 'widget-youtube',
				'description' => esc_html__( '[Sidebar Widget] Display YouTube subscribe box in the sidebar.', 'pixwell-core' )
			);
			parent::__construct( 'youtube_subscribe', esc_html__( '- [Sidebar] Youtube Subscribe -', 'pixwell-core' ), $widget_ops );
		}

		function widget( $args, $instance ) {

			echo $args['before_widget'];

			$instance = wp_parse_args( $instance, array(
				'title'        => '',
				'channel_name' => '',
				'channel_id'   => '',
			) );

			$instance['title'] = apply_filters( 'widget_title', $instance['title'] );
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'];
			} ?>
			<div class="subscribe-youtube-wrap">
				<script src="https://apis.google.com/js/platform.js"></script>
				<?php if ( ! empty( $instance['channel_name'] ) ) : ?>
					<div class="g-ytsubscribe" data-channel="<?php echo esc_attr( $instance['channel_name'] ) ?>" data-layout="default" data-count="default"></div>
				<?php elseif ( ! empty( $instance['channel_id'] ) ) : ?>
					<div class="g-ytsubscribe" data-channelid="<?php echo esc_attr( $instance['channel_id'] ); ?>" data-layout="default" data-count="default"></div>
				<?php endif; ?>
			</div>
			<?php echo $args['after_widget'];
		}

		function update( $new_instance, $old_instance ) {
			$instance                 = $old_instance;
			$instance['title']        = strip_tags( $new_instance['title'] );
			$instance['channel_name'] = strip_tags( $new_instance['channel_name'] );
			$instance['channel_id']   = strip_tags( $new_instance['channel_id'] );

			return $instance;
		}

		function form($instance) {
			$defaults = array(
				'title'        => esc_html__( 'Subscribe to Our Channel', 'pixwell-core' ),
				'channel_name' => '',
				'channel_ID'   => '',
			);
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>

			<p>
				<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title :','pixwell-core'); ?></label>
				<input  type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php if (!empty($instance['title'])) echo esc_attr($instance['title']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('channel_name')); ?>"><?php esc_html_e('Channel Name:','pixwell-core') ?></label>
				<input  type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('channel_name')); ?>" name="<?php echo esc_attr( $this->get_field_name( 'channel_name' ) ); ?>" value="<?php if (!empty($instance['channel_name'])) echo esc_attr($instance['channel_name']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('channel_ID')); ?>"><?php esc_html_e('or Channel ID:','pixwell-core') ?></label>
				<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('channel_id')); ?>" name="<?php echo esc_attr( $this->get_field_name( 'channel_id' ) ); ?>" value="<?php if (!empty($instance['channel_id'])) echo esc_attr($instance['channel_id']); ?>"/>
			</p>
		<?php
		}
	}
endif;