<?php
/** widget banner */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'pixwell_widget_header_strip' ) ) :
	class pixwell_widget_header_strip extends WP_Widget {

		function __construct() {
			$widget_ops = array(
				'classname'   => 'widget-headerstrip',
				'content' => esc_html__( '[Top Site] Display banner header strip in the top of your website.', 'pixwell-core' )
			);
			parent::__construct( 'headerstrip', esc_html__( '- [Top Site] Header Strip -', 'pixwell-core' ), $widget_ops );
		}

		function widget( $args, $instance ) {

			$instance = wp_parse_args( $instance, array(
				'content'      => '',
				'text_style'   => '',
				'bg_color'     => '',
				'url'          => '',
				'submit'       => esc_html__( 'Learn More', 'pixwell-core' ),
				'btn_bg_color' => '',
				'btn_color'    => '',
				'expired'      => '1',
			) );

			$instance['submit']  = apply_filters( 'the_title', $instance['submit'] );

			$classes   = 'rb-headerstrip';
			$style     = '';
			$btn_style = '';
			if ( empty( $instance['text_style'] ) ) {
				$classes .= ' is-light-text';
			}

			if ( ! empty( $instance['bg_color'] ) ) {
				$style = 'style="background-color: ' . $instance['bg_color'] . ';"';
			}

			if ( ! empty( $instance['btn_color'] ) || ! empty( $instance['btn_bg_color'] ) ) {
				$btn_style = 'style="color: ' . $instance['btn_color'] . '; background-color: ' . $instance['btn_bg_color'] . ';"';
			}

			echo $args['before_widget']; ?>
			<div id="<?php echo 'rb-' . esc_attr( $args['widget_id'] ) ?>" class="<?php echo esc_attr( $classes ); ?>"  <?php echo do_shortcode( $style ); ?> data-headerstrip="<?php echo esc_attr( $instance['expired'] ) ?>">
				<div class="content-inner">
					<?php if ( ! empty( $instance['content'] ) ) : ?>
						<div class="headerstrip-desc"><?php echo do_shortcode( $instance['content'] ); ?></div>
					<?php endif;
					if ( ! empty( $instance['url'] ) ) : ?>
						<div class="headerstrip-btn">
							<a href="<?php echo esc_url( $instance['url'] ) ?>" target="_blank" rel="nofollow" <?php echo do_shortcode( $btn_style ); ?>><?php echo esc_html( $instance['submit'] ) ?></a>
						</div>
					<?php endif; ?>
				</div>
				<a class="headerstrip-submit" href="#"><i class="btn-close"></i></a>
			</div>
			<?php echo $args['after_widget'];
		}

		function update( $new_instance, $old_instance ) {

			$instance = $old_instance;
			if ( current_user_can( 'unfiltered_html' ) ) {
				$instance['content'] = $new_instance['content'];
			} else {
				$instance['content'] = wp_filter_post_kses( $new_instance['content'] );
			}
			$instance['bg_color']     = esc_html( $new_instance['bg_color'] );
			$instance['text_style']   = esc_html( $new_instance['text_style'] );
			$instance['url']          = esc_url( $new_instance['url'] );
			$instance['submit']       = esc_html( $new_instance['submit'] );
			$instance['btn_color']    = esc_html( $new_instance['btn_color'] );
			$instance['btn_bg_color'] = esc_html( $new_instance['btn_bg_color'] );
			$instance['expired']      = esc_html( $new_instance['expired'] );

			return $instance;
		}

		function form( $instance ) {
			$defaults = array(
				'content'      => '',
				'text_style'   => '',
				'bg_color'     => '',
				'url'          => '',
				'submit'       => esc_html__( 'Learn More', 'pixwell-core' ),
				'btn_bg_color' => '',
				'btn_color'    => '',
				'expired'      => '1',
			);
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><strong><?php esc_html_e( 'Content (Allow HTML)', 'pixwell-core' ) ?></strong></label>
				<textarea rows="5" cols="20" id="<?php echo esc_attr($this->get_field_id( 'content' )); ?>" name="<?php echo esc_attr($this->get_field_name('content')); ?>" class="widefat"><?php echo $instance['content']; ?></textarea>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'bg_color' ) ); ?>"><strong><?php esc_html_e( 'Background Color', 'pixwell-core' ) ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'bg_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'bg_color' ) ); ?>" value="<?php esc_html_e( $instance['bg_color'] ); ?>"/>
			</p>
			<em><?php esc_html_e('Input the hex value ie: #333333')?></em>
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
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'btn_color' ) ); ?>"><strong><?php esc_html_e( 'Button Color', 'pixwell-core' ) ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'btn_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'btn_color' ) ); ?>" value="<?php esc_html_e( $instance['btn_color'] ); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'btn_bg_color' ) ); ?>"><strong><?php esc_html_e( 'Button Background Color', 'pixwell-core' ) ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'btn_bg_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'btn_bg_color' ) ); ?>" value="<?php esc_html_e( $instance['btn_bg_color'] ); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'expired' )); ?>"><?php esc_html_e('Expired Time', 'pixwell-core'); ?></label>
				<select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'expired' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'expired' )); ?>">
					<option value="1" <?php if( !empty($instance['expired']) && $instance['expired'] == '1' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('1 Day', 'pixwell-core'); ?></option>
					<option value="2" <?php if( !empty($instance['expired']) && $instance['expired'] == '2' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('2 Day', 'pixwell-core'); ?></option>
					<option value="3" <?php if( !empty($instance['expired']) && $instance['expired'] == '3' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('3 Days', 'pixwell-core'); ?></option>
					<option value="7" <?php if( !empty($instance['expired']) && $instance['expired'] == '7' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('1 Week', 'pixwell-core'); ?></option>
					<option value="14" <?php if( !empty($instance['expired']) && $instance['expired'] == '14' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('2 Weeks', 'pixwell-core'); ?></option>
					<option value="30" <?php if( !empty($instance['expired']) && $instance['expired'] == '30' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('1 Month', 'pixwell-core'); ?></option>
				</select>
			</p>
		<?php
		}
	}
endif;