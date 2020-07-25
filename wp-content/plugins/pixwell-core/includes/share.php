<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return string
 * twitter user name
 */
if ( ! function_exists( 'pixwell_get_twitter_name' ) ) {
	function pixwell_get_twitter_name() {
		$twitter_url = get_the_author_meta( 'twitter' );
		if ( ! empty( $twitter_url ) ) {
			$pos          = strpos( $twitter_url, 'twitter.com/' );
			$twitter_user = substr( $twitter_url, intval( $pos ) + 12 );
			$twitter_user = str_replace( '/', '', $twitter_user );
			$twitter_user = trim( $twitter_user );
		};

		if ( empty( $twitter_user ) ) {
			$twitter_user = get_bloginfo( 'name' );
		}

		return $twitter_user;
	}
}

/**
 * ember twitter script
 */
if ( ! function_exists( 'pixwell_twitter_embed_script' ) ) :
	function pixwell_twitter_embed_script() {
		if ( is_single() && ! wp_doing_ajax() ) :
			$top          = pixwell_get_option( 'share_top_twitter' );
			$bottom       = pixwell_get_option( 'share_bottom_twitter' );
			$left         = pixwell_get_option( 'share_left_twitter' );
			$share_left   = pixwell_get_option( 'share_left' );
			$share_top    = pixwell_get_option( 'share_top' );
			$share_bottom = pixwell_get_option( 'share_bottom' );

			if ( ( empty( $top ) || empty( $share_top ) ) && ( empty( $bottom ) || empty( $share_bottom ) ) && ( empty( $left ) || empty( $share_left ) ) ) {
				return false;
			} ?>
			<script>!function (d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (!d.getElementById(id)) {
						js = d.createElement(s);
						js.id = id;
						js.src = "//platform.twitter.com/widgets.js";
						fjs.parentNode.insertBefore(js, fjs);
					}
				}(document, "script", "twitter-wjs");
			</script>
		<?php
		endif;
	}
endif;


/**
 * render like
 */
if ( ! function_exists( 'pixwell_render_like' ) ) :
	function pixwell_render_like() {

		$protocol     = pixwell_protocol();
		$twitter_user = pixwell_get_twitter_name();
		$post_title   = urlencode( html_entity_decode( get_the_title(), ENT_COMPAT, 'UTF-8' ) ); ?>
		<aside class="like-box clearfix">
			<div class="like-el fb-like">
				<iframe src="<?php echo esc_attr( $protocol ); ?>://www.facebook.com/plugins/like.php?href=<?php echo get_permalink() ?>&amp;layout=button_count&amp;show_faces=false&amp;width=105&amp;action=like&amp;colorscheme=light&amp;height=21" style="border:none; overflow:hidden; width:105px; height:21px; background-color:transparent;"></iframe>
			</div>
			<div class="like-el twitter-like">
				<a href="<?php echo esc_attr( $protocol ); ?>://twitter.com/share" class="twitter-share-button" data-url="<?php echo get_permalink() ?>" data-text="<?php echo htmlspecialchars( $post_title, ENT_COMPAT, 'UTF-8' ); ?>" data-via="<?php echo urlencode( $twitter_user ); ?>" data-lang="en" rel="nofollow"></a>
			</div>
		</aside>
	<?php
	}
endif;


/**
 * render share icon
 */
