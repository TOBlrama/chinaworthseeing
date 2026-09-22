<?php
/**
 * Build the confirmed Explore China information architecture.
 *
 * This script uses native WordPress pages and the existing Primary Navigation
 * menu. It is designed to be safe to rerun: managed pages and menu items are
 * updated in place, while later editor-authored page content is preserved when
 * the CWS marker is removed.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$marker = 'cws-managed-explore-architecture-v1';

$destinations = array(
	array( 'key' => 'beijing', 'title' => 'Beijing', 'slug' => 'beijing', 'tagline' => 'Where imperial China still feels alive.', 'image_id' => 90 ),
	array( 'key' => 'shanghai', 'title' => 'Shanghai', 'slug' => 'shanghai', 'tagline' => 'China at its most modern, stylish and energetic.', 'image_id' => 91 ),
	array( 'key' => 'xian', 'title' => "Xi'an", 'slug' => 'xian', 'tagline' => 'The gateway to China’s ancient past.', 'image_id' => 92 ),
	array( 'key' => 'guilin', 'title' => 'Guilin', 'slug' => 'guilin', 'tagline' => 'China’s most iconic landscapes.', 'image_id' => 93 ),
	array( 'key' => 'hangzhou', 'title' => 'Hangzhou', 'slug' => 'hangzhou', 'tagline' => 'Lakeside beauty, tea and a slower rhythm.', 'image_id' => 94 ),
	array( 'key' => 'suzhou', 'title' => 'Suzhou', 'slug' => 'suzhou', 'tagline' => 'Classical gardens, canals and Jiangnan elegance.', 'image_id' => 95 ),
	array( 'key' => 'guangzhou', 'title' => 'Guangzhou', 'slug' => 'guangzhou', 'tagline' => 'The heart of Cantonese food and southern China.', 'image_id' => 96 ),
);

$experiences = array(
	array( 'key' => 'natural-wonders', 'title' => 'Natural Wonders', 'slug' => 'natural-wonders', 'summary' => 'Discover the landscapes that make China unlike anywhere else.', 'image_id' => 81 ),
	array( 'key' => 'history-heritage', 'title' => 'History & Heritage', 'slug' => 'history-heritage', 'summary' => 'Step into thousands of years of history, from imperial landmarks to ancient streets and living traditions.', 'image_id' => 82 ),
	array( 'key' => 'flavours-of-china', 'title' => 'Flavours of China', 'slug' => 'flavours-of-china', 'summary' => 'Taste China beyond the tourist checklist, from regional classics to the places locals actually love.', 'image_id' => 83 ),
);

$guides = array(
	array( 'key' => 'first-time-in-china', 'title' => 'First Time in China', 'slug' => 'first-time-in-china', 'summary' => 'Everything you need to know before your first trip.', 'image_id' => 28 ),
	array( 'key' => 'when-to-visit', 'title' => 'When to Visit', 'slug' => 'when-to-visit', 'summary' => 'A practical starting point for choosing when to travel.', 'image_id' => 0 ),
	array( 'key' => 'getting-around', 'title' => 'Getting Around', 'slug' => 'getting-around', 'summary' => 'Understand the main ways to travel within and between Chinese cities.', 'image_id' => 0 ),
	array( 'key' => 'payments-apps', 'title' => 'Payments & Apps', 'slug' => 'payments-apps', 'summary' => 'Prepare for the payment and app setup international visitors commonly need.', 'image_id' => 0 ),
	array( 'key' => 'entry-visa', 'title' => 'Entry & Visa', 'slug' => 'entry-visa', 'summary' => 'Start with the official entry requirements that apply to your trip.', 'image_id' => 0 ),
);

$image_alts = array(
	28 => 'The Great Wall winding across green mountains near Beijing',
	81 => 'Zhangjiajie sandstone pillars glowing in warm evening light',
	82 => 'Historic palace architecture inside the Forbidden City in Beijing',
	83 => 'Chinese roast duck served with pancakes, cucumber and sauces',
	90 => 'Temple of Heaven beneath a wide blue sky in Beijing',
	91 => 'Shanghai skyline and the Oriental Pearl Tower at night',
	92 => "Terracotta Warriors at the archaeological site near Xi'an",
	93 => 'Li River winding through misty karst mountains near Guilin',
	94 => 'Stone lanterns glowing over West Lake at sunset in Hangzhou',
	95 => 'Moon gate and pond in a classical Suzhou garden at dusk',
	96 => 'Canton Tower rising above modern architecture in Guangzhou',
);

foreach ( $image_alts as $image_id => $alt ) {
	if ( get_post( $image_id ) ) {
		update_post_meta( $image_id, '_wp_attachment_image_alt', $alt );
	}
}

$find_page = static function ( $slug, $parent_id = 0 ) {
	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => array( 'publish', 'draft', 'private', 'pending' ),
			'name'           => $slug,
			'post_parent'    => (int) $parent_id,
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	return $pages ? (int) $pages[0] : 0;
};

$upsert_page = static function ( $args ) use ( $find_page, $marker ) {
	$page_id = ! empty( $args['existing_id'] ) ? (int) $args['existing_id'] : $find_page( $args['slug'], $args['parent'] ?? 0 );
	$current = $page_id ? get_post( $page_id ) : null;
	$content = (string) ( $args['content'] ?? '' );

	$post_data = array(
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_title'   => $args['title'],
		'post_name'    => $args['slug'],
		'post_parent'  => (int) ( $args['parent'] ?? 0 ),
		'post_excerpt' => (string) ( $args['excerpt'] ?? '' ),
	);

	if ( ! $current || '' === trim( (string) $current->post_content ) || false !== strpos( (string) $current->post_content, $marker ) ) {
		$post_data['post_content'] = $content;
	}

	if ( $page_id ) {
		$post_data['ID'] = $page_id;
		$result          = wp_update_post( wp_slash( $post_data ), true );
	} else {
		$result = wp_insert_post( wp_slash( $post_data ), true );
	}

	if ( is_wp_error( $result ) ) {
		throw new RuntimeException( $result->get_error_message() );
	}

	$page_id = (int) $result;

	if ( ! empty( $args['image_id'] ) ) {
		set_post_thumbnail( $page_id, (int) $args['image_id'] );
	}

	update_post_meta( $page_id, '_cws_content_status', (string) ( $args['content_status'] ?? 'placeholder' ) );

	return $page_id;
};

$image_block = static function ( $image_id, $class_name, $link = '', $eager = false ) {
	$image_id = (int) $image_id;
	$image    = wp_get_attachment_image(
		$image_id,
		'large',
		false,
		array_filter(
			array(
				'class'         => 'wp-image-' . $image_id,
				'loading'       => $eager ? 'eager' : 'lazy',
				'decoding'      => 'async',
				'fetchpriority' => $eager ? 'high' : '',
			)
		)
	);

	if ( ! $image ) {
		throw new RuntimeException( sprintf( 'Missing media attachment %d.', $image_id ) );
	}

	if ( $link ) {
		return sprintf(
			'<!-- wp:image {"id":%1$d,"sizeSlug":"large","linkDestination":"custom","href":%2$s,"className":"%3$s"} -->
<figure class="wp-block-image size-large %3$s"><a href="%4$s">%5$s</a></figure>
<!-- /wp:image -->',
			$image_id,
			wp_json_encode( $link ),
			esc_attr( $class_name ),
			esc_url( $link ),
			$image
		);
	}

	return sprintf(
		'<!-- wp:image {"id":%1$d,"sizeSlug":"large","linkDestination":"none","className":"%2$s"} -->
<figure class="wp-block-image size-large %2$s">%3$s</figure>
<!-- /wp:image -->',
		$image_id,
		esc_attr( $class_name ),
		$image
	);
};

$placeholder_content = static function ( $summary, $dynamic = false ) use ( $marker ) {
	$notice = $dynamic
		? 'This practical guide is being prepared. Rules and requirements can change, so verify current details with official sources before travel.'
		: 'This guide is being prepared. The page is published now so the site structure and navigation can be reviewed.';

	return sprintf(
		'<!-- %1$s -->
<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">%2$s</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>%3$s</p>
<!-- /wp:paragraph -->',
		esc_html( $marker ),
		esc_html( $summary ),
		esc_html( $notice )
	);
};

$destinations_parent_id = $upsert_page(
	array(
		'title'          => 'Destinations',
		'slug'           => 'destinations',
		'parent'         => 0,
		'content'        => '<!-- ' . $marker . ' -->',
		'excerpt'        => 'Explore the first seven ChinaWorthSeeing destinations.',
		'content_status' => 'structure',
	)
);

$destination_ids = array();
foreach ( $destinations as $destination ) {
	$destination_ids[ $destination['key'] ] = $upsert_page(
		array(
			'title'          => $destination['title'],
			'slug'           => $destination['slug'],
			'parent'         => $destinations_parent_id,
			'content'        => $placeholder_content( $destination['tagline'] ),
			'excerpt'        => $destination['tagline'],
			'image_id'       => $destination['image_id'],
			'content_status' => 'placeholder',
		)
	);
}

$experiences_parent_id = $upsert_page(
	array(
		'title'          => 'Experiences',
		'slug'           => 'experiences',
		'parent'         => 0,
		'content'        => '<!-- ' . $marker . ' -->',
		'excerpt'        => 'Find China through landscapes, heritage and food.',
		'content_status' => 'structure',
	)
);

$experience_ids = array();
foreach ( $experiences as $experience ) {
	$experience_ids[ $experience['key'] ] = $upsert_page(
		array(
			'title'          => $experience['title'],
			'slug'           => $experience['slug'],
			'parent'         => $experiences_parent_id,
			'content'        => $placeholder_content( $experience['summary'] ),
			'excerpt'        => $experience['summary'],
			'image_id'       => $experience['image_id'],
			'content_status' => 'placeholder',
		)
	);
}

$plan_parent_id = $upsert_page(
	array(
		'title'          => 'Plan Your Trip',
		'slug'           => 'plan-your-trip',
		'parent'         => 0,
		'content'        => '<!-- ' . $marker . ' -->',
		'excerpt'        => 'Practical guidance for planning a first trip to China.',
		'content_status' => 'structure',
	)
);

$guide_ids = array();
foreach ( $guides as $guide ) {
	$guide_ids[ $guide['key'] ] = $upsert_page(
		array(
			'title'          => $guide['title'],
			'slug'           => $guide['slug'],
			'parent'         => $plan_parent_id,
			'content'        => $placeholder_content( $guide['summary'], 'entry-visa' === $guide['key'] ),
			'excerpt'        => $guide['summary'],
			'image_id'       => $guide['image_id'],
			'content_status' => 'placeholder',
		)
	);
}

$destination_cards = '';
foreach ( $destinations as $destination ) {
	$url = get_permalink( $destination_ids[ $destination['key'] ] );
	$destination_cards .= sprintf(
		'<!-- wp:group {"className":"cws-explore-place cws-explore-place--%1$s","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-explore-place cws-explore-place--%1$s">
%2$s
<!-- wp:heading {"level":3,"className":"cws-explore-place__title"} -->
<h3 class="wp-block-heading cws-explore-place__title"><a href="%3$s">%4$s</a></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"cws-explore-place__copy"} -->
<p class="cws-explore-place__copy">%5$s</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
		esc_attr( $destination['key'] ),
		$image_block( $destination['image_id'], 'cws-explore-place__media', $url ),
		esc_url( $url ),
		esc_html( $destination['title'] ),
		esc_html( $destination['tagline'] )
	);
}

$experience_tiles = '';
foreach ( $experiences as $experience ) {
	$url = get_permalink( $experience_ids[ $experience['key'] ] );
	$experience_tiles .= sprintf(
		'<!-- wp:group {"className":"cws-explore-experience cws-explore-experience--%1$s","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-explore-experience cws-explore-experience--%1$s">
%2$s
<!-- wp:group {"className":"cws-explore-experience__body","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-explore-experience__body">
<!-- wp:heading {"level":3,"className":"cws-explore-experience__title"} -->
<h3 class="wp-block-heading cws-explore-experience__title"><a href="%3$s">%4$s</a></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"cws-explore-experience__copy"} -->
<p class="cws-explore-experience__copy">%5$s</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->',
		esc_attr( $experience['key'] ),
		$image_block( $experience['image_id'], 'cws-explore-experience__media', $url ),
		esc_url( $url ),
		esc_html( $experience['title'] ),
		esc_html( $experience['summary'] )
	);
}

$planning_links = '';
foreach ( array_slice( $guides, 1 ) as $guide ) {
	$url = get_permalink( $guide_ids[ $guide['key'] ] );
	$planning_links .= sprintf(
		'<!-- wp:paragraph {"className":"cws-explore-planning__link"} -->
<p class="cws-explore-planning__link"><a href="%1$s"><span>%2$s</span><span aria-hidden="true">→</span></a></p>
<!-- /wp:paragraph -->',
		esc_url( $url ),
		esc_html( $guide['title'] )
	);
}

$first_time_url = get_permalink( $guide_ids['first-time-in-china'] );
$explore_content = sprintf(
	'<!-- %1$s -->
<!-- wp:group {"tagName":"div","className":"cws-explore-hub","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-explore-hub">
<!-- wp:group {"tagName":"section","className":"cws-explore-hub__hero","layout":{"type":"constrained"}} -->
<section class="wp-block-group cws-explore-hub__hero">
%2$s
<!-- wp:group {"className":"cws-explore-hub__hero-content","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-explore-hub__hero-content">
<!-- wp:heading {"level":1,"className":"cws-explore-hub__title"} -->
<h1 class="wp-block-heading cws-explore-hub__title">Explore China</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"cws-explore-hub__intro"} -->
<p class="cws-explore-hub__intro">Cities, landscapes, flavours and practical advice to help you find the China worth seeing.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"cws-explore-places","layout":{"type":"constrained"}} -->
<section class="wp-block-group cws-explore-places">
<!-- wp:group {"className":"cws-explore-section__inner","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-explore-section__inner">
<!-- wp:group {"className":"cws-explore-section__header","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-explore-section__header">
<!-- wp:heading {"level":2,"className":"cws-explore-section__title"} -->
<h2 class="wp-block-heading cws-explore-section__title">Places to Go</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"cws-explore-section__action"} -->
<p class="cws-explore-section__action"><a href="%3$s">View all places <span aria-hidden="true">→</span></a></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"cws-explore-places__grid","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-explore-places__grid">%4$s</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"cws-explore-experiences","layout":{"type":"constrained"}} -->
<section class="wp-block-group cws-explore-experiences">
<!-- wp:group {"className":"cws-explore-section__inner","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-explore-section__inner">
<!-- wp:heading {"level":2,"className":"cws-explore-section__title"} -->
<h2 class="wp-block-heading cws-explore-section__title">Find China Your Way</h2>
<!-- /wp:heading -->
<!-- wp:group {"className":"cws-explore-experiences__grid","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-explore-experiences__grid">%5$s</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"cws-explore-planning","layout":{"type":"constrained"}} -->
<section class="wp-block-group cws-explore-planning">
<!-- wp:group {"className":"cws-explore-section__inner","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-explore-section__inner">
<!-- wp:heading {"level":2,"className":"cws-explore-section__title"} -->
<h2 class="wp-block-heading cws-explore-section__title">Planning Your First Trip?</h2>
<!-- /wp:heading -->
<!-- wp:group {"className":"cws-explore-planning__grid","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-explore-planning__grid">
<!-- wp:group {"className":"cws-explore-planning__featured","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-explore-planning__featured">
%6$s
<!-- wp:heading {"level":3,"className":"cws-explore-planning__featured-title"} -->
<h3 class="wp-block-heading cws-explore-planning__featured-title"><a href="%7$s">First Time in China</a></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"cws-explore-planning__featured-copy"} -->
<p class="cws-explore-planning__featured-copy">Everything you need to know before your first trip.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"cws-explore-planning__links","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-explore-planning__links">%8$s</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
</div>
<!-- /wp:group -->',
	esc_html( $marker ),
	$image_block( 28, 'cws-explore-hub__hero-media', '', true ),
	esc_url( get_permalink( $destinations_parent_id ) ),
	$destination_cards,
	$experience_tiles,
	$image_block( 28, 'cws-explore-planning__featured-media', $first_time_url ),
	esc_url( $first_time_url ),
	$planning_links
);

$old_explore_slug = (string) get_post_field( 'post_name', 15 );
if ( $old_explore_slug && 'explore-china' !== $old_explore_slug ) {
	add_post_meta( 15, '_wp_old_slug', $old_explore_slug );
}

$explore_page_id = $upsert_page(
	array(
		'existing_id'    => 15,
		'title'          => 'Explore China',
		'slug'           => 'explore-china',
		'parent'         => 0,
		'content'        => $explore_content,
		'excerpt'        => 'Cities, landscapes, flavours and practical advice to help you find the China worth seeing.',
		'content_status' => 'structure',
	)
);

// The hero image is part of the managed block content. Prevent Hever from
// rendering the same image again as a separate page featured image.
delete_post_thumbnail( $explore_page_id );

$index_cards = static function ( $items, $ids, $kind ) use ( $image_block ) {
	$output = '';
	foreach ( $items as $item ) {
		$url     = get_permalink( $ids[ $item['key'] ] );
		$summary = $item['tagline'] ?? $item['summary'];
		$output .= sprintf(
			'<!-- wp:group {"className":"cws-index-card","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-index-card">%1$s
<!-- wp:heading {"level":2,"className":"cws-index-card__title"} -->
<h2 class="wp-block-heading cws-index-card__title"><a href="%2$s">%3$s</a></h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"cws-index-card__copy"} -->
<p class="cws-index-card__copy">%4$s</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
			! empty( $item['image_id'] ) ? $image_block( $item['image_id'], 'cws-index-card__media', $url ) : '',
			esc_url( $url ),
			esc_html( $item['title'] ),
			esc_html( $summary )
		);
	}

	return sprintf(
		'<!-- wp:group {"className":"cws-index-page cws-index-page--%1$s","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-index-page cws-index-page--%1$s">
<!-- wp:group {"className":"cws-index-page__grid","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-index-page__grid">%2$s</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->',
		esc_attr( $kind ),
		$output
	);
};

$upsert_page(
	array(
		'existing_id'    => $destinations_parent_id,
		'title'          => 'Destinations',
		'slug'           => 'destinations',
		'content'        => '<!-- ' . $marker . ' --><!-- wp:paragraph {"className":"cws-index-page__intro"} --><p class="cws-index-page__intro">Explore the first seven places in the ChinaWorthSeeing destination collection.</p><!-- /wp:paragraph -->' . $index_cards( $destinations, $destination_ids, 'destinations' ),
		'excerpt'        => 'Explore the first seven ChinaWorthSeeing destinations.',
		'content_status' => 'structure',
	)
);

$upsert_page(
	array(
		'existing_id'    => $experiences_parent_id,
		'title'          => 'Experiences',
		'slug'           => 'experiences',
		'content'        => '<!-- ' . $marker . ' --><!-- wp:paragraph {"className":"cws-index-page__intro"} --><p class="cws-index-page__intro">Find China through its landscapes, heritage and regional flavours.</p><!-- /wp:paragraph -->' . $index_cards( $experiences, $experience_ids, 'experiences' ),
		'excerpt'        => 'Find China through landscapes, heritage and food.',
		'content_status' => 'structure',
	)
);

$upsert_page(
	array(
		'existing_id'    => $plan_parent_id,
		'title'          => 'Plan Your Trip',
		'slug'           => 'plan-your-trip',
		'content'        => '<!-- ' . $marker . ' --><!-- wp:paragraph {"className":"cws-index-page__intro"} --><p class="cws-index-page__intro">Practical guidance to reduce the complexity of planning a first trip to China.</p><!-- /wp:paragraph -->' . $index_cards( $guides, $guide_ids, 'planning' ),
		'excerpt'        => 'Practical guidance for planning a first trip to China.',
		'content_status' => 'structure',
	)
);

$menu = wp_get_nav_menu_object( 'primary-navigation' );
if ( ! $menu ) {
	throw new RuntimeException( 'Primary Navigation menu not found.' );
}

$menu_items = wp_get_nav_menu_items( $menu->term_id, array( 'post_status' => 'any' ) );
$menu_items = is_array( $menu_items ) ? $menu_items : array();

$menu_by_key = array();
foreach ( $menu_items as $menu_item ) {
	$key = (string) get_post_meta( $menu_item->ID, '_cws_nav_key', true );
	if ( $key ) {
		$menu_by_key[ $key ] = (int) $menu_item->ID;
	}
}

$find_existing_menu_item = static function ( $object_id = 0, $title = '' ) use ( $menu_items ) {
	foreach ( $menu_items as $menu_item ) {
		if ( $object_id && (int) $menu_item->object_id === (int) $object_id ) {
			return (int) $menu_item->ID;
		}
		if ( $title && $title === $menu_item->title ) {
			return (int) $menu_item->ID;
		}
	}

	return 0;
};

$upsert_menu_item = static function ( $key, $args, $existing_id = 0 ) use ( $menu, &$menu_by_key ) {
	$item_id = $menu_by_key[ $key ] ?? (int) $existing_id;
	$result  = wp_update_nav_menu_item( $menu->term_id, $item_id, $args );

	if ( is_wp_error( $result ) ) {
		throw new RuntimeException( $result->get_error_message() );
	}

	$item_id = (int) $result;
	update_post_meta( $item_id, '_cws_nav_key', $key );
	$menu_by_key[ $key ] = $item_id;

	return $item_id;
};

$page_menu_args = static function ( $title, $page_id, $parent_id, $position, $classes = array() ) {
	return array(
		'menu-item-title'     => $title,
		'menu-item-status'    => 'publish',
		'menu-item-type'      => 'post_type',
		'menu-item-object'    => 'page',
		'menu-item-object-id' => (int) $page_id,
		'menu-item-parent-id' => (int) $parent_id,
		'menu-item-position'  => (int) $position,
		'menu-item-classes'   => implode( ' ', $classes ),
	);
};

$custom_menu_args = static function ( $title, $url, $parent_id, $position, $classes = array() ) {
	return array(
		'menu-item-title'     => $title,
		'menu-item-url'       => $url,
		'menu-item-status'    => 'publish',
		'menu-item-type'      => 'custom',
		'menu-item-object'    => 'custom',
		'menu-item-parent-id' => (int) $parent_id,
		'menu-item-position'  => (int) $position,
		'menu-item-classes'   => implode( ' ', $classes ),
	);
};

$position = 1;
$home_id  = (int) get_option( 'page_on_front' );
$home_menu_id = $upsert_menu_item( 'home', $page_menu_args( 'Home', $home_id, 0, $position++, array( 'cws-nav-standard' ) ), $find_existing_menu_item( $home_id ) );
$explore_menu_id = $upsert_menu_item( 'explore-china', $page_menu_args( 'Explore China', $explore_page_id, 0, $position++, array( 'cws-mega-menu' ) ), $find_existing_menu_item( $explore_page_id ) );

$places_group_id = $upsert_menu_item( 'group-places', $page_menu_args( 'Places to Go', $destinations_parent_id, $explore_menu_id, $position++, array( 'cws-mega-group', 'cws-mega-group--places' ) ) );
foreach ( $destinations as $destination ) {
	$upsert_menu_item( 'destination-' . $destination['key'], $page_menu_args( $destination['title'], $destination_ids[ $destination['key'] ], $places_group_id, $position++ ) );
}
$upsert_menu_item( 'view-all-places', $custom_menu_args( 'View all places', get_permalink( $destinations_parent_id ), $places_group_id, $position++, array( 'cws-mega-view-all' ) ) );

$experiences_group_id = $upsert_menu_item( 'group-experiences', $page_menu_args( 'Experiences', $experiences_parent_id, $explore_menu_id, $position++, array( 'cws-mega-group', 'cws-mega-group--experiences' ) ) );
foreach ( $experiences as $experience ) {
	$upsert_menu_item( 'experience-' . $experience['key'], $page_menu_args( $experience['title'], $experience_ids[ $experience['key'] ], $experiences_group_id, $position++ ) );
}

$plan_group_id = $upsert_menu_item( 'group-plan', $page_menu_args( 'Plan Your Trip', $plan_parent_id, $explore_menu_id, $position++, array( 'cws-mega-group', 'cws-mega-group--plan' ) ) );
foreach ( $guides as $guide ) {
	$upsert_menu_item( 'guide-' . $guide['key'], $page_menu_args( $guide['title'], $guide_ids[ $guide['key'] ], $plan_group_id, $position++ ) );
}

$upsert_menu_item( 'featured-first-time', $page_menu_args( 'First Time in China', $guide_ids['first-time-in-china'], $explore_menu_id, $position++, array( 'cws-mega-featured' ) ) );

$about_id   = 17;
$contact_id = 19;
$upsert_menu_item( 'about', $page_menu_args( 'About', $about_id, 0, $position++, array( 'cws-nav-standard' ) ), $find_existing_menu_item( $about_id ) );
$upsert_menu_item( 'contact', $page_menu_args( 'Contact', $contact_id, 0, $position++, array( 'cws-nav-standard' ) ), $find_existing_menu_item( $contact_id ) );
$upsert_menu_item( 'start-planning', $custom_menu_args( 'Start Planning', get_permalink( $contact_id ), 0, $position++, array( 'cws-nav-cta' ) ), $find_existing_menu_item( 0, 'Start Planning' ) );

$front_page_id = (int) get_option( 'page_on_front' );
$home_content  = (string) get_post_field( 'post_content', $front_page_id );

foreach ( $destinations as $destination ) {
	$url        = esc_url( get_permalink( $destination_ids[ $destination['key'] ] ) );
	$label      = $destination['title'];
	$card_title = strtoupper( $label );

	$home_content = preg_replace(
		'/"href":"#","className":"cws-destination-card__media"/',
		'"href":' . wp_json_encode( $url ) . ',"className":"cws-destination-card__media"',
		$home_content,
		1
	);

	$home_content = str_replace(
		array(
			'<a href="#" data-cws-placeholder="true" aria-label="Discover ' . esc_attr( $label ) . '">',
			'<a href="#" data-cws-placeholder="true">' . esc_html( $card_title ) . '</a>',
			'<a href="#" data-cws-placeholder="true">Discover ' . esc_html( $label ) . ' <span aria-hidden="true">→</span></a>',
		),
		array(
			'<a href="' . $url . '" aria-label="Discover ' . esc_attr( $label ) . '">',
			'<a href="' . $url . '">' . esc_html( $card_title ) . '</a>',
			'<a href="' . $url . '">Discover ' . esc_html( $label ) . ' <span aria-hidden="true">→</span></a>',
		),
		$home_content
	);
}

foreach ( $experiences as $experience ) {
	$url   = esc_url( get_permalink( $experience_ids[ $experience['key'] ] ) );
	$title = esc_html( $experience['title'] );
	$plain = '<h3 class="wp-block-heading has-text-align-center cws-why-card__title">' . $title . '</h3>';
	$link  = '<h3 class="wp-block-heading has-text-align-center cws-why-card__title"><a href="' . $url . '">' . $title . '</a></h3>';
	$home_content = str_replace( $plain, $link, $home_content );
}

$home_content = str_replace(
	'href="/inspirations/">Explore Inspirations</a>',
	'href="' . esc_url( get_permalink( $explore_page_id ) ) . '">Explore China</a>',
	$home_content
);

$current_home_content = (string) get_post_field( 'post_content', $front_page_id );
if ( $home_content !== $current_home_content ) {
	$result = wp_update_post(
		wp_slash(
			array(
				'ID'           => $front_page_id,
				'post_content' => $home_content,
			)
		),
		true
	);

	if ( is_wp_error( $result ) ) {
		throw new RuntimeException( $result->get_error_message() );
	}
}

$css = <<<'CSS'
/* ===== Explore China Architecture — 2026-08-11 ===== */
body .main-navigation .cws-submenu-toggle {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	box-sizing: border-box;
	border: 0;
	background: transparent;
	color: var(--cws-ink, #1e211e);
	cursor: pointer;
}

body .main-navigation .cws-submenu-toggle::before {
	display: block;
	width: 7px;
	height: 7px;
	border-right: 1.5px solid currentColor;
	border-bottom: 1.5px solid currentColor;
	content: "";
	transform: rotate(45deg) translate(-1px, -1px);
	transition: transform 180ms ease;
}

body .main-navigation .menu-item.is-open > .cws-submenu-toggle::before {
	transform: rotate(225deg) translate(-1px, -1px);
}

body .main-navigation .cws-submenu-toggle:focus-visible,
body .main-navigation a:focus-visible {
	outline: 2px solid var(--cws-green, #315f52);
	outline-offset: 3px;
}

body .main-navigation .cws-mega-featured__eyebrow {
	display: block;
	margin-bottom: 12px;
	color: var(--cws-green, #315f52);
	font-size: 11px;
	font-weight: 700;
	letter-spacing: .16em;
	line-height: 1.4;
	text-transform: uppercase;
}

body .main-navigation .cws-mega-featured__media {
	display: block;
	width: 100%;
	margin-bottom: 16px;
	overflow: hidden;
	aspect-ratio: 16 / 9;
	background: #e6e0d5;
}

body .main-navigation .cws-mega-featured__media img {
	display: block;
	width: 100%;
	height: 100%;
	object-fit: cover;
	transition: transform 360ms cubic-bezier(.2, .7, .2, 1);
}

body .main-navigation .cws-mega-featured__title,
body .main-navigation .cws-mega-featured__copy,
body .main-navigation .cws-mega-featured__action {
	display: block;
}

body .main-navigation .cws-mega-featured__title {
	margin-bottom: 8px;
	font-family: Georgia, "Times New Roman", serif;
	font-size: 24px;
	font-weight: 400;
	letter-spacing: -.02em;
	line-height: 1.15;
}

body .main-navigation .cws-mega-featured__copy {
	margin-bottom: 12px;
	color: #49514d;
	font-size: 14px;
	font-weight: 400;
	line-height: 1.5;
}

body .main-navigation .cws-mega-featured__action {
	color: var(--cws-green, #315f52);
	font-size: 13px;
	font-weight: 700;
}

@media (min-width: 783px) {
	body .main-navigation .main-menu > .cws-nav-standard > a {
		display: flex !important;
		align-items: center;
		height: var(--cws-fixed-header-height, 55px);
		padding-top: 0 !important;
		padding-bottom: 0 !important;
	}

	body .main-navigation .main-menu > .cws-nav-cta {
		align-items: center;
	}

	body .main-navigation .main-menu > .cws-nav-cta > a {
		display: block !important;
		height: auto !important;
		min-height: 0 !important;
	}

	body .main-navigation .main-menu > .cws-mega-menu {
		display: flex;
		align-items: center;
	}

	body .main-navigation .main-menu > .cws-mega-menu > a,
	body .main-navigation .main-menu > .cws-mega-menu > .cws-nav-label {
		padding-right: 0 !important;
	}

	body .main-navigation .main-menu > .cws-mega-menu > .cws-nav-label {
		position: relative;
		display: block;
		color: var(--cws-ink, #1e211e);
		font-family: "PT Sans", Arial, sans-serif;
		font-size: 14px;
		font-weight: 700;
		line-height: 1.5;
		cursor: default;
	}

	body .main-navigation .main-menu > .cws-mega-menu.current-menu-item > .cws-nav-label::after,
	body .main-navigation .main-menu > .cws-mega-menu.current-menu-ancestor > .cws-nav-label::after,
	body .main-navigation .main-menu > .cws-mega-menu.current-page-ancestor > .cws-nav-label::after {
		position: absolute;
		right: 0;
		bottom: -7px;
		left: 0;
		height: 2px;
		background: var(--cws-green, #315f52);
		content: "";
	}

	body .main-navigation .main-menu > .cws-mega-menu > .cws-submenu-toggle {
		width: 26px;
		height: 38px;
		margin-left: 3px;
		padding: 0;
	}

	body .main-navigation .cws-mega-menu > .sub-menu {
		position: fixed !important;
		z-index: 999;
		top: var(--cws-fixed-header-height) !important;
		right: 0 !important;
		bottom: auto !important;
		left: 0 !important;
		display: grid !important;
		grid-template-columns: minmax(150px, .9fr) minmax(170px, .9fr) minmax(190px, 1.1fr) minmax(250px, 1.15fr);
		align-items: start !important;
		box-sizing: border-box;
		width: 100vw !important;
		min-width: 0 !important;
		max-width: none !important;
		max-height: calc(100dvh - var(--cws-fixed-header-height));
		margin: 0 !important;
		padding: 34px max(48px, calc((100vw - 1180px) / 2)) 38px !important;
		overflow-y: auto;
		gap: clamp(30px, 3.5vw, 52px);
		border: 0 !important;
		border-top: 1px solid rgba(30, 33, 30, .12) !important;
		border-bottom: 1px solid rgba(30, 33, 30, .12) !important;
		background: #fffdf8 !important;
		box-shadow: none !important;
		opacity: 0 !important;
		visibility: hidden !important;
		pointer-events: none !important;
		transform: translateY(-4px) !important;
		transition: opacity 180ms ease, transform 180ms ease, visibility 180ms ease;
	}

	body .main-navigation .cws-mega-menu > .sub-menu::before,
	body .main-navigation .cws-mega-menu > .sub-menu::after {
		display: none !important;
		content: none !important;
	}

	body .main-navigation .cws-mega-menu a::before,
	body .main-navigation .cws-mega-menu > a::after {
		content: none !important;
	}

	body.admin-bar .main-navigation .cws-mega-menu > .sub-menu {
		top: calc(var(--cws-fixed-header-height) + 32px) !important;
	}

	body .main-navigation .cws-mega-menu.is-open > .sub-menu {
		opacity: 1 !important;
		visibility: visible !important;
		pointer-events: auto !important;
		transform: translateY(0) !important;
	}

	body .main-navigation .cws-mega-menu > .sub-menu > li {
		position: static !important;
		display: block !important;
		float: none !important;
		width: auto !important;
		max-width: none !important;
		margin: 0 !important;
		padding: 0 !important;
		border: 0 !important;
	}

	body .main-navigation .cws-mega-group > a,
	body .main-navigation .cws-mega-group > .cws-nav-label {
		display: inline-block !important;
		margin: 0 0 16px !important;
		padding: 0 !important;
		color: var(--cws-green, #315f52) !important;
		font-family: "PT Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
		font-size: 11px !important;
		font-weight: 700 !important;
		letter-spacing: .16em;
		line-height: 1.4;
		text-decoration: none !important;
		text-transform: uppercase;
		background: transparent !important;
	}

	body .main-navigation .cws-mega-group > .cws-submenu-toggle {
		display: none;
	}

	body .main-navigation .cws-mega-group > .sub-menu {
		position: static !important;
		display: grid !important;
		width: auto !important;
		min-width: 0 !important;
		max-width: none !important;
		margin: 0 !important;
		padding: 0 !important;
		gap: 8px;
		border: 0 !important;
		background: transparent !important;
		box-shadow: none !important;
		opacity: 1 !important;
		visibility: visible !important;
		transform: none !important;
	}

	body .main-navigation .cws-mega-group > .sub-menu > li {
		position: static !important;
		display: block !important;
		float: none !important;
		width: auto !important;
		margin: 0 !important;
		padding: 0 !important;
		border: 0 !important;
	}

	body .main-navigation .cws-mega-group > .sub-menu > li > a {
		display: inline !important;
		width: auto !important;
		margin: 0 !important;
		padding: 2px 0 !important;
		color: var(--cws-ink, #1e211e) !important;
		font-size: 15px !important;
		font-weight: 500 !important;
		line-height: 1.35;
		text-decoration: none !important;
		background: transparent !important;
		box-shadow: inset 0 -1px 0 transparent !important;
		transition: color 160ms ease, box-shadow 160ms ease;
	}

	body .main-navigation .cws-mega-group > .sub-menu > li > a:hover,
	body .main-navigation .cws-mega-group > .sub-menu > li > a:focus-visible,
	body .main-navigation .cws-mega-group > .sub-menu > li.current-menu-item > a {
		color: var(--cws-green, #315f52) !important;
		box-shadow: inset 0 -1px 0 currentColor !important;
	}

	body .main-navigation .cws-mega-group > .sub-menu > .cws-mega-view-all {
		margin-top: 7px !important;
	}

	body .main-navigation .cws-mega-group > .sub-menu > .cws-mega-view-all > a {
		color: var(--cws-green, #315f52) !important;
		font-size: 13px !important;
		font-weight: 700 !important;
	}

	body .main-navigation .cws-mega-featured > a {
		display: block !important;
		width: auto !important;
		margin: 0 !important;
		padding: 0 0 0 clamp(8px, 1.5vw, 22px) !important;
		border-left: 1px solid rgba(30, 33, 30, .14);
		color: var(--cws-ink, #1e211e) !important;
		text-decoration: none !important;
		background: transparent !important;
		box-shadow: none !important;
	}

	body .main-navigation .cws-mega-featured > a:hover .cws-mega-featured__media img,
	body .main-navigation .cws-mega-featured > a:focus-visible .cws-mega-featured__media img {
		transform: scale(1.025);
	}
}

@media (max-width: 782px) {
	body .main-navigation .main-menu-container {
		box-sizing: border-box;
		padding: 68px 20px 32px !important;
		overflow-y: auto;
		background: #fffdf8;
	}

	body .main-navigation .main-menu {
		display: block !important;
		width: 100% !important;
		max-width: none !important;
		margin: 0 !important;
		padding: 0 !important;
	}

	body .main-navigation .main-menu li {
		position: relative !important;
		display: block !important;
		float: none !important;
		width: 100% !important;
		max-width: none !important;
		margin: 0 !important;
		padding: 0 !important;
		border: 0 !important;
	}

	body .main-navigation .main-menu > li {
		border-bottom: 1px solid rgba(30, 33, 30, .12) !important;
	}

	body .main-navigation .main-menu > li > a,
	body .main-navigation .main-menu > li > .cws-nav-label {
		display: block !important;
		box-sizing: border-box;
		width: 100% !important;
		padding: 16px 48px 16px 0 !important;
		color: var(--cws-ink, #1e211e) !important;
		font-size: 17px !important;
		font-weight: 600 !important;
		line-height: 1.35;
		text-decoration: none !important;
		background: transparent !important;
		box-shadow: none !important;
	}

	body .main-navigation .main-menu > .cws-nav-cta {
		margin-top: 18px !important;
		border-bottom: 0 !important;
	}

	body .main-navigation .main-menu > .cws-nav-cta > a {
		padding: 13px 22px !important;
		border-radius: 999px;
		background: var(--cws-green, #315f52) !important;
		color: #fff !important;
		text-align: center;
	}

	body .main-navigation .cws-submenu-toggle {
		position: absolute;
		top: 4px;
		right: 0;
		width: 44px;
		height: 48px;
		padding: 0;
	}

	body .main-navigation .sub-menu {
		position: static !important;
		display: none !important;
		width: 100% !important;
		min-width: 0 !important;
		max-width: none !important;
		margin: 0 !important;
		padding: 0 0 18px 16px !important;
		border: 0 !important;
		background: transparent !important;
		box-shadow: none !important;
		opacity: 1 !important;
		visibility: visible !important;
		transform: none !important;
	}

	body .main-navigation .menu-item.is-open > .sub-menu {
		display: block !important;
	}

	body .main-navigation .cws-mega-menu > .sub-menu > .cws-mega-group {
		border-top: 1px solid rgba(30, 33, 30, .1) !important;
	}

	body .main-navigation .cws-mega-menu > .sub-menu > .cws-mega-group:first-child {
		border-top: 0 !important;
	}

	body .main-navigation .cws-mega-group > a,
	body .main-navigation .cws-mega-group > .cws-nav-label {
		display: block !important;
		box-sizing: border-box;
		width: 100% !important;
		padding: 14px 44px 12px 0 !important;
		color: var(--cws-green, #315f52) !important;
		font-size: 11px !important;
		font-weight: 700 !important;
		letter-spacing: .14em;
		line-height: 1.4;
		text-decoration: none !important;
		text-transform: uppercase;
		background: transparent !important;
	}

	body .main-navigation .cws-mega-group > .cws-submenu-toggle {
		top: 3px;
		height: 44px;
	}

	body .main-navigation .cws-mega-group > .sub-menu {
		padding: 0 0 12px 12px !important;
	}

	body .main-navigation .cws-mega-group > .sub-menu a {
		display: block !important;
		padding: 8px 0 !important;
		color: var(--cws-ink, #1e211e) !important;
		font-size: 15px !important;
		font-weight: 500 !important;
		line-height: 1.4;
		text-decoration: none !important;
		background: transparent !important;
	}

	body .main-navigation .cws-mega-view-all > a {
		color: var(--cws-green, #315f52) !important;
		font-size: 13px !important;
		font-weight: 700 !important;
	}

	body .main-navigation .cws-mega-menu > .sub-menu > .cws-mega-featured {
		display: none !important;
	}

	body .main-navigation .cws-mega-menu a::before,
	body .main-navigation .cws-mega-menu a::after {
		display: none !important;
		content: none !important;
	}

	body .main-navigation #toggle-menu {
		background: var(--cws-green, #315f52) !important;
		color: #fff !important;
	}
}

.page-id-15 .entry-header {
	display: none;
}

.page-id-15 .site-main,
.page-id-15 article.entry,
.page-id-15 .entry-content {
	width: 100%;
	max-width: none !important;
	margin: 0 !important;
	padding-right: 0 !important;
	padding-left: 0 !important;
}

.cws-explore-hub,
.cws-explore-hub section,
.cws-explore-section__inner,
.cws-explore-places__grid,
.cws-explore-experiences__grid,
.cws-explore-planning__grid,
.cws-explore-place,
.cws-explore-experience,
.cws-explore-experience__body,
.cws-explore-planning__featured,
.cws-explore-planning__links {
	max-width: none !important;
	margin: 0 !important;
	padding: 0 !important;
}

/* Hever adds legacy group wrappers around Gutenberg content. Flatten only the
 * wrappers whose parent owns the layout so grid and flex children remain the
 * intended layout items. */
.cws-explore-hub > .wp-block-group__inner-container,
.cws-explore-hub__hero > .wp-block-group__inner-container,
.cws-explore-section__header > .wp-block-group__inner-container,
.cws-explore-places__grid > .wp-block-group__inner-container,
.cws-explore-experiences__grid > .wp-block-group__inner-container,
.cws-explore-planning__grid > .wp-block-group__inner-container,
.cws-explore-experience__body > .wp-block-group__inner-container,
.cws-explore-planning__links > .wp-block-group__inner-container {
	display: contents;
}

.cws-explore-hub__hero {
	position: relative;
	box-sizing: border-box;
	width: 100%;
	min-height: min(680px, calc(100dvh - var(--cws-fixed-header-height, 55px)));
	overflow: hidden;
	background: var(--cws-ink, #1e211e);
	color: #fff;
}

.cws-explore-hub__hero-media {
	position: absolute;
	inset: 0;
	width: 100%;
	max-width: none !important;
	height: 100%;
	margin: 0 !important;
}

.cws-explore-hub__hero-media::after {
	position: absolute;
	inset: 0;
	background: linear-gradient(90deg, rgba(11, 23, 19, .82) 0%, rgba(11, 23, 19, .42) 48%, rgba(11, 23, 19, .08) 78%);
	content: "";
}

.cws-explore-hub__hero-media img {
	display: block;
	width: 100%;
	height: 100%;
	object-fit: cover;
	object-position: center 48%;
}

.cws-explore-hub__hero-content {
	position: relative;
	z-index: 1;
	display: flex;
	box-sizing: border-box;
	width: min(calc(100% - 48px), 1180px);
	max-width: none !important;
	min-height: inherit;
	margin: 0 auto !important;
	padding: clamp(70px, 9vw, 120px) 0 !important;
	flex-direction: column;
	justify-content: flex-end;
}

.cws-explore-hub__hero-content > .wp-block-group__inner-container {
	width: 100%;
	max-width: none !important;
	margin: 0 !important;
}

.cws-explore-hub__title {
	max-width: 720px;
	margin: 0 0 22px;
	color: #fff;
	font-family: Georgia, "Times New Roman", serif;
	font-size: clamp(54px, 7vw, 88px);
	font-weight: 400;
	letter-spacing: -.04em;
	line-height: .98;
}

.cws-explore-hub__intro {
	max-width: 610px;
	margin: 0;
	color: rgba(255, 255, 255, .92);
	font-size: clamp(18px, 1.8vw, 22px);
	line-height: 1.5;
}

.cws-explore-places,
.cws-explore-experiences,
.cws-explore-planning {
	box-sizing: border-box;
	width: 100%;
	padding: clamp(88px, 9vw, 132px) 0 !important;
}

.cws-explore-places,
.cws-explore-planning {
	background: #fffdf8;
}

.cws-explore-experiences {
	background: var(--cws-ivory, #f4efe5);
}

.cws-explore-section__inner {
	box-sizing: border-box;
	width: min(calc(100% - 48px), 1180px);
	margin: 0 auto !important;
}

.cws-explore-section__header {
	display: flex;
	align-items: flex-end;
	justify-content: space-between;
	max-width: none !important;
	margin: 0 0 clamp(40px, 4.5vw, 58px) !important;
	padding: 0 !important;
	gap: 24px;
}

.cws-explore-section__title {
	margin: 0;
	color: var(--cws-ink, #1e211e);
	font-family: Georgia, "Times New Roman", serif;
	font-size: clamp(40px, 4.5vw, 58px);
	font-weight: 400;
	letter-spacing: -.035em;
	line-height: 1.05;
}

.cws-explore-section__action {
	margin: 0 0 5px;
	font-size: 14px;
	font-weight: 700;
}

.cws-explore-section__action a,
.cws-explore-planning__link a {
	color: var(--cws-green, #315f52);
	text-decoration: none;
}

.cws-explore-section__action a {
	border-bottom: 1px solid rgba(49, 95, 82, .36);
}

.cws-explore-places__grid {
	display: grid;
	grid-template-columns: repeat(12, minmax(0, 1fr));
	gap: clamp(24px, 2.8vw, 34px) 24px;
}

.cws-explore-place {
	position: relative;
	grid-column: span 4;
}

.cws-explore-place:nth-child(1) { grid-column: span 7; }
.cws-explore-place:nth-child(2) { grid-column: span 5; }
.cws-explore-place:nth-child(6),
.cws-explore-place:nth-child(7) { grid-column: span 6; }

.cws-explore-place__media,
.cws-explore-experience__media,
.cws-explore-planning__featured-media,
.cws-index-card__media {
	width: 100%;
	max-width: none !important;
	margin: 0 !important;
	overflow: hidden;
	background: #e6e0d5;
}

.cws-explore-place__media {
	aspect-ratio: 4 / 5;
}

.cws-explore-place:nth-child(1) .cws-explore-place__media,
.cws-explore-place:nth-child(2) .cws-explore-place__media,
.cws-explore-place:nth-child(6) .cws-explore-place__media,
.cws-explore-place:nth-child(7) .cws-explore-place__media {
	aspect-ratio: 16 / 10;
}

.cws-explore-place__media img,
.cws-explore-experience__media img,
.cws-explore-planning__featured-media img,
.cws-index-card__media img {
	display: block;
	width: 100%;
	height: 100%;
	object-fit: cover;
	transition: transform 360ms cubic-bezier(.2, .7, .2, 1);
}

.cws-explore-place__title {
	margin: 18px 0 7px;
	font-family: Georgia, "Times New Roman", serif;
	font-size: clamp(25px, 2.4vw, 34px);
	font-weight: 400;
	letter-spacing: -.02em;
	line-height: 1.12;
}

.cws-explore-place__title a,
.cws-explore-experience__title a,
.cws-explore-planning__featured-title a,
.cws-index-card__title a {
	color: inherit;
	text-decoration: none;
}

.cws-explore-place__title a::after,
.cws-explore-experience__title a::after,
.cws-explore-planning__featured-title a::after {
	position: absolute;
	inset: 0;
	content: "";
}

.cws-explore-place__copy {
	margin: 0;
	color: #49514d;
	font-size: 15px;
	line-height: 1.55;
}

.cws-explore-experiences__grid {
	display: grid;
	grid-template-columns: minmax(0, 1.55fr) minmax(280px, .85fr);
	grid-template-rows: repeat(2, minmax(260px, 1fr));
	margin-top: clamp(40px, 4.5vw, 58px) !important;
	gap: 24px;
}

.cws-explore-experience {
	position: relative;
	min-height: 0;
	overflow: hidden;
	background: var(--cws-ink, #1e211e);
	color: #fff;
}

.cws-explore-experience:first-child {
	grid-row: 1 / span 2;
}

.cws-explore-experience__media {
	position: absolute;
	inset: 0;
	height: 100%;
}

.cws-explore-experience__media::after {
	position: absolute;
	inset: 0;
	background: linear-gradient(0deg, rgba(11, 23, 19, .78), rgba(11, 23, 19, .04) 70%);
	content: "";
}

.cws-explore-experience__body {
	position: relative;
	z-index: 1;
	display: flex;
	box-sizing: border-box;
	height: 100%;
	padding: clamp(24px, 3vw, 42px) !important;
	flex-direction: column;
	justify-content: flex-end;
}

.cws-explore-experience__title {
	margin: 0 0 9px;
	font-family: Georgia, "Times New Roman", serif;
	font-size: clamp(27px, 3vw, 42px);
	font-weight: 400;
	letter-spacing: -.025em;
	line-height: 1.08;
}

.cws-explore-experience:not(:first-child) .cws-explore-experience__title {
	font-size: clamp(24px, 2.2vw, 31px);
}

.cws-explore-experience__copy {
	max-width: 46ch;
	margin: 0;
	color: rgba(255, 255, 255, .88);
	font-size: 15px;
	line-height: 1.5;
}

.cws-explore-planning__grid {
	display: grid;
	grid-template-columns: minmax(0, 1.15fr) minmax(280px, .85fr);
	margin-top: clamp(40px, 4.5vw, 58px) !important;
	gap: clamp(36px, 6vw, 82px);
}

.cws-explore-planning__featured {
	position: relative;
}

.cws-explore-planning__featured-media {
	aspect-ratio: 16 / 10;
}

.cws-explore-planning__featured-title {
	margin: 20px 0 8px;
	font-family: Georgia, "Times New Roman", serif;
	font-size: clamp(28px, 3vw, 40px);
	font-weight: 400;
	letter-spacing: -.025em;
	line-height: 1.1;
}

.cws-explore-planning__featured-copy {
	margin: 0;
	color: #49514d;
	font-size: 16px;
	line-height: 1.55;
}

.cws-explore-planning__links {
	display: flex;
	border-top: 1px solid rgba(30, 33, 30, .16);
	flex-direction: column;
}

.cws-explore-planning__link {
	margin: 0;
	border-bottom: 1px solid rgba(30, 33, 30, .16);
}

.cws-explore-planning__link a {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: clamp(18px, 2.4vw, 28px) 0;
	gap: 20px;
	color: var(--cws-ink, #1e211e);
	font-family: Georgia, "Times New Roman", serif;
	font-size: clamp(22px, 2.2vw, 30px);
	font-weight: 400;
	line-height: 1.2;
	transition: color 160ms ease, padding-left 160ms ease;
}

.cws-explore-planning__link a:hover,
.cws-explore-planning__link a:focus-visible {
	padding-left: 5px;
	color: var(--cws-green, #315f52);
}

.cws-index-page__intro,
.cws-index-page {
	box-sizing: border-box;
	width: min(calc(100% - 48px), 1180px);
	max-width: 1180px !important;
	margin-right: auto !important;
	margin-left: auto !important;
}

.cws-index-page__intro {
	max-width: 68ch;
	margin-bottom: 44px !important;
	font-size: 18px;
	line-height: 1.6;
}

.cws-index-page__grid {
	display: grid;
	grid-template-columns: repeat(3, minmax(0, 1fr));
	max-width: none !important;
	margin: 0 !important;
	padding: 0 !important;
	gap: 36px 24px;
}

.cws-index-card {
	max-width: none !important;
	margin: 0 !important;
	padding: 0 !important;
}

.cws-index-card__media {
	aspect-ratio: 4 / 3;
}

.cws-index-card__title {
	margin: 16px 0 7px;
	font-family: Georgia, "Times New Roman", serif;
	font-size: 27px;
	font-weight: 400;
	line-height: 1.15;
}

.cws-index-card__copy {
	margin: 0;
	color: #49514d;
	font-size: 15px;
	line-height: 1.55;
}

.home .cws-why-card {
	position: relative;
}

.home .cws-why-card__title a {
	color: inherit;
	text-decoration: none;
}

.home .cws-why-card__title a::after {
	position: absolute;
	inset: 0;
	content: "";
}

.home .cws-why-card__title a:hover,
.home .cws-why-card__title a:focus-visible {
	color: var(--cws-green, #315f52);
}

.home .cws-why-card__title a:focus-visible {
	outline: 2px solid var(--cws-green, #315f52);
	outline-offset: 4px;
}

.home .cws-why-card__media img {
	transition: transform 360ms cubic-bezier(.2, .7, .2, 1);
}

@media (hover: hover) and (pointer: fine) {
	.cws-explore-place:hover img,
	.cws-explore-place:focus-within img,
	.cws-explore-experience:hover img,
	.cws-explore-experience:focus-within img,
	.cws-explore-planning__featured:hover img,
	.cws-explore-planning__featured:focus-within img,
	.cws-index-card:hover img,
	.cws-index-card:focus-within img {
		transform: scale(1.025);
	}

	.home .cws-why-card:hover .cws-why-card__media img,
	.home .cws-why-card:focus-within .cws-why-card__media img {
		transform: scale(1.02);
	}
}

@media (max-width: 782px) {
	.cws-explore-hub__hero {
		min-height: min(610px, calc(100dvh - var(--cws-fixed-header-height, 49px)));
	}

	.cws-explore-hub__hero-media::after {
		background: linear-gradient(0deg, rgba(11, 23, 19, .86) 0%, rgba(11, 23, 19, .28) 72%, rgba(11, 23, 19, .08) 100%);
	}

	.cws-explore-hub__hero-content,
	.cws-explore-section__inner {
		width: min(calc(100% - 40px), 1180px);
	}

	.cws-explore-hub__hero-content {
		padding: 68px 0 52px !important;
	}

	.cws-explore-hub__title {
		margin-bottom: 18px;
		font-size: clamp(48px, 15vw, 66px);
		line-height: 1;
	}

	.cws-explore-hub__intro {
		font-size: 17px;
		line-height: 1.5;
	}

	.cws-explore-places,
	.cws-explore-experiences,
	.cws-explore-planning {
		padding: 72px 0 !important;
	}

	.cws-explore-section__header {
		align-items: flex-start;
		margin-bottom: 34px !important;
		flex-direction: column;
		gap: 14px;
	}

	.cws-explore-section__title {
		font-size: clamp(36px, 11vw, 46px);
	}

	.cws-explore-section__action {
		margin: 0;
	}

	.cws-explore-places__grid,
	.cws-explore-experiences__grid,
	.cws-explore-planning__grid,
	.cws-index-page__grid {
		display: grid;
		grid-template-columns: 1fr;
		grid-template-rows: auto;
		gap: 32px;
	}

	.cws-explore-place,
	.cws-explore-place:nth-child(1),
	.cws-explore-place:nth-child(2),
	.cws-explore-place:nth-child(6),
	.cws-explore-place:nth-child(7),
	.cws-explore-experience:first-child {
		grid-column: auto;
		grid-row: auto;
	}

	.cws-explore-place__media,
	.cws-explore-place:nth-child(1) .cws-explore-place__media,
	.cws-explore-place:nth-child(2) .cws-explore-place__media,
	.cws-explore-place:nth-child(6) .cws-explore-place__media,
	.cws-explore-place:nth-child(7) .cws-explore-place__media {
		aspect-ratio: 4 / 3;
	}

	.cws-explore-place__title {
		font-size: 28px;
	}

	.cws-explore-experiences__grid,
	.cws-explore-planning__grid {
		margin-top: 34px !important;
	}

	.cws-explore-experience {
		min-height: 390px;
	}

	.cws-explore-experience__title,
	.cws-explore-experience:not(:first-child) .cws-explore-experience__title {
		font-size: 29px;
	}

	.cws-explore-planning__links {
		margin-top: 4px !important;
	}

	.cws-index-page__intro,
	.cws-index-page {
		width: min(calc(100% - 40px), 1180px);
	}
}

@media (prefers-reduced-motion: reduce) {
	body .main-navigation .cws-submenu-toggle::before,
	body .main-navigation .cws-mega-menu > .sub-menu,
	body .main-navigation .cws-mega-featured__media img,
	.cws-explore-place__media img,
	.cws-explore-experience__media img,
	.cws-explore-planning__featured-media img,
	.cws-index-card__media img,
	.home .cws-why-card__media img,
	.cws-explore-planning__link a {
		transition: none !important;
	}
}
CSS;

$current_css = (string) wp_get_custom_css();
$css_marker  = '/* ===== Explore China Architecture — 2026-08-11 ===== */';

if ( false === strpos( $current_css, $css_marker ) ) {
	$updated_css = rtrim( $current_css ) . "\n\n" . trim( $css ) . "\n";
} else {
	$updated_css = preg_replace(
		'/\/\* ===== Explore China Architecture — 2026-08-11 ===== \*\/.*$/s',
		trim( $css ) . "\n",
		$current_css,
		1
	);
}

if ( null === $updated_css ) {
	throw new RuntimeException( 'Unable to prepare Explore China CSS.' );
}

if ( $updated_css !== $current_css ) {
	$result = wp_update_custom_css_post( $updated_css, array( 'stylesheet' => get_stylesheet() ) );
	if ( is_wp_error( $result ) ) {
		throw new RuntimeException( $result->get_error_message() );
	}
}

printf(
	"Explore architecture applied. explore_page_id=%d destinations=%d experiences=%d guides=%d menu_items=%d\n",
	$explore_page_id,
	count( $destination_ids ),
	count( $experience_ids ),
	count( $guide_ids ),
	count( $menu_by_key )
);
