<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'pixwell_widget_social_icon' ) ) :
	class pixwell_widget_social_icon extends WP_Widget {
	
		function __construct() {
			$widget_ops = array(
				'classname'   => 'widget-social-icon',
				'description' => esc_html__( '[Sidebar Widget] Display about me information with social icons in the sidebar section.', 'pixwell-core' )
			);
			parent::__construct( 'social_icon', esc_html__( '- [Sidebar] Social/About -', 'pixwell-core' ), $widget_ops );
		}

		function widget( $args, $instance ) {

			if ( ! function_exists( 'pixwell_render_social_icons' ) || ! function_exists( 'pixwell_get_web_socials' ) ) {
				return false;
			}

			echo $args['before_widget'];
			$instance = wp_parse_args( $instance, array(
				'title'             => '',
				'content'           => '',
				'new_tab'           => true,
				'style'             => 1,
				'data_social'       => 1,
				'social_facebook'   => '',
				'social_twitter'    => '',
				'social_instagram'  => '',
				'social_pinterest'  => '',
				'social_linkedin'   => '',
				'social_tumblr'     => '',
				'social_flickr'     => '',
				'social_skype'      => '',
				'social_snapchat'   => '',
				'social_myspace'    => '',
				'social_youtube'    => '',
				'social_bloglovin'  => '',
				'social_digg'       => '',
				'social_dribbble'   => '',
				'social_soundcloud' => '',
				'social_vimeo'      => '',
				'social_reddit'     => '',
				'social_vk'         => '',
				'social_telegram'   => '',
				'social_whatsapp'   => '',
				'social_rss'        => ''
			) );

			$instance['title'] = apply_filters( 'widget_title', $instance['title'] );

			if ( ! empty( $instance['new_tab'] ) ) {
				$instance['new_tab'] = true;
			} else {
				$instance['new_tab'] = false;
			}

			if ( 1 == $instance['data_social'] ) {
				$data_social = pixwell_get_web_socials();
			} else {
				$data_social               = array();
				$data_social['facebook']   = ( ! empty( $instance['social_facebook'] ) ) ? esc_url( $instance['social_facebook'] ) : '';
				$data_social['twitter']    = ( ! empty( $instance['social_twitter'] ) ) ? esc_url( $instance['social_twitter'] ) : '';
				$data_social['instagram']  = ( ! empty( $instance['social_instagram'] ) ) ? esc_url( $instance['social_instagram'] ) : '';
				$data_social['pinterest']  = ( ! empty( $instance['social_pinterest'] ) ) ? esc_url( $instance['social_pinterest'] ) : '';
				$data_social['linkedin']   = ( ! empty( $instance['social_linkedin'] ) ) ? esc_url( $instance['social_linkedin'] ) : '';
				$data_social['tumblr']     = ( ! empty( $instance['social_tumblr'] ) ) ? esc_url( $instance['social_tumblr'] ) : '';
				$data_social['flickr']     = ( ! empty( $instance['social_flickr'] ) ) ? esc_url( $instance['social_flickr'] ) : '';
				$data_social['skype']      = ( ! empty( $instance['social_skype'] ) ) ? esc_url( $instance['social_skype'] ) : '';
				$data_social['snapchat']   = ( ! empty( $instance['social_snapchat'] ) ) ? esc_url( $instance['social_snapchat'] ) : '';
				$data_social['myspace']    = ( ! empty( $instance['social_myspace'] ) ) ? esc_url( $instance['social_myspace'] ) : '';
				$data_social['youtube']    = ( ! empty( $instance['social_youtube'] ) ) ? esc_url( $instance['social_youtube'] ) : '';
				$data_social['bloglovin']  = ( ! empty( $instance['social_bloglovin'] ) ) ? esc_url( $instance['social_bloglovin'] ) : '';
				$data_social['digg']       = ( ! empty( $instance['social_digg'] ) ) ? esc_url( $instance['social_digg'] ) : '';
				$data_social['dribbble']   = ( ! empty( $instance['social_dribbble'] ) ) ? esc_url( $instance['social_dribbble'] ) : '';
				$data_social['soundcloud'] = ( ! empty( $instance['social_soundcloud'] ) ) ? esc_url( $instance['social_soundcloud'] ) : '';
				$data_social['vimeo']      = ( ! empty( $instance['social_vimeo'] ) ) ? esc_url( $instance['social_vimeo'] ) : '';
				$data_social['reddit']     = ( ! empty( $instance['social_reddit'] ) ) ? esc_url( $instance['social_reddit'] ) : '';
				$data_social['vkontakte']  = ( ! empty( $instance['social_vk'] ) ) ? esc_url( $instance['social_vk'] ) : '';
				$data_social['telegram']   = ( ! empty( $instance['social_telegram'] ) ) ? esc_url( $instance['social_telegram'] ) : '';
				$data_social['whatsapp']   = ( ! empty( $instance['social_whatsapp'] ) ) ? esc_url( $instance['social_whatsapp'] ) : '';
				$data_social['rss']        = ( ! empty( $instance['social_rss'] ) ) ? esc_url( $instance['social_rss'] ) : '';
			}

			if ( empty( $data_social ) && empty( $instance['content'] ) ) {
				return false;
			}

			$class_name = 'about-bio';
			$social_class_name = 'social-icon-wrap clearfix tooltips-n';

			if ( ! empty( $instance['style'] ) && '2' == $instance['style'] ) {
				$class_name .= ' ' . 'is-centered';
				$social_class_name .= ' ' . 'is-centered';
			}

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'];
			}

			if ( ! empty( $instance['content'] ) ) : ?>
				<div class="<?php echo esc_attr( $class_name ); ?>">
					<?php echo html_entity_decode( esc_html( $instance['content'] ) ); ?>
				</div>
			<?php endif; ?>
			<div class="<?php echo esc_attr( $social_class_name ); ?>">
				<?php echo pixwell_render_social_icons( $data_social, $instance['new_tab'] ); ?>
			</div>

			<?php echo $args['after_widget'];
		}
		
		function update( $new_instance, $old_instance ) {

			$instance                      = $old_instance;
			$instance['title']             = strip_tags( $new_instance['title'] );
			$instance['content']           = esc_html( $new_instance['content'] );
			$instance['new_tab']           = strip_tags( $new_instance['new_tab'] );
			$instance['data_social']       = strip_tags( $new_instance['data_social'] );
			$instance['style']             = esc_attr( $new_instance['style'] );
			$instance['social_facebook']   = esc_html( $new_instance['social_facebook'] );
			$instance['social_twitter']    = esc_html( $new_instance['social_twitter'] );
			$instance['social_instagram']  = esc_html( $new_instance['social_instagram'] );
			$instance['social_pinterest']  = esc_html( $new_instance['social_pinterest'] );
			$instance['social_linkedin']   = esc_html( $new_instance['social_linkedin'] );
			$instance['social_tumblr']     = esc_html( $new_instance['social_tumblr'] );
			$instance['social_flickr']     = esc_html( $new_instance['social_flickr'] );
			$instance['social_skype']      = esc_html( $new_instance['social_skype'] );
			$instance['social_snapchat']   = esc_html( $new_instance['social_snapchat'] );
			$instance['social_myspace']    = esc_html( $new_instance['social_myspace'] );
			$instance['social_youtube']    = esc_html( $new_instance['social_youtube'] );
			$instance['social_bloglovin']  = esc_html( $new_instance['social_bloglovin'] );
			$instance['social_digg']       = esc_html( $new_instance['social_digg'] );
			$instance['social_dribbble']   = esc_html( $new_instance['social_dribbble'] );
			$instance['social_soundcloud'] = esc_html( $new_instance['social_soundcloud'] );
			$instance['social_vimeo']      = esc_html( $new_instance['social_vimeo'] );
			$instance['social_reddit']     = esc_html( $new_instance['social_reddit'] );
			$instance['social_vk']         = esc_html( $new_instance['social_vk'] );
			$instance['social_telegram']   = esc_html( $new_instance['social_telegram'] );
			$instance['social_whatsapp']   = esc_html( $new_instance['social_whatsapp'] );
			$instance['social_rss']        = esc_html( $new_instance['social_rss'] );

			return $instance;
		}


		function form( $instance ) {
			$defaults = array(
				'title'             => esc_html__( 'Find Us on Socials', 'pixwell-core' ),
				'content'           => '',
				'new_tab'           => true,
				'style'             => 1,
				'data_social'       => 2,
				'social_facebook'   => '',
				'social_twitter'    => '',
				'social_instagram'  => '',
				'social_pinterest'  => '',
				'social_linkedin'   => '',
				'social_tumblr'     => '',
				'social_flickr'     => '',
				'social_skype'      => '',
				'social_snapchat'   => '',
				'social_myspace'    => '',
				'social_youtube'    => '',
				'social_bloglovin'  => '',
				'social_digg'       => '',
				'social_dribbble'   => '',
				'social_soundcloud' => '',
				'social_vimeo'      => '',
				'social_reddit'     => '',
				'social_vk'         => '',
				'social_telegram'   => '',
				'social_whatsapp'   => '',
				'social_rss'        => ''
			);
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><strong><?php esc_attr_e('Title :','pixwell-core') ?></strong></label>
				<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php if (!empty($instance['title'])) echo esc_attr($instance['title']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('content')); ?>"><strong><?php esc_attr_e('Short biography (allow HTML):','pixwell-core') ?></strong></label>
				<textarea rows="10" cols="50" id="<?php echo esc_attr($this->get_field_id( 'content' )); ?>" name="<?php echo esc_attr($this->get_field_name('content')); ?>" class="widefat"><?php esc_html_e($instance['content']); ?></textarea>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'style' )); ?>"><strong><?php esc_attr_e('Align Content', 'pixwell-core'); ?></strong></label>
				<select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'style' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'style' )); ?>" >
					<option value="1" <?php if( !empty($instance['style']) && $instance['style'] == '1' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_attr_e('Left', 'pixwell-core'); ?></option>
					<option value="2" <?php if( !empty($instance['style']) && $instance['style'] == '2' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_attr_e('Center', 'pixwell-core'); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'data_social' )); ?>"><strong><?php esc_attr_e('Get Social Profiles form:', 'pixwell-core'); ?></strong></label>
				<select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'data_social' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'data_social' )); ?>" >
					<option value="1" <?php if( !empty($instance['data_social']) && $instance['data_social'] == '1' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_attr_e('Theme Options', 'pixwell-core'); ?></option>
					<option value="2" <?php if( !empty($instance['data_social']) && $instance['data_social'] == '2' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_attr_e('Use Custom', 'pixwell-core'); ?></option>
				</select>
			</p>
			<p><?php echo html_entity_decode(esc_html__( 'To set social link from Theme Options, Navigate to: <strong>Theme Options -> Site Social Profiles</strong>', 'pixwell-core' )); ?></p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('new_tab')); ?>"><?php esc_attr_e('Open Social link in new tab','pixwell-core'); ?></label>
				<input class="widefat" type="checkbox" id="<?php echo esc_attr($this->get_field_id('new_tab')); ?>" name="<?php echo esc_attr($this->get_field_name('new_tab')); ?>" value="true" <?php if (!empty($instance['new_tab'])) echo 'checked="checked"'; ?>  />
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('social_facebook')); ?>"><strong><?php esc_attr_e('Facebook URL:','pixwell-core') ?></strong></label>
				<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('social_facebook')); ?>" name="<?php echo esc_attr($this->get_field_name('social_facebook')); ?>" value="<?php if (!empty($instance['social_facebook'])) echo esc_attr($instance['social_facebook']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('social_twitter')); ?>"><strong><?php esc_html_e('Twitter URL:', 'pixwell-core') ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('social_twitter')); ?>" name="<?php echo esc_attr($this->get_field_name('social_twitter')); ?>" value="<?php esc_html_e($instance['social_twitter']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('social_instagram')); ?>"><strong><?php esc_html_e('Instagram URL:', 'pixwell-core') ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('social_instagram')); ?>" name="<?php echo esc_attr($this->get_field_name('social_instagram')); ?>" value="<?php esc_html_e($instance['social_instagram']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('social_pinterest')); ?>"><strong><?php esc_html_e('Pinterest URL:', 'pixwell-core') ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('social_pinterest')); ?>" name="<?php echo esc_attr($this->get_field_name('social_pinterest')); ?>" value="<?php esc_html_e($instance['social_pinterest']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('social_linkedin')); ?>"><strong><?php esc_html_e('Linkedin URL:', 'pixwell-core') ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('social_linkedin')); ?>" name="<?php echo esc_attr($this->get_field_name('social_linkedin')); ?>" value="<?php esc_html_e($instance['social_linkedin']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('social_tumblr')); ?>"><strong><?php esc_html_e('Tumblr URL:', 'pixwell-core') ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('social_tumblr')); ?>" name="<?php echo esc_attr($this->get_field_name('social_tumblr')); ?>" value="<?php esc_html_e($instance['social_tumblr']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('social_flickr')); ?>"><strong><?php esc_html_e('Flickr URL:', 'pixwell-core') ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('social_flickr')); ?>" name="<?php echo esc_attr($this->get_field_name('social_flickr')); ?>" value="<?php esc_html_e($instance['social_flickr']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('social_skype')); ?>"><strong><?php esc_html_e('Skype URL:', 'pixwell-core') ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('social_skype')); ?>" name="<?php echo esc_attr($this->get_field_name('social_skype')); ?>" value="<?php esc_html_e($instance['social_skype']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('social_snapchat')); ?>"><strong><?php esc_html_e('Snapchat URL:', 'pixwell-core') ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('social_snapchat')); ?>" name="<?php echo esc_attr($this->get_field_name('social_snapchat')); ?>" value="<?php esc_html_e($instance['social_snapchat']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('social_myspace')); ?>"><strong><?php esc_html_e('Myspace URL:', 'pixwell-core') ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('social_myspace')); ?>" name="<?php echo esc_attr($this->get_field_name('social_myspace')); ?>" value="<?php esc_html_e($instance['social_myspace']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('social_youtube')); ?>"><strong><?php esc_html_e('Youtube URL:', 'pixwell-core') ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('social_youtube')); ?>" name="<?php echo esc_attr($this->get_field_name('social_youtube')); ?>" value="<?php esc_html_e($instance['social_youtube']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('social_bloglovin')); ?>"><strong><?php esc_html_e('Bloglovin URL:', 'pixwell-core') ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('social_bloglovin')); ?>" name="<?php echo esc_attr($this->get_field_name('social_bloglovin')); ?>" value="<?php esc_html_e($instance['social_bloglovin']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('social_digg')); ?>"><strong><?php esc_html_e('Digg URL:', 'pixwell-core') ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('social_digg')); ?>" name="<?php echo esc_attr($this->get_field_name('social_digg')); ?>" value="<?php esc_html_e($instance['social_digg']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('social_dribbble')); ?>"><strong><?php esc_html_e('Dribbble URL:', 'pixwell-core') ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('social_dribbble')); ?>" name="<?php echo esc_attr($this->get_field_name('social_dribbble')); ?>" value="<?php esc_html_e($instance['social_dribbble']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('social_soundcloud')); ?>"><strong><?php esc_html_e('Soundcloud URL:', 'pixwell-core') ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('social_soundcloud')); ?>" name="<?php echo esc_attr($this->get_field_name('social_soundcloud')); ?>" value="<?php esc_html_e($instance['social_soundcloud']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('social_vimeo')); ?>"><strong><?php esc_html_e('Vimeo URL:', 'pixwell-core') ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('social_vimeo')); ?>" name="<?php echo esc_attr($this->get_field_name('social_vimeo')); ?>" value="<?php esc_html_e($instance['social_vimeo']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('social_reddit')); ?>"><strong><?php esc_html_e('Reddit URL:', 'pixwell-core') ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('social_reddit')); ?>" name="<?php echo esc_attr($this->get_field_name('social_reddit')); ?>" value="<?php esc_html_e($instance['social_reddit']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('social_vk')); ?>"><strong><?php esc_html_e('VKontakte URL:', 'pixwell-core') ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('social_vk')); ?>" name="<?php echo esc_attr($this->get_field_name('social_vk')); ?>" value="<?php esc_html_e($instance['social_vk']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('social_telegram')); ?>"><strong><?php esc_html_e('Telegram URL:', 'pixwell-core') ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('social_telegram')); ?>" name="<?php echo esc_attr($this->get_field_name('social_telegram')); ?>" value="<?php esc_html_e($instance['social_telegram']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('social_whatsapp')); ?>"><strong><?php esc_html_e('Whatsapp URL:', 'pixwell-core') ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('social_whatsapp')); ?>" name="<?php echo esc_attr($this->get_field_name('social_whatsapp')); ?>" value="<?php esc_html_e($instance['social_whatsapp']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('social_rss')); ?>"><strong><?php esc_html_e('RSS URL:', 'pixwell-core') ?></strong></label>
				<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('social_rss')); ?>" name="<?php echo esc_attr($this->get_field_name('social_rss')); ?>" value="<?php esc_html_e($instance['social_rss']); ?>"/>
			</p>
		<?php
		}
	}
endif;