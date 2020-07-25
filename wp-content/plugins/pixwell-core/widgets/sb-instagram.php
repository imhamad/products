<?php
/** Instagram grid */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'pixwell_widget_sb_instagram' ) ) :
	class pixwell_widget_sb_instagram extends WP_Widget {

		function __construct() {
			$widget_ops = array(
				'classname'   => 'widget-sb-instagram',
				'description' => esc_attr__( '[Sidebar Widget] Display Instagram grid images in the sidebar.', 'pixwell-core' )
			);
			parent::__construct( 'sb_instagram', esc_attr__( '- [Sidebar] Instagram Grid -', 'pixwell-core' ), $widget_ops );
		}

		function widget( $args, $instance ) {

			echo $args['before_widget'];

			$instance = wp_parse_args( $instance, array(
				'title'           => '',
				'user_name'       => '',
				'instagram_token' => '',
				'tag'             => '',
				'total_images'    => 9,
				'total_cols'      => 'rb-c3',
				'footer_intro'    => '',
				'footer_url'      => '#',
			) );

			$instance['title']    = apply_filters( 'widget_title', $instance['title'] );
			$instance['cache_id'] = $args['widget_id'];
			$lazyload             = pixwell_get_option( 'lazy_load' );

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'];
			} ?>
			<div class="instagram-grid layout-default">
				<?php if ( ! empty( $instance['instagram_token'] ) ) {
					$data_images = pixwell_data_instagram_token( $instance );
				} elseif ( ! empty( $instance['user_name'] ) ) {
					$data_images = pixwell_data_instagram_no_token( $instance );
				}

				if ( empty( $data_images ) ) :
					echo '<div class="rb-error">' . esc_html__( 'Something was wrong! Try to remove the widget and re-add again.', 'pixwell-core' ) . '</div>';
				elseif ( is_string( $data_images ) || ! is_array( $data_images ) ) :
					echo '<div class="rb-error">' . $data_images . '</div>';
				else : ?>
					<div class="grid-holder">
						<?php $data_images = array_slice( $data_images, 0, $instance['total_images'] );
						foreach ( $data_images as $image ) :  ?>
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
					<?php if ( ! empty( $instance['footer_intro'] ) ) : ?>
						<div class="grid-footer">
							<a href="<?php echo esc_url( $instance['footer_url'] ); ?>" target="_blank"><?php echo wp_kses_post( $instance['footer_intro'] ) ?></a>
						</div>
					<?php endif;
				endif; ?>
			</div>
			<?php echo $args['after_widget'];
		}

		function update( $new_instance, $old_instance ) {

			$instance                    = $old_instance;
			$instance['title']           = esc_attr( $new_instance['title'] );
			$instance['user_name']       = esc_attr( $new_instance['user_name'] );
			$instance['instagram_token'] = esc_attr( $new_instance['instagram_token'] );
			$instance['tag']             = esc_attr( $new_instance['tag'] );
			$instance['total_images']    = absint( esc_attr( $new_instance['total_images'] ) );
			$instance['total_cols']      = esc_attr( $new_instance['total_cols'] );
			$instance['footer_intro']    = wp_kses_post( $new_instance['footer_intro'] );
			$instance['footer_url']      = esc_url( $new_instance['footer_url'] );

			delete_transient( 'pixwell_instagram_cache' );

			return $instance;
		}


		function form( $instance ) {

			$defaults = array(
				'title'           => esc_html__( 'Instagram', 'pixwell-core' ),
				'user_name'       => '',
				'instagram_token' => '',
				'tag'             => '',
				'total_images'    => 6,
				'total_cols'      => 'rb-c3',
				'footer_intro'    => esc_html__( 'Follow Us on @ Instagram', 'pixwell-core' ),
				'footer_url'      => '#',
			);

			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><strong><?php esc_html_e( 'Title:', 'pixwell-core' ) ?></strong></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'user_name' ) ); ?>"><?php esc_html_e( '@Username or #Tag:', 'pixwell-core' ) ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'user_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'user_name' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['user_name'] ); ?>"/>
				<em><?php esc_html_e( 'Input a username, tags (maximum is 12 images) or Instagram token. Leave blank the username form if you use Instagram token.' ); ?></em>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'instagram_token' ) ); ?>"><?php esc_html_e( 'or input Instagram Token:', 'pixwell-core' ) ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'instagram_token' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'instagram_token' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['instagram_token'] ); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'tag' ) ); ?>"><?php echo esc_html__( '#Tag (this option only for Instagram Token):', 'pixwell-core' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'tag' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tag' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['tag'] ); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'total_images' ) ); ?>"><?php esc_html_e( 'Total Images:', 'pixwell-core' ) ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'total_images' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'total_images' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['total_images'] ); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'total_cols' ) ); ?>"><?php esc_html_e( 'Number of Columns:', 'pixwell-core' ); ?></label>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'total_cols' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'total_cols' ) ); ?>">
					<option value="rb-c2" <?php if ( ! empty( $instance['total_cols'] ) && $instance['total_cols'] == 'rb-c2' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e( '2 columns', 'pixwell-core' ); ?></option>
					<option value="rb-c3" <?php if ( ! empty( $instance['total_cols'] ) && $instance['total_cols'] == 'rb-c3' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e( '3 columns', 'pixwell-core' ); ?></option>
					<option value="rb-c4" <?php if ( ! empty( $instance['total_cols'] ) && $instance['total_cols'] == 'rb-c4' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e( '4 columns', 'pixwell-core' ); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'footer_intro' ) ); ?>"><?php esc_html_e( 'Footer Description (Allow Raw HTMl):', 'pixwell-core' ) ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'footer_intro' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'footer_intro' ) ); ?>" type="text" value="<?php echo wp_kses_post( $instance['footer_intro'] ); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'footer_url' ) ); ?>"><strong><?php esc_html_e( 'Footer Link:', 'pixwell-core' ) ?></strong></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'footer_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'footer_url' ) ); ?>" type="text" value="<?php echo esc_url( $instance['footer_url'] ); ?>"/>
			</p>
		<?php
		}
	}
endif;