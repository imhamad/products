<?php
$pixwell_footer_widget_layout = pixwell_get_option( 'footer_widget_layout' );
$pixwell_footer_class         = 'footer-widget footer-section';

if ( empty( $pixwell_footer_widget_layout ) ) {
	$pixwell_footer_widget_layout = 1;
}

switch ( $pixwell_footer_widget_layout ) {
	case 2:
		$pixwell_footer_class .= ' footer-style-2';
		break;
	case 3:
		$pixwell_footer_class .= ' footer-style-3';
		break;
	case 4:
		$pixwell_footer_class .= ' footer-style-4';
		break;
	default :
		$pixwell_footer_class .= ' footer-style-1';
}

if ( is_active_sidebar( 'pixwell_sidebar_footer_1' ) || is_active_sidebar( 'pixwell_sidebar_footer_2' ) || is_active_sidebar( 'pixwell_sidebar_footer_3' ) || is_active_sidebar( 'pixwell_sidebar_footer_4' ) ) : ?>
	<div class="<?php echo esc_attr( $pixwell_footer_class ); ?>">
		<div class="rbc-container rb-p20-gutter">
			<div class="footer-widget-inner rb-n20-gutter">
				<?php if ( is_active_sidebar( 'pixwell_sidebar_footer_1' ) ) : ?>
					<div class="footer-col-1 rb-p20-gutter">
						<?php dynamic_sidebar( 'pixwell_sidebar_footer_1' ); ?>
					</div>
				<?php endif; ?>
				<?php if ( is_active_sidebar( 'pixwell_sidebar_footer_2' ) ) : ?>
					<div class="footer-col-2 rb-p20-gutter">
						<?php dynamic_sidebar( 'pixwell_sidebar_footer_2' ); ?>
					</div>
				<?php endif; ?>
				<?php if ( is_active_sidebar( 'pixwell_sidebar_footer_3' ) ) : ?>
					<div class="footer-col-3 rb-p20-gutter">
						<?php dynamic_sidebar( 'pixwell_sidebar_footer_3' ); ?>
					</div>
				<?php endif; ?>
				<?php if ( is_active_sidebar( 'pixwell_sidebar_footer_4' ) ) : ?>
					<div class="footer-col-4 rb-p20-gutter">
						<?php dynamic_sidebar( 'pixwell_sidebar_footer_4' ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif;