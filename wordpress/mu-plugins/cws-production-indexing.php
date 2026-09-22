<?php
/**
 * Plugin Name: ChinaWorthSeeing Production Indexing Guardrails
 * Description: Keeps managed placeholder and structure-only pages out of search results while allowing the completed MVP pages to be indexed.
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CWS_PRODUCTION_INDEXING_VERSION', '1.0.0' );

/**
 * Placeholder pages remain useful for navigation review and early visitors, but
 * they must not be presented to search engines as finished travel content.
 */
add_filter(
	'wp_robots',
	static function ( array $robots ) {
		if ( ! is_singular( 'page' ) ) {
			return $robots;
		}

		$page_id        = get_queried_object_id();
		$content_status = (string) get_post_meta( $page_id, '_cws_content_status', true );
		$page_slug      = (string) get_post_field( 'post_name', $page_id );

		$should_noindex = 'placeholder' === $content_status
			|| ( 'structure' === $content_status && 'explore-china' !== $page_slug );

		if ( $should_noindex ) {
			unset( $robots['index'] );
			$robots['noindex'] = true;
		}

		return $robots;
	},
	20
);
