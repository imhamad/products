<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'pixwell_widget_tweets' ) ) :
	class pixwell_widget_tweets extends WP_Widget {

		function __construct() {
			$widget_ops = array(
				'classname'   => 'widget-tweets',
				'description' => esc_html__( '[Sidebar Widget] Display latest tweets in the sidebar. This widget requests to install oAuth Twitter Feed for Developers plugin for working.', 'pixwell-core' )
			);
			parent::__construct( 'twitter_tweets', esc_html__( '- [Sidebar] Twitter Tweets -', 'pixwell-core' ), $widget_ops );
		}

		function widget( $args, $instance ) {

			echo $args['before_widget'];

			$instance = wp_parse_args( $instance, array(
				'title'        => '',
				'twitter_user' => '',
				'num_tweets'   => '',
			) );

			$instance['title'] = apply_filters( 'widget_title', $instance['title'] );
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'];
			} ?>
			<ul class="twitter-widget-inner">
				<?php if ( function_exists( 'getTweets' ) ) :
					
					$tweets_data = getTweets( $instance['num_tweets'], $instance['twitter_user'] );
					if ( ! empty( $tweets_data ) && is_array( $tweets_data ) && empty( $tweets_data['error'] ) ) :
						foreach ( $tweets_data as $tweet ) :
							$tweet['text'] = preg_replace( '/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i', "<a href=\"$1\" class=\"twitter-link\">$1</a>", $tweet['text'] );
							$tweet['text'] = preg_replace( '/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i', "<a href=\"http://$1\" class=\"twitter-link\">$1</a>", $tweet['text'] );
							$tweet['text'] = preg_replace( "/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i", "<a href=\"mailto://$1\" class=\"twitter-link\">$1</a>", $tweet['text'] );
							$tweet['text'] = preg_replace( '/([\.|\,|\:|\>|\{|\(]?)#{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/#search?q=$2\" class=\"twitter-link\">#$2</a>$3 ", $tweet['text'] );
							$tweet['text'] = str_replace( 'RT', ' ', $tweet['text'] );

							$time = strtotime( $tweet['created_at'] );
							if ( ( abs( time() - $time ) ) < 86400 ) {
								$h_time = sprintf( esc_html__( '%s ago', 'pixwell-core' ), human_time_diff( $time ) );
							} else {
								$h_time = date( 'M j, Y', $time );
							} ?>

							<li class="twitter-content entry-summary">
								<p><?php echo do_shortcode( $tweet['text'] ); ?></p>
								<em class="twitter-timestamp"><?php echo esc_attr( $h_time ) ?></em>
							</li>

						<?php endforeach; ?>
					<?php
					else :
						echo '<li class="rb-error">' . $tweets_data['error'] . '</li>';
					endif; ?>
				<?php else : echo '<li class="rb-error">' . esc_html__( 'Please install plugin name "oAuth Twitter Feed for Developers', 'pixwell-core' ) . '</li>'; ?>
				<?php endif; ?>

			</ul>
			<?php echo $args['after_widget'];
		}

		function update( $new_instance, $old_instance ) {

			$instance = $old_instance;

			$instance['title']        = strip_tags( $new_instance['title'] );
			$instance['twitter_user'] = strip_tags( $new_instance['twitter_user'] );
			$instance['num_tweets']   = absint( strip_tags( $new_instance['num_tweets'] ) );

			return $instance;
		}

		function form( $instance ) {

			$defaults = array(
				'title'        => esc_html__( 'Latest Tweets', 'pixwell-core' ),
				'twitter_user' => '',
				'num_tweets'   => 4
			);
			$instance = wp_parse_args( (array) $instance, $defaults );    ?>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><strong><?php esc_html_e('Title:', 'pixwell-core');?></strong></label>
				<input type="text" class="widefat"  id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($instance['title']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'twitter_user' )); ?>"><strong><?php esc_html_e('Twitter Name:', 'pixwell-core');?></strong></label>
				<input type="text" class="widefat"  id="<?php echo esc_attr($this->get_field_id( 'twitter_user' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'twitter_user' )); ?>" value="<?php echo esc_attr($instance['twitter_user']); ?>"/>
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'num_tweets' )); ?>"><strong><?php esc_html_e('Number of Tweets:', 'pixwell-core');?></strong></label>
				<input type="text" class="widefat"  id="<?php echo esc_attr($this->get_field_id( 'num_tweets' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'num_tweets' )); ?>" value="<?php echo esc_attr( $instance['num_tweets'] ); ?>"/>
			</p>
			<p><a href="http://dev.twitter.com/apps" target="_blank"><?php esc_html_e('Please create your Twitter App ', 'pixwell-core'); ?></a><?php esc_html_e(' and install ','pixwell-core'); ?><a href="https://wordpress.org/plugins/oauth-twitter-feed-for-developers/"><?php esc_html_e('"oAuth Twitter Feed for Developers" Plugin','pixwell-core');?></a></p>
		<?php
		}
	}
endif;