if ( ! function_exists( 'pixwell_render_share_icon' ) ):
	function pixwell_render_share_icon( $settings = array() ) {

		$protocol     = pixwell_protocol();
		$twitter_user = pixwell_get_twitter_name();
		$post_title   = urlencode( html_entity_decode( get_the_title(), ENT_COMPAT, 'UTF-8' ) );

		if ( ! empty( $settings['facebook'] ) ) : ?>
			<a class="share-action share-icon share-facebook" href="<?php echo esc_attr( $protocol ); ?>://www.facebook.com/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" title="Facebook" rel="nofollow"><i class="rbi rbi-facebook"></i></a>
		<?php endif;

		if ( ! empty( $settings['twitter'] ) ) : ?>
			<a class="share-twitter share-icon" href="https://twitter.com/intent/tweet?text=<?php echo htmlspecialchars( $post_title, ENT_COMPAT, 'UTF-8' ); ?>&amp;url=<?php echo urlencode( get_permalink() ); ?>&amp;via=<?php echo urlencode( $twitter_user ); ?>" title="Twitter" rel="nofollow"><i class="rbi rbi-twitter"></i></a><?php endif;
		if ( ! empty( $settings['pinterest'] ) ) :
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'pixwell_780x0-2x' );
			if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) and get_post_meta( get_the_ID(), '_yoast_wpseo_metadesc', true ) != '' ) {
				$pinterest_description = get_post_meta( get_the_ID(), '_yoast_wpseo_metadesc', true );
			} else {
				$pinterest_description = $post_title;
			} ?>
			<a class="share-action share-icon share-pinterest" rel="nofollow" href="<?php echo esc_attr( $protocol ); ?>://pinterest.com/pin/create/button/?url=<?php echo urlencode( get_permalink() ); ?>&amp;media=<?php if ( ! empty( $image[0] ) ) {
				echo( esc_url( $image[0] ) );
			} ?>&amp;description=<?php echo htmlspecialchars( $pinterest_description, ENT_COMPAT, 'UTF-8' ); ?>" title="Pinterest"><i class="rbi rbi-pinterest"></i></a>
		<?php endif;

		if ( ! empty( $settings['whatsapp'] ) ) : ?>
			<a class="share-icon share-whatsapp is-web" rel="nofollow" href="<?php echo esc_attr( $protocol ); ?>://web.whatsapp.com/send?text=<?php echo htmlspecialchars( $post_title, ENT_COMPAT, 'UTF-8' ) . ' &#9758; ' . urlencode( get_permalink() ); ?>" target="_blank" title="WhatsApp"><i class="rbi rbi-whatsapp"></i></a>
			<a class="share-icon share-whatsapp is-mobile" rel="nofollow" href="whatsapp://send?text=<?php echo htmlspecialchars( $post_title, ENT_COMPAT, 'UTF-8' ) . ' &#9758; ' . urlencode( get_permalink() ); ?>" target="_blank" title="WhatsApp"><i class="rbi rbi-whatsapp"></i></a>
		<?php endif;

		if ( ! empty( $settings['linkedin'] ) ) : ?>
			<a class="share-action share-icon share-linkedin" rel="nofollow" href="<?php echo esc_attr( $protocol ); ?>://linkedin.com/shareArticle?mini=true&amp;url=<?php echo urlencode( get_permalink() ); ?>&amp;title=<?php echo htmlspecialchars( $post_title, ENT_COMPAT, 'UTF-8' ); ?>" title="linkedIn"><i class="rbi rbi-linkedin"></i></a>
		<?php endif;

		if ( ! empty( $settings['tumblr'] ) ) : ?>
			<a class="share-action share-icon share-tumblr" rel="nofollow" href="<?php echo esc_attr( $protocol ); ?>://www.tumblr.com/share/link?url=<?php echo urlencode( get_permalink() ); ?>&amp;name=<?php echo htmlspecialchars( $post_title, ENT_COMPAT, 'UTF-8' ); ?>&amp;description=<?php echo htmlspecialchars( $post_title, ENT_COMPAT, 'UTF-8' ); ?>" title="Tumblr"><i class="rbi rbi-tumblr"></i></a>
		<?php endif;

		if ( ! empty( $settings['reddit'] ) ) : ?>
			<a class="share-action share-icon share-reddit" rel="nofollow" href="<?php echo esc_attr( $protocol ); ?>://www.reddit.com/submit?url=<?php echo urlencode( get_permalink() ); ?>&amp;title=<?php echo htmlspecialchars( $post_title, ENT_COMPAT, 'UTF-8' ); ?>" title="Reddit"><i class="rbi rbi-reddit"></i></a>
		<?php endif;

		if ( ! empty( $settings['vk'] ) ) : ?>
			<a class="share-action share-icon share-vk" rel="nofollow" href="<?php echo esc_attr( $protocol ); ?>://vkontakte.ru/share.php?url=<?php echo urlencode( get_permalink() ); ?>" title="VKontakte"><i class="rbi rbi-vk"></i></a>
		<?php endif;

		if ( ! empty( $settings['telegram'] ) ) : ?>
			<a class="share-action share-icon share-telegram" rel="nofollow" href="<?php echo esc_attr( $protocol ); ?>://t.me/share/?url=<?php echo urlencode( get_permalink() ); ?>&amp;text=<?php echo htmlspecialchars( $post_title, ENT_COMPAT, 'UTF-8' ); ?>" title="Telegram"><i class="rbi rbi-telegram"></i></a>
		<?php endif;

		if ( ! empty( $settings['email'] ) ) : ?>
			<a class="share-icon share-email" rel="nofollow" href="mailto:?subject=<?php echo get_the_title(); ?>&amp;BODY=<?php echo pixwell_translate( 'share_email_info' )  . ' ' . urlencode( get_permalink() ); ?>" title="Email"><i class="rbi rbi-email-envelope"></i></a>
		<?php endif;
	}
