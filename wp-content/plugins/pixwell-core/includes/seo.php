<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_head', 'pixwell_site_description', 1 );
add_action( 'wp_head', 'pixwell_schema_organization', 5 );
add_action( 'wp_head', 'pixwell_post_review_markup', 5 );
add_action( 'wp_head', 'pixwell_schema_website', 10 );
add_action( 'wp_head', 'pixwell_opengraph_meta', 20 );
add_filter( 'wpseo_title', 'pixwell_composer_title', 10, 1 );
add_action( 'wp_footer', 'pixwell_post_list_markup' );
add_action( 'pixwell_after_post_query', 'pixwell_add_post_list', 10, 1 );
add_action( 'pixwell_after_portfolio_query', 'pixwell_add_post_list', 10, 1 );

/**
 * site description
 */
if ( ! function_exists( 'pixwell_site_description' ) ):
	function pixwell_site_description() {

		if ( ! is_front_page() ) {
			return false;
		}

		$content = pixwell_get_option( 'site_description' );
		if ( ! empty ( $content ) ) {
			echo '<meta name="description" content="' . esc_html( $content ) . '">';
		}

		return false;
	}
endif;


/**
 * @return bool
 * organization schema markup
 */
if ( ! function_exists( 'pixwell_schema_organization' ) ):
	function pixwell_schema_organization() {
		$schema = pixwell_get_option( 'organization_markup' );

		if ( empty( $schema ) ) {
			return false;
		}

		$site_street   = pixwell_get_option( 'site_street' );
		$site_locality = pixwell_get_option( 'site_locality' );
		$site_phone    = pixwell_get_option( 'site_phone' );
		$site_email    = pixwell_get_option( 'site_email' );
		$postal_code   = pixwell_get_option( 'postal_code' );
		$protocol      = pixwell_protocol();


		$home_url = home_url( '/' );

		$json_ld = array(
			'@context'  => $protocol . '://schema.org',
			'@type'     => 'Organization',
			'legalName' => get_bloginfo( 'name' ),
			'url'       => $home_url
		);

		if ( ! empty( $site_street ) || ! empty( $site_locality ) ) {
			$json_ld['address']['@type'] = 'PostalAddress';

			if ( ! empty( $site_street ) ) {
				$json_ld['address']['streetAddress'] = esc_html( $site_street );
			}

			if ( ! empty( $site_locality ) ) {
				$json_ld['address']['addressLocality'] = esc_html( $site_locality );
			}

			if ( ! empty( $postal_code ) ) {
				$json_ld['address']['postalCode'] = esc_html( $postal_code );
			}
		}

		if ( ! empty( $site_email ) ) {
			$json_ld['email'] = esc_html( $site_email );
		}

		if ( ! empty( $site_phone ) ) {
			$json_ld['contactPoint'] = array(
				'@type'       => 'ContactPoint',
				'telephone'   => esc_html( $site_phone ),
				'contactType' => 'customer service',
			);
		}

		$logo = pixwell_get_option( 'site_logo' );
		if ( ! empty( $logo['url'] ) ) {
			$json_ld['logo'] = $logo['url'];
		}

		$social = array(
			pixwell_get_option( 'social_facebook' ),
			pixwell_get_option( 'social_twitter' ),
			pixwell_get_option( 'social_instagram' ),
			pixwell_get_option( 'social_pinterest' ),
			pixwell_get_option( 'social_linkedin' ),
			pixwell_get_option( 'social_tumblr' ),
			pixwell_get_option( 'social_flickr' ),
			pixwell_get_option( 'social_skype' ),
			pixwell_get_option( 'social_snapchat' ),
			pixwell_get_option( 'social_myspace' ),
			pixwell_get_option( 'social_youtube' ),
			pixwell_get_option( 'social_bloglovin' ),
			pixwell_get_option( 'social_digg' ),
			pixwell_get_option( 'social_dribbble' ),
			pixwell_get_option( 'social_soundcloud' ),
			pixwell_get_option( 'social_vimeo' ),
			pixwell_get_option( 'social_reddit' ),
			pixwell_get_option( 'social_vk' ),
			pixwell_get_option( 'social_telegram' ),
			pixwell_get_option( 'social_whatsapp' ),
			pixwell_get_option( 'social_rss' )
		);

		foreach ( $social as $key => $el ) {
			if ( empty( $el ) || '#' == $el ) {
				unset( $social[ $key ] );
			}
		}

		if ( count( $social ) ) {
			$json_ld['sameAs'] = array_values( $social );
		}

		echo '<script type="application/ld+json">';
		if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
			echo wp_json_encode( $json_ld, JSON_UNESCAPED_SLASHES );
		} else {
			echo wp_json_encode( $json_ld );
		}
		echo '</script>', "\n";

		return false;
	}
