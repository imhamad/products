<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>
	<main id="main" class="rbc-site-main composer-main">
		<?php
		do_action( 'rbc_before_content' );
		if ( have_posts() ) {
			$get_paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
			$get_page  = get_query_var( 'page' ) ? get_query_var( 'page' ) : 1;
			if ( $get_paged > $get_page ) {
				$paged = $get_paged;
			} else {
				$paged = $get_page;
			}
			if ( empty( $paged ) || $paged < 2 ) :
				while ( have_posts() ) : the_post();
					remove_filter( 'the_content', 'wpautop' );
					the_content();
				endwhile;
			endif;
		}
		wp_reset_postdata();
		do_action( 'rbc_after_content' );
		?>
	</main>
<?php get_footer();