endif;


/**
 * share icons
 */
if ( ! function_exists( 'pixwell_render_share_text' ) ):
	function pixwell_render_share_text( $settings = array() ) {

		$protocol     = pixwell_protocol();
		$twitter_user = pixwell_get_twitter_name();
		$post_title   = urlencode( html_entity_decode( get_the_title(), ENT_COMPAT, 'UTF-8' ) );

		if ( ! empty( $settings['facebook'] ) ) : ?>
			<a class="share-action share-icon share-facebook" rel="nofollow" href="<?php echo esc_attr( $protocol ); ?>://www.facebook.com/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" title="Facebook"><i class="rbi rbi-facebook"></i><span><?php echo pixwell_translate( 'share_facebook' ) ?></span></a>
		<?php endif;
		if ( ! empty( $settings['twitter'] ) ) : ?>
			<a class="share-twitter share-icon" rel="nofollow" href="https://twitter.com/intent/tweet?text=<?php echo htmlspecialchars( $post_title, ENT_COMPAT, 'UTF-8' ); ?>&amp;url=<?php echo urlencode( get_permalink() ); ?>&amp;via=<?php echo urlencode( $twitter_user ); ?>" title="Twitter">
				<i class="rbi rbi-twitter"></i><span><?php echo pixwell_translate( 'share_twitter' ) ?></span>
			</a>
		<?php endif;

		if ( ! empty( $settings['pinterest'] ) ) :
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'pixwell_780x0-2x' );
			if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) and get_post_meta( get_the_ID(), '_yoast_wpseo_metadesc', true ) != '' ) {
				$pinterest_description = get_post_meta( get_the_ID(), '_yoast_wpseo_metadesc', true );
			} else {
				$pinterest_description = $post_title;
			} ?>
			<a class="share-action share-icon share-pinterest" rel="nofollow" href="<?php echo esc_attr( $protocol ); ?>://pinterest.com/pin/create/button/?url=<?php echo urlencode( get_permalink() ); ?>&amp;media=<?php if ( ! empty( $image[0] ) ) {
				echo( esc_url( $image[0] ) );
			} ?>&amp;description=<?php echo htmlspecialchars( $pinterest_description, ENT_COMPAT, 'UTF-8' ); ?>" title="Pinterest"><i class="rbi rbi-pinterest"></i><span><?php echo pixwell_translate( 'share_pinterest' ) ?></span></a>
		<?php endif;

		if ( ! empty( $settings['whatsapp'] ) ) : ?>
			<a class="share-icon share-whatsapp is-web" rel="nofollow" href="<?php echo esc_attr( $protocol ); ?>://web.whatsapp.com/send?text=<?php echo htmlspecialchars( $post_title, ENT_COMPAT, 'UTF-8' ) . ' &#9758; ' . urlencode( get_permalink() ); ?>" target="_blank" title="WhatsApp"><i class="rbi rbi-whatsapp"></i><span><?php echo pixwell_translate( 'share_whatsapp' ) ?></span></a>
			<a class="share-icon share-whatsapp is-mobile" rel="nofollow" href="whatsapp://send?text=<?php echo htmlspecialchars( $post_title, ENT_COMPAT, 'UTF-8' ) . ' &#9758; ' . urlencode( get_permalink() ); ?>" target="_blank" title="WhatsApp"><i class="rbi rbi-whatsapp"></i><span><?php echo pixwell_translate( 'share_whatsapp' ) ?></span></a>
		<?php endif;

		if ( ! empty( $settings['linkedin'] ) ) : ?>
			<a class="share-action share-icon share-linkedin" rel="nofollow" href="<?php echo esc_attr( $protocol ); ?>://linkedin.com/shareArticle?mini=true&amp;url=<?php echo urlencode( get_permalink() ); ?>&amp;title=<?php echo htmlspecialchars( $post_title, ENT_COMPAT, 'UTF-8' ); ?>" title="linkedIn"><i class="rbi rbi-linkedin"></i><span><?php echo pixwell_translate( 'share_linkedin' ) ?></span></a>
		<?php endif;

		if ( ! empty( $settings['tumblr'] ) ) : ?>
			<a class="share-action share-icon share-tumblr" rel="nofollow" href="<?php echo esc_attr( $protocol ); ?>://www.tumblr.com/share/link?url=<?php echo urlencode( get_permalink() ); ?>&amp;name=<?php echo htmlspecialchars( $post_title, ENT_COMPAT, 'UTF-8' ); ?>&amp;description=<?php echo htmlspecialchars( $post_title, ENT_COMPAT, 'UTF-8' ); ?>" title="Tumblr"><i class="rbi rbi-tumblr"></i><span><?php echo pixwell_translate( 'share_tumblr' ) ?></span></a>
		<?php endif;

		if ( ! empty( $settings['reddit'] ) ) : ?>
			<a class="share-action share-icon share-reddit" rel="nofollow" href="<?php echo esc_attr( $protocol ); ?>://www.reddit.com/submit?url=<?php echo urlencode( get_permalink() ); ?>&amp;title=<?php echo htmlspecialchars( $post_title, ENT_COMPAT, 'UTF-8' ); ?>" title="Reddit"><i class="rbi rbi-reddit"></i><span><?php echo pixwell_translate( 'share_reddit' ) ?></span></a>
		<?php endif;

		if ( ! empty( $settings['vk'] ) ) : ?>
			<a class="share-action share-icon share-vk" rel="nofollow" href="<?php echo esc_attr( $protocol ); ?>://vkontakte.ru/share.php?url=<?php echo urlencode( get_permalink() ); ?>" title="VKontakte"><i class="rbi rbi-vk"></i><span><?php echo pixwell_translate( 'share_vk' ) ?></span></a>
		<?php endif;

		if ( ! empty( $settings['telegram'] ) ) : ?>
			<a class="share-action share-icon share-telegram" rel="nofollow" href="<?php echo esc_attr( $protocol ); ?>://t.me/share/?url=<?php echo urlencode( get_permalink() ); ?>&amp;text=<?php echo htmlspecialchars( $post_title, ENT_COMPAT, 'UTF-8' ); ?>" title="Telegram"><i class="rbi rbi-telegram"></i><span><?php echo pixwell_translate( 'share_telegram' ) ?></span></a>
		<?php endif;

		if ( ! empty( $settings['email'] ) ) : ?>
			<a class="share-icon share-email" rel="nofollow" href="mailto:?subject=<?php echo get_the_title(); ?>&amp;BODY=<?php echo pixwell_translate( 'share_email_info' )  . ' '  . urlencode( get_permalink() ); ?>" title="Email"><i class="rbi rbi-email-envelope"></i><span><?php echo pixwell_translate( 'share_email' ) ?></span></a>
		<?php endif;
	}
