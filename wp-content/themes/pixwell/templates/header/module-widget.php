<?php
/** right menu section */
if ( is_active_sidebar( 'pixwell_header_rnav' ) ) : ?>
	<aside class="rnav-section">
		<?php dynamic_sidebar( 'pixwell_header_rnav' ); ?>
	</aside>
<?php endif;