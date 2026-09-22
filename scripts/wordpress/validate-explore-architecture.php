<?php
/** Read-only validation for Explore China pages, menu and homepage links. */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 1 );
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';

$paths = array(
	'explore-china',
	'destinations',
	'destinations/beijing',
	'destinations/shanghai',
	'destinations/xian',
	'destinations/guilin',
	'destinations/hangzhou',
	'destinations/suzhou',
	'destinations/guangzhou',
	'experiences',
	'experiences/natural-wonders',
	'experiences/history-heritage',
	'experiences/flavours-of-china',
	'plan-your-trip',
	'plan-your-trip/first-time-in-china',
	'plan-your-trip/when-to-visit',
	'plan-your-trip/getting-around',
	'plan-your-trip/payments-apps',
	'plan-your-trip/entry-visa',
);

$pages = array();
foreach ( $paths as $path ) {
	$page = get_page_by_path( $path, OBJECT, 'page' );
	$pages[ $path ] = $page
		? array(
			'id'             => (int) $page->ID,
			'status'         => $page->post_status,
			'url'            => get_permalink( $page ),
			'content_status' => get_post_meta( $page->ID, '_cws_content_status', true ),
		)
		: null;
}

$menu       = wp_get_nav_menu_object( 'primary-navigation' );
$menu_items = $menu ? wp_get_nav_menu_items( $menu->term_id ) : array();
$menu_items = is_array( $menu_items ) ? $menu_items : array();
$rendered_menu = $menu
	? wp_nav_menu(
		array(
			'menu'        => $menu,
			'container'   => false,
			'echo'        => false,
			'fallback_cb' => false,
		)
	)
	: '';

$top_level  = array();
$managed    = array();
$parent_map = array();

foreach ( $menu_items as $item ) {
	$key = (string) get_post_meta( $item->ID, '_cws_nav_key', true );
	if ( $key ) {
		$managed[ $key ] = array(
			'id'        => (int) $item->ID,
			'title'     => $item->title,
			'parent_id' => (int) $item->menu_item_parent,
			'url'       => $item->url,
		);
	}

	if ( 0 === (int) $item->menu_item_parent ) {
		$top_level[] = $item->title;
	}

	$parent_map[ $item->title ] = (int) $item->menu_item_parent;
}

$front_page_id = (int) get_option( 'page_on_front' );
$home_content  = (string) get_post_field( 'post_content', $front_page_id );
$home_blocks   = parse_blocks( $home_content );
$home_hashes   = array();
$menu_group_label_count = preg_match_all(
	'/<span class="cws-nav-label">(?:Places to Go|Experiences|Plan Your Trip)<\/span>/',
	$rendered_menu
);
$menu_group_link_count = preg_match_all(
	'/<a[^>]*>(?:Places to Go|Experiences|Plan Your Trip)<\/a>/',
	$rendered_menu
);

foreach ( $home_blocks as $block ) {
	$class_name = isset( $block['attrs']['className'] ) ? (string) $block['attrs']['className'] : '';
	foreach ( array( 'cws-hero', 'cws-brand-statement' ) as $protected_class ) {
		if ( preg_match( '/(?:^|\s)' . preg_quote( $protected_class, '/' ) . '(?:\s|$)/', $class_name ) ) {
			$home_hashes[ $protected_class ] = hash( 'sha256', serialize_block( $block ) );
		}
	}
}

$result = array(
	'pages'                         => $pages,
	'all_required_pages_exist'      => ! in_array( null, $pages, true ),
	'top_level_navigation'          => $top_level,
	'top_level_matches'             => array( 'Home', 'Explore China', 'About', 'Contact', 'Start Planning' ) === $top_level,
	'managed_menu_item_count'       => count( $managed ),
	'managed_menu_items'            => $managed,
	'home_destination_link_count'   => substr_count( $home_content, '/destinations/' ),
	'home_placeholder_link_count'   => substr_count( $home_content, 'data-cws-placeholder="true"' ),
	'home_experience_link_count'    => substr_count( $home_content, '/experiences/' ),
	'home_legacy_inspirations_count' => substr_count( $home_content, 'Explore Inspirations' ),
	'home_explore_cta_count'        => substr_count( $home_content, '>Explore China</a>' ),
	'menu_explore_label_count'      => substr_count( $rendered_menu, '<span class="cws-nav-label">Explore China</span>' ),
	'menu_explore_link_count'       => preg_match_all( '/href="[^"]*\/explore-china\/"[^>]*>Explore China<\/a>/', $rendered_menu ),
	'menu_group_label_count'        => $menu_group_label_count,
	'menu_group_link_count'         => $menu_group_link_count,
	'home_protected_section_hashes' => $home_hashes,
	'explore_section_count'         => isset( $pages['explore-china']['id'] ) ? substr_count( (string) get_post_field( 'post_content', $pages['explore-china']['id'] ), '<section class="wp-block-group cws-explore-' ) : 0,
	'css_marker_count'              => substr_count( wp_get_custom_css(), 'Explore China Architecture — 2026-08-11' ),
	'mu_plugin_loaded'              => isset( get_mu_plugins()['cws-explore-navigation.php'] ),
	'theme'                         => wp_get_theme()->get_stylesheet(),
	'smart_slider_active'           => is_plugin_active( 'smart-slider-3/smart-slider-3.php' ),
	'permalink_structure'           => get_option( 'permalink_structure' ),
);

echo wp_json_encode( $result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . PHP_EOL;
