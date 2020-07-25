<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RB_REACTION_VERSION', 1.0 );

add_action( 'wp_enqueue_scripts', 'rb_reaction_register_scripts', 10 );
add_action( 'wp_ajax_nopriv_rb_add_reaction', 'rb_add_reaction' );
add_action( 'wp_ajax_rb_add_reaction', 'rb_add_reaction' );
add_action( 'wp_ajax_nopriv_rb_load_reaction', 'rb_load_user_reaction' );
add_action( 'wp_ajax_rb_load_reaction', 'rb_load_user_reaction' );
add_shortcode( 'rb_show_reaction', 'rb_render_reaction' );

/**
 * load reaction scripts
 */
function rb_reaction_register_scripts() {
	if ( ! is_admin() ) {
		wp_register_script( 'rb-reaction-script', plugin_dir_url( __FILE__ ) . 'reaction.js', array(
			'jquery',
		), RB_REACTION_VERSION, true );
	}
}

/**
 * get user IPs
 */
function rb_get_user_ip() {

	if ( getenv( 'HTTP_CLIENT_IP' ) ) {
		$user_ip = getenv( 'HTTP_CLIENT_IP' );
	} elseif ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
		$user_ip = getenv( 'HTTP_X_FORWARDED_FOR' );
	} elseif ( getenv( 'HTTP_X_FORWARDED' ) ) {
		$user_ip = getenv( 'HTTP_X_FORWARDED' );
	} elseif ( getenv( 'HTTP_FORWARDED_FOR' ) ) {
		$user_ip = getenv( 'HTTP_FORWARDED_FOR' );
	} elseif ( getenv( 'HTTP_FORWARDED' ) ) {
		$user_ip = getenv( 'HTTP_FORWARDED' );
	} else {
		$user_ip = $_SERVER['REMOTE_ADDR'];
	}

	if ( ! filter_var( $user_ip, FILTER_VALIDATE_IP ) ) {
		return '127.0.0.1';
	} else {
		return rb_mask_anonymise_ip( $user_ip );
	}
}

/**
 * @param $user_ip
 *
 * @return mixed
 * rb_get_anonymise_ip
 */
function rb_mask_anonymise_ip( $user_ip ) {
	if ( strpos( $user_ip, "." ) == true ) {
		return preg_replace( '~[0-9]+$~', 'x', $user_ip );
	} else {
		return preg_replace( '~[0-9]*:[0-9]+$~', 'xxxx:xxxx', $user_ip );
	}
}

/**
 * register reaction
 */
function rb_register_reaction() {
	$defaults = array(
		'love'   => array(
			'id'    => 'love',
			'title' => pixwell_translate( 'love' ),
			'icon'  => 'symbol-love'
		),
		'sad'    => array(
			'id'    => 'sad',
			'title' => pixwell_translate( 'sad' ),
			'icon'  => 'symbol-sad'
		),
		'happy'  => array(
			'id'    => 'happy',
			'title' => pixwell_translate( 'happy' ),
			'icon'  => 'symbol-happy'
		),
		'sleepy' => array(
			'id'    => 'sleepy',
			'title' => pixwell_translate( 'sleepy' ),
			'icon'  => 'symbol-sleepy'
		),
		'angry'  => array(
			'id'    => 'angry',
			'title' => pixwell_translate( 'angry' ),
			'icon'  => 'symbol-angry'
		),
		'dead'   => array(
			'id'    => 'dead',
			'title' => pixwell_translate( 'dead' ),
			'icon'  => 'symbol-dead'
		),
		'wink'   => array(
			'id'    => 'wink',
			'title' => pixwell_translate( 'wink' ),
			'icon'  => 'symbol-wink'
		),
	);

	$defaults = apply_filters( 'rb_add_reaction', $defaults );

	return $defaults;
}


/**
 * render reaction
 */