endif;


/**
 * link search box schema
 */
if ( ! function_exists( 'pixwell_schema_website' ) ):
	function pixwell_schema_website() {

		$schema = pixwell_get_option( 'website_markup' );
		if ( empty( $schema ) ) {
			return false;
		}

		$protocol = pixwell_protocol();
		$home_url = home_url( '/' );
		$json_ld  = array(
			'@context'        => $protocol . '://schema.org',
			'@type'           => 'WebSite',
			'@id'             => $home_url . '#website',
			'url'             => $home_url,
			'name'            => get_bloginfo( 'name' ),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => $home_url . '?s={search_term_string}',
				'query-input' => 'required name=search_term_string',
			),
		);

		echo '<script type="application/ld+json">';
		if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
			echo wp_json_encode( $json_ld, JSON_UNESCAPED_SLASHES );
		} else {
			echo wp_json_encode( $json_ld );
		}
		echo '</script>', "\n";

		return false;
	}
endif;


/**
 * @return bool
 * schema markup for single post
 */
if ( ! function_exists( 'pixwell_schema_article' ) ):
	function pixwell_schema_article() {

		if ( ! is_single() ) {
			return false;
		}

		$disable_schema = pixwell_get_option( 'article_markup' );
		$single_schema  = rb_get_meta( 'single_schema' );
		if ( ! empty( $single_schema ) && 1 == $single_schema ) {
			return false;
		}

		if ( ! empty( $disable_schema ) ) {
			return false;
		}

		$protocol  = pixwell_protocol();
		$publisher = get_bloginfo( 'name' );
		$logo      = pixwell_get_option( 'site_logo' );
		if ( ! empty( $logo['url'] ) ) {
			$publisher_logo = esc_url( $logo['url'] );
		}
		$subtitle = rb_get_meta( 'title_tagline' );

		$feat_attachment = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' ); ?>
		<div class="article-meta is-hidden">
			<meta itemprop="mainEntityOfPage" content="<?php echo get_permalink(); ?>">
			<span class="vcard author" itemprop="author" content="<?php echo get_the_author_meta( 'display_name' ); ?>"><span class="fn"><?php echo get_the_author_meta( 'display_name' ); ?></span></span>
			<time class="date published entry-date" datetime="<?php echo date( DATE_W3C, get_the_time( 'U', get_the_ID() ) ); ?>" content="<?php echo date( DATE_W3C, get_the_time( 'U', get_the_ID() ) ); ?>" itemprop="datePublished"><?php echo get_the_date( '', get_the_ID() ) ?></time>
			<meta class="updated" itemprop="dateModified" content="<?php echo date( DATE_W3C, get_the_modified_date( 'U', get_the_ID() ) ); ?>">
			<?php if ( ! empty( $feat_attachment[0] ) ) : ?>
				<span itemprop="image" itemscope itemtype="<?php echo esc_attr( $protocol ); ?>://schema.org/ImageObject">
				<meta itemprop="url" content="<?php echo esc_url( $feat_attachment[0] ); ?>">
				<meta itemprop="width" content="<?php echo esc_attr( $feat_attachment[1] ); ?>">
				<meta itemprop="height" content="<?php echo esc_attr( $feat_attachment[2] ); ?>">
				</span>
			<?php endif; ?>
			<?php if ( ! empty( $subtitle ) ) : ?>
				<meta itemprop="description" content="<?php echo esc_attr( $subtitle ); ?>">
			<?php endif; ?>
			<span itemprop="publisher" itemscope itemtype="<?php echo esc_attr( $protocol ) ?>://schema.org/Organization">
				<meta itemprop="name" content="<?php echo esc_attr( $publisher ); ?>">
				<?php if ( ! empty( $publisher_logo ) ) : ?>
					<span itemprop="logo" itemscope itemtype="<?php echo esc_attr( $protocol ) ?>://schema.org/ImageObject">
				<meta itemprop="url" content="<?php echo esc_url( $publisher_logo ); ?>">
				</span>
				<?php endif; ?>
				</span>
		</div>
	<?php
	}
