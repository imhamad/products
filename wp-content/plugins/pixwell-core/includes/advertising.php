<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * @param $settings
 * render ad script
 */
if ( ! function_exists( 'pixwell_ad_script' ) ) :
	function pixwell_ad_script( $settings ) {

		if ( empty( $settings['ad_script'] ) ) {
			return false;
		}

		$ad_script = pixwell_decode_shortcode( $settings['ad_script'] );
		if ( empty( $ad_script ) ) {
			$ad_script = $settings['ad_script'];
		}
		if ( ! empty( $settings['title'] ) ) : ?>
			<h6 class="advert-decs"><?php echo esc_html( $settings['title'] ); ?></h6>
		<?php endif;
		$spot = pixwell_ad_spot( $ad_script );
		if ( ! empty( $spot['data_ad_slot'] ) && ! empty( $spot['data_ad_client'] ) && ! empty( $settings['ad_size'] ) ): ?>
			<aside class="ad-script adsense">
				<style>
					<?php echo '.res-'.trim($settings['id']); ?><?php echo pixwell_ad_script_css($settings['ad_size_mobile']); ?>
					@media (min-width: 500px) {
					<?php echo '.res-'.trim($settings['id']); ?><?php echo pixwell_ad_script_css($settings['ad_size_tablet']); ?>
					}
					@media (min-width: 800px) {
					<?php echo '.res-'.trim($settings['id']); ?><?php echo pixwell_ad_script_css($settings['ad_size_desktop']); ?>
					}
				</style>
				<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<ins class="adsbygoogle<?php echo ' res-' . trim( $settings['id'] ); ?>"
				     style="display:inline-block"
				     data-ad-client="<?php echo esc_attr( $spot['data_ad_client'] ); ?>"
				     data-ad-slot="<?php echo esc_attr( $spot['data_ad_slot'] ); ?>"></ins>
				<script>
					(adsbygoogle = window.adsbygoogle || []).push({});
				</script>
			</aside>
		<?php else : ?>
			<aside class="ad-script non-adsense">
				<?php echo do_shortcode( $ad_script ); ?>
			</aside>

		<?php endif;
	}
endif;



/**
 * @param $ad_script
 *
 * @return array|bool
 * get ad spot
 */
if ( ! function_exists( 'pixwell_ad_spot' ) ) :
	function pixwell_ad_spot( $ad_script ) {
		$data_ad = array();

		if ( empty( $ad_script ) ) {
			return false;
		}

		if ( preg_match( '/googlesyndication.com/', $ad_script ) ) {

			$array_ad_client_code = explode( 'data-ad-client', $ad_script );
			if ( empty( $array_ad_client_code[1] ) ) {
				return false;
			}
			preg_match( '/"([a-zA-Z0-9-\s]+)"/', $array_ad_client_code[1], $match_data_ad_client );
			$data_ad_client = str_replace( array( '"', ' ' ), array( '' ), $match_data_ad_client[1] );

			$array_ad_slot_code = explode( 'data-ad-slot', $ad_script );
			if ( empty( $array_ad_slot_code[1] ) ) {
				return false;
			}
			preg_match( '/"([a-zA-Z0-9\s]+)"/', $array_ad_slot_code[1], $match_data_add_slot );
			$data_ad_slot = str_replace( array( '"', ' ' ), array( '' ), $match_data_add_slot[1] );

			if ( ! empty( $data_ad_client ) && ! empty( $data_ad_slot ) ) {
				$data_ad['data_ad_client'] = $data_ad_client;
				$data_ad['data_ad_slot']   = $data_ad_slot;
			}

			return $data_ad;

		} else {
			return false;
		}
	}
endif;


/**
 * @param $size
 *
 * @return string
 * ad css
 */
