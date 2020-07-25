<?php
/** instagram widget */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'pixwell_widget_fw_instagram' ) ) :
	class pixwell_widget_fw_instagram extends WP_Widget {

		function __construct() {
			$widget_ops = array(
				'classname'   => 'widget-fw-instagram',
				'description' => esc_attr__( 'Display Instagram images grid layout in the FullWidth section.', 'pixwell-core' )
			);
			parent::__construct( 'fw_instagram', esc_attr__( '- [FullWidth] Instagram Grid -', 'pixwell-core' ), $widget_ops );
		}

		function widget( $args, $instance ) {

			echo $args['before_widget'];

			$instance = wp_parse_args( $instance, array(
				'header_intro'    => esc_html__( '<span>Follow @ Instagram</span><h6>Our Profile</h6>', 'pixwell-core' ),
				'url'             => '#',
				'user_name'       => '',
				'instagram_token' => '',
				'tag'             => '',
				'grid_layout'     => 'rb-cmix',
				'total_images'    => 7,
				'total_cols'      => 'rb-c7',
				'layout'          => 'full',
			) );

			$instance['cache_id'] = $args['widget_id'];
			$flag                 = true;
			$classes              = array();
			$lazyload             = pixwell_get_option( 'lazy_load' );

			if ( $instance['grid_layout'] == 'rb-cmix' ) {
				$instance['total_images'] = 7;
				if ( empty( $instance['header_intro'] ) ) {
					$instance['total_images'] = 8;
				}
				$instance['total_cols'] = 'rb-masonry';
				$classes[]              = 'instagram-grid layout-grid grid-masonry is-wrap rbc-container rb-p20-gutter';
			} elseif ( $instance['grid_layout'] == 'rb-cfmix' ) {
				$instance['total_images'] = 10;
				if ( empty( $instance['header_intro'] ) ) {
					$instance['total_images'] = 11;
				}
				$instance['total_cols'] = 'rb-masonry';
				$classes[]              = 'instagram-grid layout-grid grid-fmasonry is-wide';
			} else {
				$flag      = false;
				$classes[] = 'instagram-grid layout-default grid-default';
				if ( 'wrapper' == $instance['layout'] ) {
					$classes[] = 'is-wrap rbc-container';
				} else {
					$classes[] = 'is-wide';
				}
			}
			$classes = join( ' ', $classes ); ?>
			<div class="<?php echo esc_attr( $classes ); ?>">
				<?php if ( ! empty( $instance['header_intro'] ) && ! $flag ) : ?>
					<div class="grid-header">
						<a href="<?php echo esc_url( $instance['url'] ); ?>" target="_blank"><?php echo wp_kses_post( $instance['header_intro'] ) ?></a>
					</div>
				<?php endif;
				if ( ! empty( $instance['instagram_token'] ) ) {
					$data_images = pixwell_data_instagram_token( $instance );
				} elseif ( ! empty( $instance['user_name'] ) ) {
					$data_images = pixwell_data_instagram_no_token( $instance );
				}
				if ( empty( $data_images ) ) {
					echo '<div class="rb-error">' . esc_html__( 'Something was wrong! Try to remove the widget and re-add again.', 'pixwell-core' ) . '</div>';

					return;
				}
				if ( is_string( $data_images ) || ! is_array( $data_images ) ) {
					echo '<div class="rb-error">' . $data_images . '</div>';
					return;
				} ?>
				<div class="grid-holder">
					<?php $data_images = array_slice( $data_images, 0, $instance['total_images'] );
					foreach ( $data_images as $image ) :?>
						<?php if ( true == $flag && ! empty( $instance['header_intro'] ) ) : ?>
							<div class="grid-el <?php echo esc_attr( $instance['total_cols'] ) ?>">
								<div class="instagram-box box-intro">
									<a href="<?php echo esc_html( $instance['url'] ); ?>" target="_blank"></a>
									<div class="box-content">
										<i class="rbi rbi-instagram"></i><?php echo wp_kses_post( $instance['header_intro'] ); ?>
									</div>
								</div>
							</div>
							<?php $flag = false;
						endif; ?>
						<div class="grid-el <?php echo esc_attr( $instance['total_cols'] ) ?>">
							<div class="instagram-box">
								<a href="<?php echo esc_html( $image['link'] ); ?>" target="_blank">
									<?php if ( empty( $lazyload ) ) : ?>
										<img src="<?php echo esc_url( $image['thumbnail_src'] ) ?>" alt="<?php echo esc_attr( $image['caption'] ); ?>">
									<?php else : ?>
										<span class="s-lazyload rb-iwrap"><img class="rb-lazyload" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=" data-src="<?php echo esc_url( $image['thumbnail_src'] ) ?>" alt="<?php echo esc_attr( $image['caption'] ); ?>"></span>
									<?php endif; ?>
								</a>
								<div class="box-content">
									<?php if ( ! empty( $image['likes'] ) ) : ?>
										<span class="likes"><i class="rbi rbi-heart"></i><?php echo esc_html( $image['likes'] ); ?></span>
									<?php endif;
									if ( ! empty( $image['comments'] ) ) : ?>
										<span class="comments"><i class="rbi rbi-chat-bubble"></i><?php echo esc_html( $image['comments'] ); ?></span>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php echo $args['after_widget'];
		}

		function update( $new_instance, $old_instance ) {

			$instance                    = $old_instance;
			$instance['header_intro']    = wp_kses_post( $new_instance['header_intro'] );
			$instance['url']             = esc_html( $new_instance['url'] );
			$instance['user_name']       = esc_attr( $new_instance['user_name'] );
			$instance['instagram_token'] = esc_attr( $new_instance['instagram_token'] );
			$instance['tag']             = esc_attr( $new_instance['tag'] );
			$instance['grid_layout']     = esc_attr( $new_instance['grid_layout'] );
			$instance['total_images']    = absint( esc_attr( $new_instance['total_images'] ) );
			$instance['total_cols']      = esc_attr( $new_instance['total_cols'] );
			$instance['layout']          = esc_attr( $new_instance['layout'] );

			delete_transient( 'pixwell_instagram_cache' );

			return $instance;
		}


		function form( $instance ) {

			$defaults = array(
				'header_intro'    => esc_html__( '<span>Follow @ Instagram</span><h6>Your Name</h6>', 'pixwell-core' ),
				'url'             => '#',
				'user_name'       => '',
				'instagram_token' => '',
				'tag'             => '',
				'grid_layout'     => 'rb-cmix',
				'total_images'    => 7,
				'total_cols'      => 'rb-c7',
				'layout'          => 'full',
			);

			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'header_intro' ) ); ?>"><strong><?php esc_html_e( 'Header Intro (Allow Raw HTML):', 'pixwell-core' ) ?></strong></label>
				<textarea rows="10" cols="50" id="<?php echo esc_attr($this->get_field_id( 'header_intro' )); ?>" name="<?php echo esc_attr($this->get_field_name('header_intro')); ?>" class="widefat"><?php echo wp_kses_post($instance['header_intro']); ?></textarea>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>"><strong><?php esc_html_e( 'User Link:', 'pixwell-core' ) ?></strong></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'url' ) ); ?>" type="text" value="<?php echo esc_html( $instance['url'] ); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'user_name' ) ); ?>"><?php esc_html_e( '@Username or #Tag', 'pixwell-core' ) ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'user_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'user_name' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['user_name'] ); ?>"/>
				<em><?php esc_html_e( 'Input a username, tags (maximum is 12 images) or Instagram token. Leave blank the username form if you use Instagram token.' ); ?></em>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'instagram_token' ) ); ?>"><?php esc_html_e( 'or input Instagram Token', 'pixwell-core' ) ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'instagram_token' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'instagram_token' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['instagram_token'] ); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'tag' ) ); ?>"><?php echo esc_html__( '#Image Tags (only work with for Instagram Token)', 'pixwell-core' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'tag' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tag' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['tag'] ); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'grid_layout' ) ); ?>"><?php esc_html_e( 'Grid Layout', 'pixwell-core' ); ?></label>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'grid_layout' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'grid_layout' ) ); ?>">
					<option value="rb-cmix" <?php if ( ! empty( $instance['grid_layout'] ) && $instance['grid_layout'] == 'rb-cmix' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e( 'Wrapper Masonry', 'pixwell-core' ); ?></option>
					<option value="rb-cfmix" <?php if ( ! empty( $instance['grid_layout'] ) && $instance['grid_layout'] == 'rb-cfmix' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e( 'Wide Masonry', 'pixwell-core' ); ?></option>
					<option value="rb-grid" <?php if ( ! empty( $instance['grid_layout'] ) && $instance['grid_layout'] == 'rb-grid' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e( 'Default Grid', 'pixwell-core' ); ?></option>
				</select>
			</p>
			<p><em><?php esc_html_e('Below options will only apply to default grid layout.'); ?></em></p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'total_images' ) ); ?>"><?php esc_html_e( 'Default Grid - Total Images', 'pixwell-core' ) ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'total_images' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'total_images' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['total_images'] ); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'total_cols' ) ); ?>"><?php esc_html_e( 'Default Grid - Columns', 'pixwell-core' ); ?></label>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'total_cols' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'total_cols' ) ); ?>">
					<option value="rb-c5" <?php if ( ! empty( $instance['total_cols'] ) && $instance['total_cols'] == 'rb-c5' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e( '5 columns', 'pixwell-core' ); ?></option>
					<option value="rb-c6" <?php if ( ! empty( $instance['total_cols'] ) && $instance['total_cols'] == 'rb-c6' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e( '6 columns', 'pixwell-core' ); ?></option>
					<option value="rb-c7" <?php if ( ! empty( $instance['total_cols'] ) && $instance['total_cols'] == 'rb-c7' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e( '7 columns', 'pixwell-core' ); ?></option>
					<option value="rb-c8" <?php if ( ! empty( $instance['total_cols'] ) && $instance['total_cols'] == 'rb-c8' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e( '8 columns', 'pixwell-core' ); ?></option>
					<option value="rb-c9" <?php if ( ! empty( $instance['total_cols'] ) && $instance['total_cols'] == 'rb-c9' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e( '9 columns', 'pixwell-core' ); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php esc_html_e( 'Default Grid - Wrapper', 'pixwell-core' ); ?></label>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>">
					<option value="full" <?php if ( ! empty( $instance['layout'] ) && $instance['layout'] == 'full' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e( 'FullWidth', 'pixwell-core' ); ?></option>
					<option value="wrapper" <?php if ( ! empty( $instance['layout'] ) && $instance['layout'] == 'wrapper' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e( 'Has Wrapper', 'pixwell-core' ); ?></option>
				</select>
			</p>
		<?php
		}
	}

endif;