<?php
/**
 * Read-only validation for the homepage Why Travel section.
 *
 * Run with:
 * studio wp eval-file validate-home-why-travel.php --path D:\ChinaWorthSeeing.com
 */

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

$brand_index = null;
$why_index   = null;
$why_count   = 0;

foreach ( $blocks as $index => $block ) {
	$class_name = isset( $block['attrs']['className'] ) ? (string) $block['attrs']['className'] : '';

	if ( false !== strpos( $class_name, 'cws-brand-statement' ) ) {
		$brand_index = $index;
	}

	if ( preg_match( '/(?:^|\s)cws-why-travel(?:\s|$)/', $class_name ) ) {
		$why_index = $index;
		++$why_count;
	}
}

$result = array(
	'page_id'                => $page_id,
	'why_root_count'         => $why_count,
	'heading_count'          => substr_count( $content, 'Why travel with ChinaWorthSeeing' ),
	'immediately_after_brand'=> null !== $brand_index && null !== $why_index && $why_index === $brand_index + 1,
	'top_level_blocks'        => count( $blocks ),
	'theme'                   => wp_get_theme()->get_stylesheet(),
	'smart_slider_active'     => is_plugin_active( 'smart-slider-3/smart-slider-3.php' ),
);

echo wp_json_encode( $result, JSON_UNESCAPED_SLASHES ) . PHP_EOL;
