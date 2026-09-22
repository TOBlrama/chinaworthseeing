<?php
/** Read-only validation for the English MVP production launch. */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 1 );
}

$checks = array();
$record = static function ( string $name, bool $passed, $actual = null ) use ( &$checks ) {
	$checks[] = array(
		'name'   => $name,
		'passed' => $passed,
		'actual' => $actual,
	);
};

$home = untrailingslashit( home_url() );
$record( 'Production home URL', 'https://www.chinaworthseeing.com' === $home, $home );
$record(
	'English site language',
	'en_US' === get_locale(),
	array(
		'locale'        => get_locale(),
		'wplang_option' => get_option( 'WPLANG' ),
	)
);
$record( 'Search indexing enabled', 1 === (int) get_option( 'blog_public' ), (int) get_option( 'blog_public' ) );
$record( 'Turnstile keys active', (bool) get_option( '_fluentform_turnstile_keys_status', false ), (bool) get_option( '_fluentform_turnstile_keys_status', false ) );
$record(
	'Production indexing guardrail loaded',
	defined( 'CWS_PRODUCTION_INDEXING_VERSION' ),
	defined( 'CWS_PRODUCTION_INDEXING_VERSION' ) ? CWS_PRODUCTION_INDEXING_VERSION : null
);

foreach ( array( 'home', 'about', 'contact', 'explore-china', 'privacy-policy' ) as $slug ) {
	$page = get_page_by_path( $slug, OBJECT, 'page' );
	$record(
		'Published page: ' . $slug,
		$page instanceof WP_Post && 'publish' === $page->post_status,
		$page instanceof WP_Post ? array( 'id' => $page->ID, 'status' => $page->post_status ) : null
	);
}

$privacy_page = get_page_by_path( 'privacy-policy', OBJECT, 'page' );
$record(
	'Privacy page selected in WordPress',
	$privacy_page instanceof WP_Post && (int) get_option( 'wp_page_for_privacy_policy' ) === (int) $privacy_page->ID,
	(int) get_option( 'wp_page_for_privacy_policy' )
);

$placeholder_pages = get_posts(
	array(
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'meta_key'       => '_cws_content_status',
		'meta_value'     => 'placeholder',
	)
);
$internal_notice_count = 0;
foreach ( $placeholder_pages as $placeholder_page ) {
	if ( false !== strpos( (string) $placeholder_page->post_content, 'site structure and navigation can be reviewed' ) ) {
		++$internal_notice_count;
	}
}
$record( 'No internal review copy on placeholder pages', 0 === $internal_notice_count, $internal_notice_count );
$record( 'Placeholder pages identified for noindex guardrail', count( $placeholder_pages ) > 0, count( $placeholder_pages ) );

$failed = array_values(
	array_filter(
		$checks,
		static fn( array $check ) => ! $check['passed']
	)
);

echo wp_json_encode(
	array(
		'status' => empty( $failed ) ? 'PASS' : 'FAIL',
		'checks' => $checks,
	),
	JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
) . PHP_EOL;

if ( ! empty( $failed ) ) {
	exit( 1 );
}
