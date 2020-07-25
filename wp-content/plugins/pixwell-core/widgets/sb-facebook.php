<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'pixwell_widget_facebook' ) ) :
	class pixwell_widget_facebook extends WP_Widget {

		function __construct() {
			$widget_ops = array(
				'classname'   => 'widget-facebook',
				'description' => esc_html__( '[Sidebar Widget] Display Facebook Like box in the sidebar.', 'pixwell-core' )
			);
			parent::__construct( 'pixwell_widget_facebook', esc_html__( '- [Sidebar] Facebook Like Box -', 'pixwell-core' ), $widget_ops );
		}

		function widget( $args, $instance ) {

			echo $args['before_widget'];

			$instance = wp_parse_args( $instance, array(
				'title'        => '',
				'fanpage_name' => '',
			) );
			$instance['title']    = apply_filters( 'widget_title', $instance['title'] );

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'];
			}
			if ( $instance['fanpage_name'] ) : ?>
			<div class="fb-container">
				<div id="fb-root"></div>
				<script>(function(d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id)) return;
						js = d.createElement(s); js.id = id;
						js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3&appId=1385724821660962";
						fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));</script>
				<div class="fb-page" data-href="https://facebook.com/<?php echo esc_attr( trim( $instance['fanpage_name'] ) ) ?>" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"></div>
			</div>
		<?php endif; 
			echo $args['after_widget'];
		}

		function update( $new_instance, $old_instance ) {
			$instance                 = $old_instance;
			$instance['title']        = strip_tags( $new_instance['title'] );
			$instance['fanpage_name'] = strip_tags( $new_instance['fanpage_name'] );

			return $instance;
		}

		function form( $instance ) {
			$defaults = array( 'title' => esc_html__( 'Find Us on Facebook', 'pixwell-core' ), 'fanpage_name' => '' );
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><strong><?php esc_html_e('Title', 'pixwell-core'); ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('fanpage_name')); ?>"><strong><?php esc_html_e('Fanpage Name (text without protocol HTTPS):', 'pixwell-core') ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('fanpage_name')); ?>" name="<?php echo esc_attr($this->get_field_name('fanpage_name')); ?>" value="<?php echo esc_html($instance['fanpage_name']); ?>"/>
			</p>
		<?php
		}
	}
endif;