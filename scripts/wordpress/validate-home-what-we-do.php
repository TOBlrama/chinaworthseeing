<?php
/**
 * Validate the simplified homepage What We Do section and its placement.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$front_page_id = (int) get_option( 'page_on_front' );
$content       = (string) get_post_field( 'post_content', $front_page_id );
$custom_css    = (string) wp_get_custom_css();
$blocks        = parse_blocks( $content );
$top_classes   = array();

foreach ( $blocks as $block ) {
	$class_name = isset( $block['attrs']['className'] ) ? (string) $block['attrs']['className'] : '';

	if ( '' !== $class_name ) {
		$top_classes[] = $class_name;
	}
}

$hero_index  = array_search( 'cws-hero', $top_classes, true );
$what_index  = array_search( 'cws-what-we-do', $top_classes, true );
$brand_index = array_search( 'cws-brand-statement', $top_classes, true );

$checks = array(
	'page_id'                    => $front_page_id,
	'section_count'              => substr_count( $content, 'className":"cws-what-we-do"' ),
	'title_count'                => substr_count( $content, 'className":"cws-what-we-do__title"' ),
	'subtitle_count'             => substr_count( $content, 'className":"cws-what-we-do__subtitle"' ),
	'value_count'                => substr_count( $content, 'className":"cws-what-we-do__value"' ),
	'has_confirmed_title'        => false !== strpos( $content, 'We create detailed, personalized travel plans — without selling you a packaged tour.' ),
	'has_confirmed_subtitle'     => false !== strpos( $content, 'Most bookings can be made through links included in your itinerary, or you\'re always free to book everything yourself.' ),
	'legacy_content_absent'      => false === strpos( $content, 'Built Around You' ) && false === strpos( $content, 'We Plan. You Stay in Control.' ),
	'immediately_after_hero'     => false !== $hero_index && false !== $what_index && $what_index === $hero_index + 1,
	'immediately_before_brand'   => false !== $what_index && false !== $brand_index && $brand_index === $what_index + 1,
	'current_css_marker_count'   => substr_count( $custom_css, 'What We Do — 2026-08-12' ),
	'previous_css_marker_count'  => substr_count( $custom_css, 'What We Do — 2026-08-11' ),
	'theme'                      => get_stylesheet(),
	'smart_slider_active'        => is_plugin_active( 'smart-slider-3/smart-slider-3.php' ),
);

foreach ( $checks as $key => $value ) {
	if ( is_bool( $value ) ) {
		$value = $value ? 'true' : 'false';
	}

	printf( "%s=%s\n", $key, $value );
}
