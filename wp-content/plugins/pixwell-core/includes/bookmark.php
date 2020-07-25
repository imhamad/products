<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/** read it later */
add_action( 'pixwell_single_bottom', 'pixwell_remove_bookmark' );
add_action( 'wp_ajax_nopriv_rb_bookmark', 'pixwell_bookmark_list' );
add_action( 'wp_ajax_rb_bookmark', 'pixwell_bookmark_list' );


/** bookmark */
if ( ! function_exists( 'pixwell_bookmark' ) ) :
	function pixwell_bookmark() {
		?>
		<span class="read-it-later bookmark-item" <?php if ( is_rtl() ) { echo 'dir="rtl"'; }; ?> data-title="<?php echo pixwell_translate( 'read_later' ); ?>" data-bookmarkid="<?php echo get_the_ID(); ?>"><i class="rbi rbi-bookmark"></i></span>
	<?php
	}
endif;

/** remove bookmark */
if ( ! function_exists( 'pixwell_remove_bookmark' ) ):
	function pixwell_remove_bookmark() {
		if ( is_single() ) {
			echo '<aside class="is-hidden rb-remove-bookmark" data-bookmarkid="' . get_the_ID() . '"></aside>';
		}
	}
endif;


/** bookmark list */
if ( ! function_exists( 'pixwell_bookmark_list' ) ) {
	function pixwell_bookmark_list() {
		if ( empty( $_POST['ids'] ) || ! is_array( $_POST['ids'] ) ) {
			pixwell_bookmark_list_empty();
		} else {
			$response     = '';
			$included_ids = $_POST['ids'];
			$included_ids = array_map( 'absint', $included_ids );
			if ( function_exists( 'pixwell_render_bookmark_list' ) ) {
				ob_start();
				pixwell_render_bookmark_list( $included_ids );
				$response = ob_get_clean();
			}
			wp_send_json( $response, null );
			die();
		}
	}
}


/** empty bookmark list */
if ( ! function_exists( 'pixwell_bookmark_list_empty' ) ) {
	function pixwell_bookmark_list_empty() {
		$response = '';
		$response .= '<div class="bookmark-empty">';
		$response .= '<div class="bookmark-empty-icon"><i class="rbi rbi-warning"></i></div>';
		$response .= '<h6 class="h3">' . pixwell_translate( 'nothing_found' ) . '</h6>';
		$response .= '<p>' . pixwell_translate( 'bookmark_empty' ) . '</p>';
		$response .= '</div>';
		wp_send_json( $response, null );
		die();
	}

}