endif;


/**
 * @param int $forgery
 *
 * @return int
 * get share total
 */
if ( ! function_exists( 'pixwell_get_total_shares' ) ) {
	function pixwell_get_total_shares( $forgery = 0 ) {

		$url = get_permalink();
		$params = array(
			'timeout'   => 60,
			'sslverify' => false,
		);

		$url_snip  = pixwell_convert_to_id( substr( $url, 0, 35 ) );
		$transient = 'rb_share_' . $url_snip;
		$cache     = get_transient( $transient );

		if ( false !== $cache ) {
			$total = $cache;
		} else {

			$json_string = wp_remote_get( 'http://graph.facebook.com/?ids=' . $url, $params );
			if ( ! is_wp_error( $json_string ) && isset( $json_string['body'] ) ) {
				$json              = json_decode( $json_string['body'], true );
				$count['facebook'] = isset( $json[ $url ]['share']['share_count'] ) ? intval( ( $json[ $url ]['share']['share_count'] ) ) : 0;
			} else {
				$count['facebook'] = 0;
			}

			$json_string = wp_remote_get( "http://www.linkedin.com/countserv/count/share?url=$url&format=json", $params );
			if ( ! is_wp_error( $json_string ) && isset( $json_string['body'] ) ) {
				$json              = json_decode( $json_string['body'], true );
				$count['linkedin'] = isset( $json['count'] ) ? intval( $json['count'] ) : 0;
			} else {
				$count['linkedin'] = 0;
			}

			$json_string = wp_remote_get( 'http://api.pinterest.com/v1/urls/count.json?url=' . $url, $params );
			if ( ! is_wp_error( $json_string ) && isset( $json_string['body'] ) ) {
				$json_string        = preg_replace( '/^receiveCount\((.*)\)$/', "\\1", $json_string['body'] );
				$json               = json_decode( $json_string, true );
				$count['pinterest'] = isset( $json['count'] ) ? intval( $json['count'] ) : 0;
			} else {
				$count['pinterest'] = 0;
			}

			$total = $count['facebook'] + $count['pinterest'] + $count['linkedin'];

			set_transient( $transient, $total, 3200 );

		}

		return $forgery + $total;
	}
}