function rb_render_reaction( $attrs ) {

	$attrs = shortcode_atts( array(
		'id' => '',
	), $attrs );

	if ( ! wp_script_is( 'rb-reaction-script' ) ) {
		wp_enqueue_script( 'rb-reaction-script' );
		wp_localize_script( 'rb-reaction-script', 'rbReactionParams', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	};

	if ( empty( $attrs['id'] ) ) {

		global $post;
		$post_id = get_the_ID();
	} else {
		$post_id = $attrs['id'];
	}

	if ( empty( $post_id ) ) {
		return false;
	}

	$output    = '';
	$reactions = rb_register_reaction();

	if ( is_array( $reactions ) ) {

		$output .= '<aside id="reaction-' . $post_id . '" class="rb-reaction reaction-wrap" data-reaction_uid="' . esc_attr( $post_id ) . '">';

		foreach ( $reactions as $reaction ) {
			if ( empty( $reaction['id'] ) ) {
				continue;
			}
			$output .= '<div class="reaction" data-reaction="' . $reaction['id'] . '" data-reaction_uid="' . esc_attr( $post_id ) . '">';
			$output .= '<span class="reaction-content">';
			$output .= '<div class="reaction-icon"><svg class="rb-svg" viewBox="0 0 150 150"><use xlink:href="#' . esc_attr( $reaction['icon'] ) . '"></use></svg></div>';
			$output .= '<span class="reaction-title h6">' . esc_html( $reaction['title'] ) . '</span>';
			$output .= '</span>';
			$output .= '<span class="total-wrap"><span class="reaction-count">' . rb_count_reaction( $reaction['id'], $post_id ) . '</span></span>';
			$output .= '</div>';
		}

		$output .= '</aside>';
	}

	return $output;
}

/**
 * reaction count
 */
function rb_count_reaction( $reaction, $post_id ) {
	$data = get_post_meta( $post_id, 'rb_reaction_data', true );
	if ( ! empty( $data[ $reaction ] ) ) {
		return count( $data[ $reaction ] );
	} else {
		return 0;
	}
}


/**
 * @param $post_id
 * load user reaction
 */
function rb_load_user_reaction() {

	if ( empty( $_POST['uid'] ) ) {
		wp_send_json( '', null );
	}

	$current_user = get_current_user_id();
	if ( ! empty( $current_user ) ) {
		$user_ip = $current_user;
	} else {
		$user_ip = rb_get_user_ip();
	}

	$uid      = esc_attr( $_POST['uid'] );
	$data     = get_post_meta( $uid, 'rb_reaction_data', true );
	$response = array();

	if ( is_array( $data ) ) {
		foreach ( $data as $reaction => $stored_data ) {
			if ( in_array( $user_ip, $stored_data ) ) {
				$response[] = $reaction;
				continue;
			}
		}
	}

	wp_send_json( $response, null );
}

/**
 * add reaction
 */
function rb_add_reaction() {

	if ( empty( $_POST['uid'] ) || empty( $_POST['reaction'] ) || empty( $_POST['push'] ) ) {
		wp_send_json( '', null );
	}

	$current_user = get_current_user_id();

	if ( ! empty( $current_user ) ) {
		$user_ip = $current_user;
	} else {
		$user_ip = rb_get_user_ip();
	}

	$uid      = esc_attr( $_POST['uid'] );
	$reaction = esc_attr( $_POST['reaction'] );
	$push     = esc_attr( $_POST['push'] );
	$data     = get_post_meta( $uid, 'rb_reaction_data', true );

	if ( ! is_array( $data ) ) {
		$data = array();
	}

	if ( ! is_array( $data[ $reaction ] ) ) {
		$data[ $reaction ] = array();
	}

	if ( $push > 0 ) {
		$data[ $reaction ][] = $user_ip;
		array_unique( $data[ $reaction ] );
	} else {
		if ( ( $key = array_search( $user_ip, $data[ $reaction ] ) ) !== false ) {
			unset( $data[ $reaction ][ $key ] );
		}
	}

	update_post_meta( $uid, 'rb_reaction_data', $data );
	wp_send_json( true, null );
}
