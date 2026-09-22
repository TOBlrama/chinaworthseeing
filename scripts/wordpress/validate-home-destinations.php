<?php
/** Read-only validation for the homepage destination carousel. */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 1 );
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';

$page_id = (int) get_option( 'page_on_front' );
$content = (string) get_post_field( 'post_content', $page_id );
$blocks  = array_values(
	array_filter(
		parse_blocks( $content ),
		static function ( $block ) {
			return ! empty( $block['blockName'] ) || '' !== trim( (string) $block['innerHTML'] );
		}
	)
);

$classes = array();
$hashes  = array();

foreach ( $blocks as $block ) {
	$class_name = isset( $block['attrs']['className'] ) ? (string) $block['attrs']['className'] : '';
	$classes[]  = $class_name;

	foreach ( array( 'cws-hero', 'cws-brand-statement', 'cws-why-travel' ) as $existing_class ) {
		if ( preg_match( '/(?:^|\s)' . preg_quote( $existing_class, '/' ) . '(?:\s|$)/', $class_name ) ) {
			$hashes[ $existing_class ] = hash( 'sha256', serialize_block( $block ) );
		}
	}
}

$why_index         = array_search( 'cws-why-travel', $classes, true );
$destination_index = array_search( 'cws-destinations', $classes, true );
$mu_plugins        = get_mu_plugins();

$result = array(
	'page_id'                  => $page_id,
	'top_level_classes'        => $classes,
	'existing_section_hashes'  => $hashes,
	'destination_root_count'   => substr_count( $content, '"className":"cws-destinations"' ),
	'card_count'               => substr_count( $content, '"className":"cws-destination-card cws-destination-card--' ),
	'placeholder_link_count'   => substr_count( $content, 'data-cws-placeholder="true"' ),
	'carousel_button_count'    => substr_count( $content, 'data-cws-carousel-' ),
	'immediately_after_why'    => false !== $why_index && false !== $destination_index && $destination_index === $why_index + 1,
	'css_marker_count'         => substr_count( wp_get_custom_css(), 'Destination Carousel — 2026-08-11' ),
	'mu_plugin_loaded'         => isset( $mu_plugins['cws-home-destinations.php'] ),
	'theme'                    => wp_get_theme()->get_stylesheet(),
	'smart_slider_active'      => is_plugin_active( 'smart-slider-3/smart-slider-3.php' ),
);

echo wp_json_encode( $result, JSON_UNESCAPED_SLASHES ) . PHP_EOL;