endif;


/**
 * opengraph meta
 */
if ( ! function_exists( 'pixwell_opengraph_meta' ) ) :
	function pixwell_opengraph_meta() {

		$open_graph = pixwell_get_option( 'open_graph' );

		if ( empty( $open_graph ) ) {
			return false;
		}

		if ( ! is_singular() || is_page_template( 'rbc-frontend.php' ) ) {
			return false;
		}

		if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
			$yoast_social = get_option( 'wpseo_social' );
			if ( ! empty( $yoast_social['opengraph'] ) ) {
				return false;
			}
		}

		global $post;
		$facebook_app_id = pixwell_get_option( 'facebook_app_id' ); ?>
		<meta property="og:title" content="<?php echo esc_attr( get_the_title() ); ?>"/>
		<?php if ( get_post_type( 'post' ) ) : ?>
			<meta property="og:type" content="article"/>
		<?php endif; ?>
		<meta property="og:url" content="<?php echo get_permalink(); ?>"/>
		<meta property="og:site_name" content="<?php bloginfo( 'name' ); ?>"/>
		<?php if ( has_post_thumbnail( $post->ID ) ) :
			$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'pixwell_780x0-2x' ); ?>
			<meta property="og:image" content="<?php echo esc_url( $thumbnail_src[0] ); ?>"/>
		<?php
		else :
			$logo = pixwell_get_option( 'facebook_default_img' );
			if ( empty( $logo['url'] ) ) {
				$logo = pixwell_get_option( 'site_logo' );
			}
			if ( ! empty( $logo['url'] ) ): ?>
				<meta property="og:image" content="<?php echo esc_url( $logo['url'] ); ?>"/>
			<?php endif;
		endif;
		if ( ! empty( $facebook_app_id ) ) : ?>
			<meta property="fb:facebook_app_id" content="<?php echo esc_attr( $facebook_app_id ); ?>"/>
		<?php endif;

		return false;
	}
endif;


/**
 * render breadcrumb
 */
if ( ! function_exists( 'pixwell_breadcrumb' ) ) :
	function pixwell_breadcrumb( $classes = 'rbc-container rb-p20-gutter' ) {

		$breadcrumbs = pixwell_get_option( 'site_breadcrumb' );
		if ( empty( $breadcrumbs ) ) {
			return;
		}
		$class_name = 'breadcrumb-inner';
		if ( ! empty( $classes ) ) {
			$class_name .= ' ' . $classes;
		}
		if ( function_exists( 'bcn_display' ) ) : ?>
			<aside id="site-breadcrumb" class="breadcrumb breadcrumb-navxt">
				<span class="<?php echo esc_attr( $class_name ); ?>" vocab="<?php echo pixwell_protocol(); ?>://schema.org/" typeof="BreadcrumbList"><?php bcn_display(); ?></span>
			</aside>
			<?php
			return;
		endif;

		if ( function_exists( 'yoast_breadcrumb' ) ) :
			yoast_breadcrumb( '<aside id="site-breadcrumb"><span class="breadcrumb breadcrumb-yoast"><div class="' . esc_attr( $class_name ) . '">', '</div></span></aside>' );

			return;
		endif;
	}
endif;


/** Yoast seo title */
if ( ! function_exists( 'pixwell_composer_title' ) ) {
	function pixwell_composer_title( $seo_title ) {

		if ( ! in_the_loop() && is_page_template( 'rbc-frontend.php' ) ) {

			$get_paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
			$get_page  = get_query_var( 'page' ) ? get_query_var( 'page' ) : 1;

			if ( $get_paged > $get_page ) {
				$paged = $get_paged;
			} else {
				$paged = $get_page;
			}

			if ( $paged > 1 ) {
				return $seo_title . ' - ' . pixwell_translate( 'page' ) . ' ' . $paged;
			}
		}

		return $seo_title;
	}
}


