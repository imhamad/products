<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_init', 'rb_newsletter_activate_download', 10 );
add_action( 'admin_init', 'rb_export_subscribed_emails', 90 );
add_action( 'wp_ajax_nopriv_rb_submit_newsletter', 'rb_submit_newsletter' );
add_action( 'wp_ajax_rb_submit_newsletter', 'rb_submit_newsletter' );
add_action( 'admin_menu', 'rb_newsletter_download_link' );


/** add user cap */
function rb_newsletter_activate_download() {
	$role = get_role( 'administrator' );
	$role->add_cap( 'download_subscribed_emails' );
}

/** download link */
function rb_newsletter_download_link() {
	add_submenu_page( 'tools.php',
		'Download Ruby Subscribed Emails',
		'Download Ruby Subscribed Emails',
		'download_subscribed_emails',
		'tools.php?download=subscribed-emails.csv' );
}

/** submit newsletter */
if ( ! function_exists( 'rb_submit_newsletter' ) ) {
	function rb_submit_newsletter() {

		if ( empty( $_POST['email'] ) || ! is_email( $_POST['email'] ) ) {
			wp_send_json( array( 'notice' => 'email-error' ) );
			die();
		}

		if ( isset ( $_POST['privacy'] ) && empty( $_POST['privacy'] ) ) {
			wp_send_json( array( 'notice' => 'privacy-error' ) );
			die();
		}

		$email           = sanitize_email( $_POST['email'] );
		$subscribed_data = get_option( 'rb_subscribed_emails' );

		if ( empty( $subscribed_data ) || ! is_array( $subscribed_data ) ) {
			update_option( 'rb_subscribed_emails', array( $email ) );
			wp_send_json( array( 'notice' => 'success' ) );
			die();

		} else {

			if ( in_array( $email, $subscribed_data ) ) {
				wp_send_json( array( 'notice' => 'email-exists' ) );
				die();
			}

			array_push( $subscribed_data, $email );
			update_option( 'rb_subscribed_emails', $subscribed_data );
			wp_send_json( array( 'notice' => 'success' ) );
			die();
		}
	}
}

/** export subscribed emails */
if ( ! function_exists( 'rb_export_subscribed_emails' ) ) {
	function rb_export_subscribed_emails() {

		if ( ! is_admin() ) {
			return;
		}

		global $pagenow;
		if ( $pagenow == 'tools.php' && current_user_can( 'download_subscribed_emails' ) && isset( $_GET['download'] ) && $_GET['download'] == 'subscribed-emails.csv' ) {
			header( "Content-type: application/x-msdownload" );
			header( "Content-Disposition: attachment; filename=subscribed-emails.csv" );
			header( "Pragma: no-cache" );
			header( "Content-Transfer-Encoding: binary" );
			header( "Expires: 0" );
			echo rb_generate_subscribed_emails_csv();
			exit();
		}
	}
}

/** generate csv */
if ( ! function_exists( 'rb_generate_subscribed_emails_csv' ) ) {
	function rb_generate_subscribed_emails_csv() {
		$subscribed_data = get_option( 'rb_subscribed_emails' );
		$output          = 'Email Address';
		$output .= "\n";
		if ( ! empty( $subscribed_data ) && is_array( $subscribed_data ) ) {
			foreach ( $subscribed_data as $email ) {
				$output .= $email;
				$output .= "\n";
			}
		}

		return $output;
	}
}


/**
 * @param $settings
 *
 * @return string
 * render newsletter
 */
if ( ! function_exists( 'rb_render_newsletter' ) ) {
	function rb_render_newsletter( $settings ) {
		ob_start();

		$settings['placeholder']   = pixwell_get_option( 'newsletter_placeholder' );
		$settings['privacy_error'] = pixwell_get_option( 'newsletter_privacy_error' );
		$settings['email_error']   = pixwell_get_option( 'newsletter_email_error' );
		$settings['email_exists']  = pixwell_get_option( 'newsletter_email_exists' );
		$settings['success']       = pixwell_get_option( 'newsletter_success' );
		$checkbox_id = rand( 1, 100 );

		$class_name = 'rb-newsletter';
		if ( empty( $settings['submit'] ) ) {
			$class_name .= ' is-submit-icon';
		} ?>
		<div class="<?php echo esc_attr( $class_name ) ?>">
			<div class="rb-newsletter-inner">
					<div class="newsletter-cover">
						<?php if ( ! empty( $settings['cover'] ) ) : ?>
						<img src="<?php echo esc_url( $settings['cover'] ); ?>" alt="<?php esc_attr( $settings['title'] ) ?>"/>
						<?php endif; ?>
					</div>
				<div class="newsletter-content">
					<?php if ( ! empty( $settings['title'] ) ) : ?>
						<h4><?php echo esc_html( $settings['title'] ); ?></h4>
					<?php endif;
					if ( ! empty( $settings['description'] ) ) : ?>
						<div class="newsletter-desc"><?php echo wp_kses_post( wpautop( $settings['description'] ) ); ?></div>
					<?php endif;
					if ( ! empty( $settings['inner_cover'] ) ) : ?>
						<div class="newsletter-inner-cover">
							<img src="<?php echo esc_url( $settings['inner_cover'] ); ?>" alt="<?php esc_attr( $settings['title'] ) ?>"/>
						</div>
					<?php endif; ?>
					<form class="rb-newsletter-form" action="#" method="post">
						<div class="newsletter-input">
							<input class="newsletter-email" placeholder="<?php esc_attr_e( $settings['placeholder'] ); ?>" type="email" name="rb_email_subscribe">
							<?php if ( ! empty( $settings['submit'] ) ) : ?>
							<button type="submit" name="submit" class="newsletter-submit"><?php echo esc_html( $settings['submit'] ); ?></button>
							<?php else : ?>
								<button type="submit" name="submit" class="newsletter-submit newsletter-submit-icon"><i class="rbi rbi-email-envelope"></i></button>
							<?php endif; ?>
						</div>
						<?php if ( ! empty( $settings['privacy'] ) ) : ?>
							<div class="newsletter-privacy">
								<input type="checkbox" id="rb-privacy-<?php echo esc_attr( $checkbox_id ); ?>" name="rb_privacy" class="newsletter-checkbox">
								<label for="rb-privacy-<?php echo esc_attr( $checkbox_id ); ?>"><?php echo wp_kses_post( $settings['privacy'] ); ?></label>
							</div>
						<?php endif; ?>
					</form>
				</div>
			</div>
			<div class="newsletter-response">
				<?php if ( ! empty( $settings['privacy'] ) ) : ?>
					<span class="response-notice privacy-error"><?php echo wp_kses_post( $settings['privacy_error'] ); ?></span>
				<?php endif; ?>
				<span class="response-notice email-error"><?php echo wp_kses_post( $settings['email_error'] ); ?></span>
				<span class="response-notice email-exists"><?php echo wp_kses_post( $settings['email_exists'] ); ?></span>
				<span class="response-notice success"><?php echo wp_kses_post( $settings['success'] ); ?></span>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
