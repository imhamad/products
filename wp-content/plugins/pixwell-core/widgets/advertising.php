<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'pixwell_widget_advertising' ) ) {
	class pixwell_widget_advertising extends WP_Widget {

		function __construct() {
			$widget_ops = array(
				'classname'   => 'widget-ad',
				'description' => esc_html__( 'Display your custom ads, your banner JS or Google Adsense code, Support Google Ads Responsive', 'pixwell-core' )
			);
			parent::__construct( 'widget_advertising', esc_html__( '- Advertising Box -', 'pixwell-core' ), $widget_ops );
		}

		function widget( $args, $instance ) {

			echo $args['before_widget'];
			$instance = wp_parse_args( $instance, array(
				'title'           => '',
				'background'      => '',
				'destination'     => '',
				'image'           => '',
				'ad_script'       => '',
				'ad_size'         => 0,
				'ad_size_desktop' => 1,
				'ad_size_tablet'  => 2,
				'ad_size_mobile'  => 3,
			) );

			$instance['title'] = apply_filters( 'widget_title', $instance['title'] );
			$instance['id']    = $args['widget_id'];
			if ( ! empty( $instance['image'] ) ) :
				if ( ! empty ( $instance['background'] ) ) : ?>
					<aside class="advert-wrap advert-image" style="background-color: <?php echo esc_attr( $instance['background'] ); ?>">
				<?php else : ?>
					<aside class="advert-wrap advert-image">
				<?php endif;
				$instance['image'] = wp_get_attachment_url( $instance['image'] );
				pixwell_ad_image( $instance );
			else :
				if ( ! empty ( $instance['background'] ) ) : ?>
					<aside class="advert-wrap advert-script" style="background-color: <?php echo esc_attr( $instance['background'] ); ?>">
				<?php else : ?>
					<aside class="advert-wrap advert-script">
				<?php endif;
				pixwell_ad_script( $instance );
			endif; ?>
			<div class="clearfix"></div>
			</aside>
			<?php  echo $args['after_widget'];
		}

		function update( $new_instance, $old_instance ) {
			$instance               = $old_instance;
			$instance['title']      = strip_tags( $new_instance['title'] );
			$instance['background'] = strip_tags( $new_instance['background'] );
			$instance['image']      = strip_tags( $new_instance['image'] );
			$instance['destination'] = strip_tags( $new_instance['destination'] );
			if ( current_user_can( 'unfiltered_html' ) ) {
				$instance['ad_script'] = $new_instance['ad_script'];
			} else {
				$instance['ad_script'] = wp_filter_post_kses( $new_instance['ad_script'] );
			}
			$instance['ad_size']         = strip_tags( $new_instance['ad_size'] );
			$instance['ad_size_desktop'] = strip_tags( $new_instance['ad_size_desktop'] );
			$instance['ad_size_tablet']   = strip_tags( $new_instance['ad_size_tablet'] );
			$instance['ad_size_mobile']  = strip_tags( $new_instance['ad_size_mobile'] );

			return $instance;
		}

		function form( $instance ) {
			$defaults = array(
				'title'           => esc_html__( '- Advertisement -', 'pixwell-core' ),
				'background'      => '',
				'destination'     => '',
				'image'           => '',
				'ad_script'       => '',
				'ad_size'         => 0,
				'ad_size_desktop' => 1,
				'ad_size_tablet'  => 2,
				'ad_size_mobile'  => 3
			);
			$instance = wp_parse_args( (array) $instance, $defaults );  ?>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><strong><?php esc_html_e('Small Title', 'pixwell-core'); ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('destination')); ?>"><?php esc_html_e('Image type - Destination Link', 'pixwell-core'); ?></label>
				<input class="widefat" id="<?php echo esc_attr($this->get_field_id('destination')); ?>" name="<?php echo esc_attr($this->get_field_name('destination')); ?>" type="text" value="<?php if( !empty($instance['destination']) ) echo  esc_url($instance['destination']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('background')); ?>"><strong><?php esc_html_e('Widget Background', 'pixwell-core'); ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('background')); ?>" name="<?php echo esc_attr($this->get_field_name('background')); ?>" value="<?php echo esc_attr($instance['background']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"><?php esc_html_e( 'Image type - Image', 'pixwell-core' ) ?></label>
				<span id="<?php echo esc_attr( $this->get_field_id( 'preview' ) ); ?>" class="w-img-preview">
						<?php if ( ! empty( $instance['image'] ) ) : ?><img src="<?php echo wp_get_attachment_url( $instance['image'] ); ?>"><?php endif; ?>
				</span>
				<input data-id="<?php echo esc_attr($this->get_field_id('') ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'upload' ) ); ?>" class="w-upload-img button" type="button" value="<?php esc_attr_e('+Add Advert Image', 'pixwell-core'); ?>"/>
				<input data-id="<?php echo esc_attr($this->get_field_id('') ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'clear' ) ); ?>" class="w-clear-img button" type="button" value="<?php esc_attr_e('Remove', 'pixwell-core'); ?>"/>
				<input class="w-image-id" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>" type="hidden" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" value="<?php esc_html_e( $instance['image'] ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'ad_script' )); ?>"><?php esc_html_e('JS or Google AdSense Code','pixwell-core'); ?></label>
				<textarea rows="10" cols="50" id="<?php echo esc_attr($this->get_field_id( 'ad_script' )); ?>" name="<?php echo esc_attr($this->get_field_name('ad_script')); ?>" class="widefat"><?php echo $instance['ad_script']; ?></textarea>
			</p>
			<p><?php esc_html_e('Please leave empty Image type options if you use the script option.','pixwell-core'); ?></p>
			<h3><?php esc_html_e('Adsense Responsive', 'pixwell-core'); ?></h3>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'ad_size' )); ?>"><?php esc_html_e('Ad Size', 'pixwell-core'); ?></label>
				<select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'ad_size' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'ad_size' )); ?>">
					<option value="0" <?php if( !empty($instance['ad_size']) && $instance['ad_size'] == '0' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('From the Script', 'pixwell-core'); ?></option>
					<option value="1" <?php if( !empty($instance['ad_size']) && $instance['ad_size'] == '1' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Custom Size (Settings Below)', 'pixwell-core'); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'ad_size_desktop' )); ?>"><?php esc_html_e('Ad Size Desktop', 'pixwell-core'); ?></label>
				<select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'ad_size_desktop' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'ad_size_desktop' )); ?>">
					<option value="0" <?php if( !empty($instance['ad_size_desktop']) && $instance['ad_size_desktop'] == '0' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Hide on Desktop', 'pixwell-core'); ?></option>
					<option value="1" <?php if( !empty($instance['ad_size_desktop']) && $instance['ad_size_desktop'] == '1' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Leaderboard (728x90)', 'pixwell-core'); ?></option>
					<option value="2" <?php if( !empty($instance['ad_size_desktop']) && $instance['ad_size_desktop'] == '2' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Banner (468x60)', 'pixwell-core'); ?></option>
					<option value="3" <?php if( !empty($instance['ad_size_desktop']) && $instance['ad_size_desktop'] == '3' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Half banner (234x60)', 'pixwell-core'); ?></option>
					<option value="4" <?php if( !empty($instance['ad_size_desktop']) && $instance['ad_size_desktop'] == '4' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Button (125x125)', 'pixwell-core'); ?></option>
					<option value="5" <?php if( !empty($instance['ad_size_desktop']) && $instance['ad_size_desktop'] == '5' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Skyscraper (120x600)', 'pixwell-core'); ?></option>
					<option value="6" <?php if( !empty($instance['ad_size_desktop']) && $instance['ad_size_desktop'] == '6' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Wide Skyscraper (160x600)', 'pixwell-core'); ?></option>
					<option value="7" <?php if( !empty($instance['ad_size_desktop']) && $instance['ad_size_desktop'] == '7' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Small Rectangle (180x150)', 'pixwell-core'); ?></option>
					<option value="8" <?php if( !empty($instance['ad_size_desktop']) && $instance['ad_size_desktop'] == '8' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Vertical Banner (120 x 240)', 'pixwell-core'); ?></option>
					<option value="9" <?php if( !empty($instance['ad_size_desktop']) && $instance['ad_size_desktop'] == '9' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Small Square (200x200)', 'pixwell-core'); ?></option>
					<option value="10" <?php if( !empty($instance['ad_size_desktop']) && $instance['ad_size_desktop'] == '10' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Square (250x250)', 'pixwell-core'); ?></option>
					<option value="11" <?php if( !empty($instance['ad_size_desktop']) && $instance['ad_size_desktop'] == '11' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Medium Rectangle (300x250)', 'pixwell-core'); ?></option>
					<option value="12" <?php if( !empty($instance['ad_size_desktop']) && $instance['ad_size_desktop'] == '12' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Large Rectangle (336x280)', 'pixwell-core'); ?></option>
					<option value="13" <?php if( !empty($instance['ad_size_desktop']) && $instance['ad_size_desktop'] == '13' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Half Page (300x600)', 'pixwell-core'); ?></option>
					<option value="14" <?php if( !empty($instance['ad_size_desktop']) && $instance['ad_size_desktop'] == '14' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Portrait (300x1050)', 'pixwell-core'); ?></option>
					<option value="15" <?php if( !empty($instance['ad_size_desktop']) && $instance['ad_size_desktop'] == '15' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Mobile Banner (320x50)', 'pixwell-core'); ?></option>
					<option value="16" <?php if( !empty($instance['ad_size_desktop']) && $instance['ad_size_desktop'] == '16' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Large Leaderboard (970x90)', 'pixwell-core'); ?></option>
					<option value="17" <?php if( !empty($instance['ad_size_desktop']) && $instance['ad_size_desktop'] == '17' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Billboard (970x250)', 'pixwell-core'); ?></option>
				</select>
			</p>

			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'ad_size_tablet' )); ?>"><?php esc_html_e('Ad Size Tablet (Screen width < 800px)', 'pixwell-core'); ?></label>
				<select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'ad_size_tablet' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'ad_size_tablet' )); ?>">
					<option value="0" <?php if( !empty($instance['ad_size_tablet']) && $instance['ad_size_tablet'] == '0' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Hide on Table', 'pixwell-core'); ?></option>
					<option value="1" <?php if( !empty($instance['ad_size_tablet']) && $instance['ad_size_tablet'] == '1' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Leaderboard (728x90)', 'pixwell-core'); ?></option>
					<option value="2" <?php if( !empty($instance['ad_size_tablet']) && $instance['ad_size_tablet'] == '2' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Banner (468x60)', 'pixwell-core'); ?></option>
					<option value="3" <?php if( !empty($instance['ad_size_tablet']) && $instance['ad_size_tablet'] == '3' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Half banner (234x60)', 'pixwell-core'); ?></option>
					<option value="4" <?php if( !empty($instance['ad_size_tablet']) && $instance['ad_size_tablet'] == '4' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Button (125x125)', 'pixwell-core'); ?></option>
					<option value="5" <?php if( !empty($instance['ad_size_tablet']) && $instance['ad_size_tablet'] == '5' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Skyscraper (120x600)', 'pixwell-core'); ?></option>
					<option value="6" <?php if( !empty($instance['ad_size_tablet']) && $instance['ad_size_tablet'] == '6' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Wide Skyscraper (160x600)', 'pixwell-core'); ?></option>
					<option value="7" <?php if( !empty($instance['ad_size_tablet']) && $instance['ad_size_tablet'] == '7' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Small Rectangle (180x150)', 'pixwell-core'); ?></option>
					<option value="8" <?php if( !empty($instance['ad_size_tablet']) && $instance['ad_size_tablet'] == '8' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Vertical Banner (120 x 240)', 'pixwell-core'); ?></option>
					<option value="9" <?php if( !empty($instance['ad_size_tablet']) && $instance['ad_size_tablet'] == '9' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Small Square (200x200)', 'pixwell-core'); ?></option>
					<option value="10" <?php if( !empty($instance['ad_size_tablet']) && $instance['ad_size_tablet'] == '10' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Square (250x250)', 'pixwell-core'); ?></option>
					<option value="11" <?php if( !empty($instance['ad_size_tablet']) && $instance['ad_size_tablet'] == '11' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Medium Rectangle (300x250)', 'pixwell-core'); ?></option>
					<option value="12" <?php if( !empty($instance['ad_size_tablet']) && $instance['ad_size_tablet'] == '12' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Large Rectangle (336x280)', 'pixwell-core'); ?></option>
					<option value="13" <?php if( !empty($instance['ad_size_tablet']) && $instance['ad_size_tablet'] == '13' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Half Page (300x600)', 'pixwell-core'); ?></option>
					<option value="14" <?php if( !empty($instance['ad_size_tablet']) && $instance['ad_size_tablet'] == '14' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Portrait (300x1050)', 'pixwell-core'); ?></option>
					<option value="15" <?php if( !empty($instance['ad_size_tablet']) && $instance['ad_size_tablet'] == '15' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Mobile Banner (320x50)', 'pixwell-core'); ?></option>
					<option value="16" <?php if( !empty($instance['ad_size_tablet']) && $instance['ad_size_tablet'] == '16' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Large Leaderboard (970x90)', 'pixwell-core'); ?></option>
					<option value="17" <?php if( !empty($instance['ad_size_tablet']) && $instance['ad_size_tablet'] == '17' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Billboard (970x250)', 'pixwell-core'); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'ad_size_mobile' )); ?>"><?php esc_html_e('Ad Size Mobile (Screen width < 500px )', 'pixwell-core'); ?></label>
				<select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'ad_size_mobile' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'ad_size_mobile' )); ?>">
					<option value="0" <?php if( !empty($instance['ad_size_mobile']) && $instance['ad_size_mobile'] == '0' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Hide on Mobile', 'pixwell-core'); ?></option>
					<option value="1" <?php if( !empty($instance['ad_size_mobile']) && $instance['ad_size_mobile'] == '1' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Leaderboard (728x90)', 'pixwell-core'); ?></option>
					<option value="2" <?php if( !empty($instance['ad_size_mobile']) && $instance['ad_size_mobile'] == '2' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Banner (468x60)', 'pixwell-core'); ?></option>
					<option value="3" <?php if( !empty($instance['ad_size_mobile']) && $instance['ad_size_mobile'] == '3' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Half banner (234x60)', 'pixwell-core'); ?></option>
					<option value="4" <?php if( !empty($instance['ad_size_mobile']) && $instance['ad_size_mobile'] == '4' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Button (125x125)', 'pixwell-core'); ?></option>
					<option value="5" <?php if( !empty($instance['ad_size_mobile']) && $instance['ad_size_mobile'] == '5' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Skyscraper (120x600)', 'pixwell-core'); ?></option>
					<option value="6" <?php if( !empty($instance['ad_size_mobile']) && $instance['ad_size_mobile'] == '6' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Wide Skyscraper (160x600)', 'pixwell-core'); ?></option>
					<option value="7" <?php if( !empty($instance['ad_size_mobile']) && $instance['ad_size_mobile'] == '7' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Small Rectangle (180x150)', 'pixwell-core'); ?></option>
					<option value="8" <?php if( !empty($instance['ad_size_mobile']) && $instance['ad_size_mobile'] == '8' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Vertical Banner (120 x 240)', 'pixwell-core'); ?></option>
					<option value="9" <?php if( !empty($instance['ad_size_mobile']) && $instance['ad_size_mobile'] == '9' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Small Square (200x200)', 'pixwell-core'); ?></option>
					<option value="10" <?php if( !empty($instance['ad_size_mobile']) && $instance['ad_size_mobile'] == '10' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Square (250x250)', 'pixwell-core'); ?></option>
					<option value="11" <?php if( !empty($instance['ad_size_mobile']) && $instance['ad_size_mobile'] == '11' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Medium Rectangle (300x250)', 'pixwell-core'); ?></option>
					<option value="12" <?php if( !empty($instance['ad_size_mobile']) && $instance['ad_size_mobile'] == '12' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Large Rectangle (336x280)', 'pixwell-core'); ?></option>
					<option value="13" <?php if( !empty($instance['ad_size_mobile']) && $instance['ad_size_mobile'] == '13' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Half Page (300x600)', 'pixwell-core'); ?></option>
					<option value="14" <?php if( !empty($instance['ad_size_mobile']) && $instance['ad_size_mobile'] == '14' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Portrait (300x1050)', 'pixwell-core'); ?></option>
					<option value="15" <?php if( !empty($instance['ad_size_mobile']) && $instance['ad_size_mobile'] == '15' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Mobile Banner (320x50)', 'pixwell-core'); ?></option>
					<option value="16" <?php if( !empty($instance['ad_size_mobile']) && $instance['ad_size_mobile'] == '16' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Large Leaderboard (970x90)', 'pixwell-core'); ?></option>
					<option value="17" <?php if( !empty($instance['ad_size_mobile']) && $instance['ad_size_mobile'] == '17' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Billboard (970x250)', 'pixwell-core'); ?></option>
				</select>
			</p>
		<?php
		}
	}
}