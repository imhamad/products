<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'pixwell_widget_sb_post' ) ) {
	class pixwell_widget_sb_post extends WP_Widget {

		function __construct() {
			$widget_ops = array(
				'classname'   => 'widget-post',
				'description' => esc_html__( '[Sidebar Widget] Display blog listings with custom query in the sidebar.', 'pixwell-core' )
			);
			parent::__construct( 'sb_post', esc_html__( '- [Sidebar] Post Listing -', 'pixwell-core' ), $widget_ops );
		}

		function widget( $args, $instance ) {

			if ( ! function_exists( 'pixwell_widget_post' ) ) {
				return;
			}

			echo $args['before_widget'];
			$instance = wp_parse_args( $instance, array(
				'title'          => '',
				'style'          => '',
				'category'       => '',
				'categories'     => '',
				'tags'           => '',
				'format'         => 0,
				'posts_per_page' => 4,
				'offset'         => 0,
				'order'        => 'date'
			) );

			if ( is_single() ) {
				$current_post = get_the_ID();
				if ( ! empty( $current_post ) ) {
					$instance['post_not_in'] = $current_post;
				}
			}

			$instance['title']    = apply_filters( 'widget_title', $instance['title'] );
			$instance['name'] = $args['widget_id'];

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'];
			} ?>
			<div class="widget-post-content">
				<?php pixwell_widget_post( $instance ); ?>
			</div>
			<?php echo $args['after_widget'];
		}

		function update( $new_instance, $old_instance ) {

			$instance = $old_instance;

			$instance['title']          = strip_tags( $new_instance['title'] );
			$instance['style']          = strip_tags( $new_instance['style'] );
			$instance['category']       = strip_tags( $new_instance['category'] );
			$instance['categories']     = strip_tags( $new_instance['categories'] );
			$instance['tags']           = strip_tags( $new_instance['tags'] );
			$instance['format']         = strip_tags( $new_instance['format'] );
			$instance['posts_per_page'] = absint( strip_tags( $new_instance['posts_per_page'] ) );
			$instance['offset']         = absint( strip_tags( $new_instance['offset'] ) );
			$instance['order']        = strip_tags( $new_instance['order'] );

			return $instance;
		}

		function form( $instance ) {
			$defaults = array(
				'title'          => esc_html__( 'Latest Posts', 'pixwell-core' ),
				'style'          => '',
				'category'       => '',
				'categories'     => '',
				'tags'           => '',
				'format'         => 0,
				'posts_per_page' => 4,
				'offset'         => 0,
				'order'        => 'date_post'
			);
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><strong><?php esc_html_e('Widget Title','pixwell-core') ?></strong></label>
				<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php if(!empty($instance['title'])) echo esc_attr($instance['title']); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'style' )); ?>"><strong><?php esc_html_e('Post Layout', 'pixwell-core'); ?></strong></label>
				<select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'style' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'style' )); ?>" >
					<option value="1" <?php if( !empty($instance['style']) && $instance['style'] == '1' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Style 1 (List)', 'pixwell-core'); ?></option>
					<option value="2" <?php if( !empty($instance['style']) && $instance['style'] == '2' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Style 2 (Grid)', 'pixwell-core'); ?></option>
					<option value="3" <?php if( !empty($instance['style']) && $instance['style'] == '3' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Style 3 (List only Title)', 'pixwell-core'); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('category')); ?>"><strong><?php esc_html_e('Category Filter', 'pixwell-core'); ?></strong></label>
				<select class="widefat" id="<?php echo esc_attr($this->get_field_id('category')); ?>" name="<?php echo esc_attr($this->get_field_name('category')); ?>">
					<option value='all' <?php if ($instance['category'] == 'all') echo 'selected="selected"'; ?>><?php esc_html_e('All Categories', 'pixwell-core'); ?></option>
					<?php $categories = get_categories('type=post'); foreach ($categories as $category) { ?><option  value='<?php echo esc_attr($category->term_id); ?>' <?php if ($instance['category'] == $category->term_id) echo 'selected="selected"'; ?>><?php echo esc_attr($category->cat_name); ?></option><?php } ?>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'categories' )); ?>"><strong><?php esc_html_e('Multiple Category Filter','pixwell-core') ?></strong></label>
				<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'categories' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'categories' )); ?>" value="<?php if( !empty($instance['categories']) ) echo esc_attr($instance['categories']); ?>" />
			</p>
			<p><?php esc_html_e('Optional: Input category IDs, separated by comma (ie: 1,2). This option will override the category option.', 'pixwell-core'); ?></p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'tags' )); ?>"><strong><?php esc_html_e('Post Tags','pixwell-core') ?></strong></label>
				<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'tags' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'tags' )); ?>" value="<?php if( !empty($instance['tags']) ) echo esc_attr($instance['tags']); ?>" />
			</p>
			<p><?php esc_html_e( ' (Optional: Input post tags IDs, separate tags with comma (ie: tag1,tag2)', 'pixwell-core' ); ?></p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'format' )); ?>"><strong><?php esc_html_e('Post Format Filter', 'pixwell-core'); ?></strong></label>
				<select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'format' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'format' )); ?>" >
					<option value="0" <?php if( !empty($instance['format']) && $instance['format'] == '0' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('-- All --', 'pixwell-core'); ?></option>
					<option value="default" <?php if( !empty($instance['format']) && $instance['format'] == 'default' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Default', 'pixwell-core'); ?></option>
					<option value="gallery" <?php if( !empty($instance['format']) && $instance['format'] == 'gallery' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Gallery', 'pixwell-core'); ?></option>
					<option value="video" <?php if( !empty($instance['format']) && $instance['format'] == 'video' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Video', 'pixwell-core'); ?></option>
					<option value="audio" <?php if( !empty($instance['format']) && $instance['format'] == 'audio' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Audio', 'pixwell-core'); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'posts_per_page' )); ?>"><strong><?php esc_html_e('Total Posts','pixwell-core') ?></strong></label>
				<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'posts_per_page' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'posts_per_page' )); ?>" value="<?php if( !empty($instance['posts_per_page']) ) echo esc_attr($instance['posts_per_page']); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'offset' )); ?>"><strong><?php esc_html_e('Post Offset','pixwell-core') ?></strong></label>
				<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'offset' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'offset' )); ?>" value="<?php if( !empty($instance['offset']) ) echo esc_attr($instance['offset']); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'order' )); ?>"><strong><?php esc_html_e('Order By', 'pixwell-core'); ?></strong></label>
				<select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'order' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'order' )); ?>" >
					<option value="date_post" <?php if( !empty($instance['order']) && $instance['order'] == 'date_post' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Latest Post', 'pixwell-core'); ?></option>
					<option value="update" <?php if( !empty($instance['order']) && $instance['order'] == 'update' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Last Updated', 'pixwell-core'); ?></option>
					<option value="comment_count" <?php if( !empty($instance['order']) && $instance['order'] == 'comment_count' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Popular Comment', 'pixwell-core'); ?></option>
					<option value="popular" <?php if( !empty($instance['order']) && $instance['order'] == 'popular' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Popular (Require Post Views Plugin)', 'pixwell-core'); ?></option>
					<option value="popular_m" <?php if( !empty($instance['order']) && $instance['order'] == 'popular_m' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Popular Last 30 Days (Require Post Views Plugin)', 'pixwell-core'); ?></option>
					<option value="popular_w" <?php if( !empty($instance['order']) && $instance['order'] == 'popular_w' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Popular Last 7 Days (Require Post Views Plugin)', 'pixwell-core'); ?></option>
					<option value="top_review" <?php if( !empty($instance['order']) && $instance['order'] == 'top_review' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Top Review', 'pixwell-core'); ?></option>
					<option value="last_review" <?php if( !empty($instance['order']) && $instance['order'] == 'last_review' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Latest Review', 'pixwell-core'); ?></option>
					<option value="post_type" <?php if( !empty($instance['order']) && $instance['order'] == 'post_type' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Post Type', 'pixwell-core'); ?></option>
					<option value="rand" <?php if( !empty($instance['order']) && $instance['order'] == 'rand' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Random Post', 'pixwell-core'); ?></option>
					<option value="author" <?php if( !empty($instance['author']) && $instance['order'] == 'author' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('Author', 'pixwell-core'); ?></option>
					<option value="alphabetical_order_asc" <?php if( !empty($instance['order']) && $instance['order'] == 'alphabetical_order_asc' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('alphabetical A->Z Posts', 'pixwell-core'); ?></option>
					<option value="alphabetical_order_decs" <?php if( !empty($instance['order']) && $instance['order'] == 'alphabetical_order_decs' ) echo "selected=\"selected\""; else echo ""; ?>><?php esc_html_e('alphabetical Z->A Posts', 'pixwell-core'); ?></option>
				</select>
			</p>
		<?php
		}
	}
}
