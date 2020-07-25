<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'pixwell_widget_sb_flickr' ) ) :
	class pixwell_widget_sb_flickr extends WP_Widget {

		function __construct() {
			$widget_ops = array(
				'classname'   => 'widget-flickr',
				'description' => esc_html__( '[Sidebar Widget] Display flickr grid images in the sidebar.', 'pixwell-core' )
			);
			parent::__construct( 'sb_flickr', esc_html__( '- [Sidebar] Flickr Grid -', 'pixwell-core' ), $widget_ops );
		}

		function widget( $args, $instance ) {

			echo $args['before_widget'];

			$instance = wp_parse_args( $instance, array(
				'title'        => '',
				'flickr_id'    => '',
				'tags'         => '',
				'total_images' => '',
				'total_cols'   => ''
			) );

			$instance['title']    = apply_filters( 'widget_title', $instance['title'] );
			$instance['cache_id'] = $args['widget_id'];
			$flickr_data = pixwell_data_flickr( $instance );

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'];
			} ?>
			<div class="flickr-grid layout-default clearfix">
				<div class="grid-holder">
					<?php if ( ! empty( $flickr_data ) && is_array( $flickr_data ) ) : ?>
						<?php foreach ( $flickr_data as $item ): ?>
							<div class="grid-el <?php echo esc_attr( $instance['total_cols'] ) ?>">
								<a href="<?php echo esc_url( $item['link'] ); ?>">
									<img src="<?php echo esc_url( $item['media'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>"/>
								</a>
							</div>
						<?php endforeach; ?>
					<?php else : ?>
						<div class="rb-error"><?php esc_html_e( 'Configuration error or no pictures...', 'pixwell-core' ) ?></div>
					<?php endif; ?>
				</div>
			</div>
			<?php echo $args['after_widget'];
		}

		function update( $new_instance, $old_instance ) {
			$instance                 = $old_instance;
			$instance['title']        = strip_tags( $new_instance['title'] );
			$instance['flickr_id']    = strip_tags( $new_instance['flickr_id'] );
			$instance['total_images'] = absint( strip_tags( $new_instance['total_images'] ) );
			$instance['tags']         = strip_tags( $new_instance['tags'] );
			$instance['total_cols']   = strip_tags( $new_instance['total_cols'] );

			delete_transient( 'pixwell_flickr_cache' );

			return $instance;
		}

		function form( $instance ) {
			$defaults = array(
				'title'        => esc_html__( 'Flickr Gallery', 'pixwell-core' ),
				'flickr_id'    => '',
				'total_images' => 9,
				'tags'         => '',
				'total_cols'   => 'rb-c3'

			);
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><strong><?php esc_html_e( 'Title:', 'pixwell-core' ) ?></strong></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('flickr_id')); ?>"><strong><?php esc_html_e('Flickr User ID:', 'pixwell-core') ?></strong></label>
				<input class="widefat" id="<?php echo esc_attr($this->get_field_id('flickr_id')); ?>" name="<?php echo esc_attr($this->get_field_name('flickr_id')); ?>" type="text" value="<?php echo esc_attr($instance['flickr_id']); ?>"/>
			</p>
			<p><a href="http://www.idgettr.com" target="_blank"><?php esc_html_e('Get Flickr Id','pixwell-core') ?></a></p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('total_images')); ?>"><strong><?php esc_html_e('Limit Image Number:', 'pixwell-core') ?></strong></label>
				<input class="widefat" id="<?php echo esc_attr($this->get_field_id('total_images')); ?>" name="<?php echo esc_attr($this->get_field_name('total_images')); ?>" type="text" value="<?php echo esc_attr($instance['total_images']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('tags')); ?>"><?php esc_html_e('Tags (optional, Separate tags with comma. e.g. tag1,tag2):', 'pixwell-core'); ?></label>
				<input class="widefat" id="<?php echo esc_attr($this->get_field_id('tags')); ?>" name="<?php echo esc_attr($this->get_field_name('tags')); ?>" type="text" value="<?php echo esc_attr($instance['tags']); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'total_cols' )); ?>"><strong><?php esc_html_e('Number of total_cols:', 'pixwell-core'); ?></strong></label>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'total_cols' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'total_cols' ) ); ?>">
					<option value="rb-c2" <?php if ( ! empty( $instance['total_cols'] ) && $instance['total_cols'] == 'rb-c2' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e( '2 columns', 'pixwell-core' ); ?></option>
					<option value="rb-c3" <?php if ( ! empty( $instance['total_cols'] ) && $instance['total_cols'] == 'rb-c3' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e( '3 columns', 'pixwell-core' ); ?></option>
					<option value="rb-c4" <?php if ( ! empty( $instance['total_cols'] ) && $instance['total_cols'] == 'rb-c4' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e( '4 columns', 'pixwell-core' ); ?></option>
				</select>
			</p>
		<?php
		}
	}
endif;