/** add query data */
if ( ! function_exists( 'pixwell_add_post_list' ) ) {
	function pixwell_add_post_list( $query ) {

		if ( ! isset( $GLOBALS['pixwell_pids'] ) || is_single() ) {
			return false;
		}

		if ( ! empty( $query->posts ) ) {
			$post_ids = wp_list_pluck( $query->posts, 'ID' );
			if ( is_array( $post_ids ) ) {
				foreach ( $post_ids as $post_id ) {
					array_push( $GLOBALS['pixwell_pids'], $post_id );
				}
			}
		}

		return false;
	}
}

/** item list markup */
if ( ! function_exists( 'pixwell_post_list_markup' ) ) {
	function pixwell_post_list_markup() {

		if ( ! isset( $GLOBALS['pixwell_pids'] ) || ! array_filter( $GLOBALS['pixwell_pids'] ) ) {
			return false;
		}

		$items_list = array();
		$index      = 1;

		$items = array_unique( $GLOBALS['pixwell_pids'] );
		foreach ( $items as $post_id ) {
			$data = array(
				'@type'    => "ListItem",
				'position' => $index,
				'url'      => get_permalink( $post_id ),
				'name'     => get_the_title( $post_id ),
				'image'    => get_the_post_thumbnail_url( $post_id, 'full' )
			);
			array_push( $items_list, $data );
			$index ++;
		}
		$post_data = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'ItemList',
			"itemListElement" => $items_list
		);

		echo '<script type="application/ld+json">';
		if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
			echo wp_json_encode( $post_data, JSON_UNESCAPED_SLASHES );
		} else {
			echo wp_json_encode( $post_data );
		}
		echo '</script>', "\n";

		return false;
	}
}


/**
 * product review markup
 */
if ( ! function_exists( 'pixwell_post_review_markup' ) ) {
	function pixwell_post_review_markup() {

		if ( ! is_single() ) {
			return false;
		}

		$disable_markup = pixwell_get_option( 'disable_review_markup' );
		if ( ! empty( $disable_markup ) ) {
			return false;
		}

		$post_id = get_the_ID();
		$review  = rb_get_meta( 'post_review', $post_id );
		if ( empty( $review ) || 1 != $review || empty( $post_id ) ) {
			return false;
		}

		$protocol     = pixwell_protocol();
		$total_stars  = get_post_meta( $post_id, 'pixwell_review_stars', true );
		$user_rating  = get_post_meta( $post_id, 'pixwell_user_rating', true );
		$author       = get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) );
		$image        = get_the_post_thumbnail_url( $post_id, 'full' );
		$name         = get_the_title( $post_id );
		$description  = rb_get_meta( 'review_summary', $post_id );
		$sku          = get_post_field( 'post_name', $post_id );
		$rating_val   = $total_stars;
		$rating_count = 3;

		if ( empty( $description ) ) {
			$description = get_the_excerpt( $post_id );
		}

		$json_ld = array(
			'@context'    => $protocol . '://schema.org',
			'@type'       => 'Product',
			'description' => $description,
			'image'       => $image,
			'name'        => $name,
			'mpn'         => $post_id,
			'sku'         => $sku,
			'brand'       => array(
				'@type' => 'Brand',
				'name'  => get_bloginfo( 'name' ),
			),
		);

		if ( ! empty( $user_rating['total'] ) && ! empty( $user_rating['average'] ) ) {
			$rating_val   = $user_rating['average'];
			$rating_count = intval( $user_rating['total'] );
		}

		$json_ld['review'] = array(
			'author'       => array(
				'@type' => 'Person',
				'name'  => $author
			),
			'@type'        => 'Review',
			'reviewRating' => array(
				'@type'       => 'Rating',
				'ratingValue' => $total_stars,
				'bestRating'  => 5,
				'worstRating' => 1,
			),
		);

		$json_ld['aggregateRating'] = array(
			'@type'       => 'AggregateRating',
			'ratingValue' => $rating_val,
			'ratingCount' => $rating_count,
			'bestRating'  => 5,
			'worstRating' => 1,
		);

		echo '<script type="application/ld+json">';
		if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
			echo wp_json_encode( $json_ld, JSON_UNESCAPED_SLASHES );
		} else {
			echo wp_json_encode( $json_ld );
		}
		echo '</script>', "\n";

		return false;
	}
}