if ( ! function_exists( 'pixwell_ad_script_css' ) ):
	function pixwell_ad_script_css( $size ) {
		switch ( $size ) {
			case '1' :
				return '{ width: 728px; height: 90px; }';
			case '2' :
				return '{ width: 468px; height: 60px; }';
			case '3' :
				return '{ width: 234px; height: 60px; }';
			case '4' :
				return '{ width: 125px; height: 125px; }';
			case '5' :
				return '{ width: 120px; height: 600px; }';
			case '6' :
				return '{ width: 160px; height: 600px; }';
			case '7' :
				return '{ width: 180px; height: 150px; }';
			case '8' :
				return '{ width: 120px; height: 240px; }';
			case '9' :
				return '{ width: 200px; height: 200px; }';
			case '10' :
				return '{ width: 250px; height: 250px; }';
			case '11' :
				return '{ width: 300px; height: 250px; }';
			case '12' :
				return '{ width: 336px; height: 280px; }';
			case '13' :
				return '{ width: 300px; height: 600px; }';
			case '14' :
				return '{ width: 300px; height: 1050px; }';
			case '15' :
				return '{ width: 320px; height: 50px; }';
			case '16' :
				return '{ width: 970px; height: 90px; }';
			case '17' :
				return '{ width: 970px; height: 250px; }';
			default :
				return '{ display: none; }';
		}
	}
endif;


/**
 * widget custom ad
 */
if ( ! function_exists( 'pixwell_ad_image' ) ):
	function pixwell_ad_image( $settings ) {

		if ( empty( $settings['image'] ) ) {
			return false;
		} ?>
		<aside class="ad-image">
			<?php if ( ! empty( $settings['title'] ) ) : ?>
				<h6 class="advert-decs"><?php echo esc_html( $settings['title'] ); ?></h6>
			<?php endif; ?>
			<?php if ( empty( $settings['destination'] ) ) : ?>
				<img src="<?php echo esc_url( $settings['image'] ); ?>" alt="<?php if ( ! empty( $settings['title'] ) ) { echo esc_attr( $settings['title'] ); } ?>">
			<?php else : ?>
				<a class="widget-ad-link" target="_blank" href="<?php echo esc_url( $settings['destination'] ); ?>"><img src="<?php echo esc_url( $settings['image'] ); ?>" alt="<?php if ( ! empty( $settings['title'] ) ) { echo esc_attr( $settings['title'] ); } ?>"></a>
			<?php endif; ?>
		</aside>
	<?php
	}
endif;


/** in-feed adverts */
if ( ! function_exists( 'pixwell_infeed_advert' ) ) {
	function pixwell_infeed_advert( $settings ) {
		if ( ! empty( $settings['infeed_ad'] ) ):

			$class_name = 'infeed-wrap';
			if ( ! empty( $settings['infeed_classname'] ) ) {
				$class_name .= ' ' . $settings['infeed_classname'];
			} ?>
			<div class="<?php echo esc_attr( $class_name ); ?>">
				<?php if ( 'code' == $settings['infeed_ad'] ):
					if ( ! empty( $settings['html_advert'] ) ) :
						if ( base64_decode( $settings['html_advert'] ) ) {
							$advert_code = base64_decode( $settings['html_advert'] );
						} else {
							$advert_code = $settings['html_advert'];
						} ?>
						<div class="infeed-ad infeed-code"><?php echo do_shortcode( $advert_code ); ?></div>
					<?php endif;
				elseif ( 'image' == $settings['infeed_ad'] )  :
					if ( empty( $settings['ad_destination'] ) ) : ?>
						<img class="infeed-ad infeed-image" src="<?php echo esc_url( $settings['ad_attachment'] ); ?>" alt="<?php if ( ! empty( $settings['infeed_description'] ) ) { echo esc_attr( $settings['infeed_description'] );	} ?>">
					<?php else : ?>
						<a class="infeed-ad infeed-image" target="_blank" href="<?php echo esc_url( $settings['ad_destination'] ); ?>"><img src="<?php echo esc_url( $settings['ad_attachment'] ); ?>" alt="<?php if ( ! empty( $settings['infeed_description'] ) ) {
								echo esc_attr( $settings['infeed_description'] );
							} ?>"></a>
					<?php endif;
				endif;
				if ( ! empty( $settings['infeed_description'] ) ) : ?>
					<div class="advert-decs"><?php echo do_shortcode( $settings['infeed_description'] ) ?></div>
				<?php endif; ?>
			</div>
		<?php endif;
	}
}