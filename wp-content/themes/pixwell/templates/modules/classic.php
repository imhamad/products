<?php
/**
 * @param array $settings
 * @param null  $query_data
 * loop classic 1
 */
if ( ! function_exists( 'pixwell_post_classic' ) ) :
	function pixwell_post_classic( $settings = array() ) {

		$settings = pixwell_get_meta_setting( $settings, 'classic' );
		$padding  = pixwell_get_option( 'padding_content_classic' );

		if ( empty( $settings['h_tag'] ) ) {
			$settings['h_tag'] = 'h2';
		}

		$post_classes   = array();
		$post_classes[] = 'p-wrap p-classic post-' . get_the_ID();
		if ( is_sticky() ) {
			$post_classes[] = 'sticky';
		}
		if ( ! function_exists( 'pixwell_core_enqueue' ) ) {
			$post_classes[] = 'none-core';
		}
		if ( ! pixwell_is_featured_image( 'pixwell_370x250-2x' ) ) {
			$post_classes[] = 'no-feat';
		}
		if ( empty( pixwell_post_meta_info( $settings ) ) && empty( $settings['readmore'] ) ) {
			$post_classes[] = 'rb-hf';
		}
		if ( empty( pixwell_get_option( 'meta_author_icon' ) ) || ! isset( $settings['entry_meta']['enabled']['author'] ) ) {
			$post_classes[] = 'no-avatar';
		}
		if ( ! empty( $padding ) ) {
			$post_classes[] = 'is-padding';
		} ?>
		<div class="<?php echo join( ' ', $post_classes ); ?>">
			<?php if ( pixwell_is_featured_image( 'pixwell_370x250-2x' ) ) : ?>
				<div class="p-feat-holder">
					<div class="p-feat">
						<?php
						pixwell_post_thumb( 'pixwell_370x250-2x' );
						pixwell_post_cat_info( $settings );
						?>
					</div>
					<?php pixwell_post_review_info( $settings ); ?>
				</div>
			<?php endif; ?>
			<div class="p-header"><?php pixwell_post_title( $settings['h_tag'], $settings['bookmark'], 'h1' ); ?></div>
			<?php pixwell_post_summary( $settings ); ?>
			<div class="p-footer">
				<?php echo pixwell_post_meta_info( $settings );
				pixwell_post_readmore( $settings ); ?>
			</div>
		</div>
	<?php
	}
endif;


/**
 * @param array $settings
 * @param null  $query_data
 * loop classic 2
 */
if ( ! function_exists( 'pixwell_post_classic_2' ) ) :
	function pixwell_post_classic_2( $settings = array() ) {

		$settings                = pixwell_get_meta_setting( $settings, 'classic_2' );
		$settings['cat_classes'] = 'is-relative';

		if ( empty( $settings['h_tag'] ) ) {
			$settings['h_tag'] = 'h2';
		}

		$post_classes   = array();
		$post_classes[] = 'p-wrap p-classic p-classic-2 post-' . get_the_ID();
		if ( is_sticky() ) {
			$post_classes[] = 'sticky';
		}
		if ( ! pixwell_is_featured_image( 'pixwell_780x0' ) ) {
			$post_classes[] = 'no-feat';
		}
		if ( empty( pixwell_post_meta_info( $settings ) ) && empty( $settings['readmore'] ) ) {
			$post_classes[] = 'rb-hf';
		}
		if ( empty( pixwell_get_option( 'meta_author_icon' ) ) || ! isset( $settings['entry_meta']['enabled']['author'] ) ) {
			$post_classes[] = 'no-avatar';
		}
		if ( ! empty( $padding ) ) {
			$post_classes[] = 'is-padding';
		} ?>
		<div class="<?php echo join( ' ', $post_classes ); ?>">
			<div class="p-header">
				<?php pixwell_post_cat_info( $settings ); ?>
				<?php pixwell_post_title( $settings['h_tag'], $settings['bookmark'], 'h1' ); ?>
			</div>
			<?php if ( pixwell_is_featured_image( 'pixwell_780x0' ) ) : ?>
				<div class="p-feat-holder">
					<div class="p-feat">
						<?php pixwell_post_thumb( 'pixwell_780x0' , 'autosize' ); ?>
					</div>
					<?php pixwell_post_review_info( $settings ); ?>
				</div>
			<?php endif; ?>
			<?php pixwell_post_summary( $settings ); ?>
			<div class="p-footer">
				<?php echo pixwell_post_meta_info( $settings );
				pixwell_post_readmore( $settings ); ?>
			</div>
		</div>
	<?php
	}
endif;