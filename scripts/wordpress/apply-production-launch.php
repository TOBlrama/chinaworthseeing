<?php
/**
 * Apply the minimum confirmed production-launch settings for ChinaWorthSeeing.
 *
 * Run only after apply-contact-enquiry.php has restored the Privacy Policy and
 * after the production-indexing MU plugin is present. The script fails closed:
 * it will not enable search indexing unless the core launch pages, Turnstile,
 * and production URL are all in the expected state.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 1 );
}

$expected_home = 'https://www.chinaworthseeing.com';
$actual_home   = untrailingslashit( home_url() );

if ( $expected_home !== $actual_home ) {
	throw new RuntimeException( 'Refusing to launch: unexpected home URL ' . $actual_home );
}

$find_page = static function ( string $slug ) {
	$page = get_page_by_path( $slug, OBJECT, 'page' );
	return $page instanceof WP_Post ? $page : null;
};

$required_pages = array( 'home', 'contact', 'explore-china', 'privacy-policy' );
foreach ( $required_pages as $required_slug ) {
	$required_page = $find_page( $required_slug );
	if ( ! $required_page || 'publish' !== $required_page->post_status ) {
		throw new RuntimeException( 'Refusing to launch: required page is missing or unpublished: ' . $required_slug );
	}
}

$contact_page = $find_page( 'contact' );
if ( false === strpos( (string) $contact_page->post_content, '[fluentform' ) ) {
	throw new RuntimeException( 'Refusing to launch: Contact page does not contain the Fluent Forms shortcode.' );
}

if ( ! (bool) get_option( '_fluentform_turnstile_keys_status', false ) ) {
	throw new RuntimeException( 'Refusing to launch: Cloudflare Turnstile keys are not confirmed active.' );
}

$privacy_page = $find_page( 'privacy-policy' );
if ( (int) get_option( 'wp_page_for_privacy_policy' ) !== (int) $privacy_page->ID ) {
	update_option( 'wp_page_for_privacy_policy', (int) $privacy_page->ID );
}

$about_marker  = 'cws-managed-production-launch-v1';
$about_content = <<<'HTML'
<!-- cws-managed-production-launch-v1 -->
<!-- wp:group {"tagName":"article","className":"cws-about-page","layout":{"type":"constrained"}} -->
<article class="wp-block-group cws-about-page">
<!-- wp:paragraph -->
<p>About ChinaWorthSeeing</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Making China easier to plan</h1>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>ChinaWorthSeeing helps international travellers understand where to go, how to move between places, what to prepare and how to shape a China trip that fits them.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>We provide detailed, personalised travel plans and practical guidance. We do not sell packaged tours or make bookings on your behalf. You stay in control and can book through links included in your itinerary or through any channel you prefer.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>ChinaWorthSeeing is operated by Yixiang Sheng, an individual based in China.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/contact/">Tell us about your trip</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</article>
<!-- /wp:group -->
HTML;

$about_page = $find_page( 'about' );
if ( ! $about_page ) {
	$about_id = wp_insert_post(
		wp_slash(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => 'About',
				'post_name'    => 'about',
				'post_content' => $about_content,
				'post_excerpt' => 'How ChinaWorthSeeing helps international travellers plan a trip through China.',
			)
		),
		true
	);
} elseif ( '' === trim( (string) $about_page->post_content ) || false !== strpos( (string) $about_page->post_content, $about_marker ) ) {
	$about_id = wp_update_post(
		wp_slash(
			array(
				'ID'           => $about_page->ID,
				'post_status'  => 'publish',
				'post_title'   => 'About',
				'post_name'    => 'about',
				'post_content' => $about_content,
				'post_excerpt' => 'How ChinaWorthSeeing helps international travellers plan a trip through China.',
			)
		),
		true
	);
} else {
	$about_id = $about_page->ID;
}

if ( is_wp_error( $about_id ) ) {
	throw new RuntimeException( 'Unable to create or update About: ' . $about_id->get_error_message() );
}

update_post_meta( (int) $about_id, '_cws_content_status', 'confirmed-about' );

$internal_notice = 'This guide is being prepared. The page is published now so the site structure and navigation can be reviewed.';
$public_notice   = 'Our detailed guide is still being prepared. If you are planning a trip now, tell us what you have in mind and we can help you think through the options.';
$dynamic_notice = 'This practical guide is being prepared. Rules and requirements can change, so verify current details with official sources before travel.';
$public_dynamic = 'Our detailed practical guide is still being prepared. Rules and requirements can change, so please verify current details with official sources. If you are planning a trip now, tell us what you have in mind and we can help you identify what to check.';

$placeholder_pages = get_posts(
	array(
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'meta_key'       => '_cws_content_status',
		'meta_value'     => 'placeholder',
		'orderby'        => 'ID',
		'order'          => 'ASC',
	)
);

$placeholder_updates = array();
foreach ( $placeholder_pages as $placeholder_page ) {
	$content = (string) $placeholder_page->post_content;
	$updated = str_replace(
		array( $internal_notice, $dynamic_notice ),
		array( $public_notice, $public_dynamic ),
		$content
	);

	if ( $updated !== $content ) {
		$result = wp_update_post(
			wp_slash(
				array(
					'ID'           => $placeholder_page->ID,
					'post_content' => $updated,
				)
			),
			true
		);

		if ( is_wp_error( $result ) ) {
			throw new RuntimeException( 'Unable to update placeholder page ' . $placeholder_page->ID . ': ' . $result->get_error_message() );
		}

		$placeholder_updates[] = (int) $placeholder_page->ID;
	}
}

// WordPress represents its default English (United States) locale as an empty
// WPLANG option. Saving the literal "en_US" is rejected by the settings
// sanitizer because it is not a downloadable translation pack.
update_option( 'WPLANG', '' );
update_option( 'blogdescription', 'Personalised China travel planning and practical guidance for international travellers.' );

$stored_wplang    = (string) get_option( 'WPLANG' );
$effective_locale = '' === $stored_wplang ? 'en_US' : $stored_wplang;

// This is deliberately last. Any failed prerequisite above leaves the site in
// its existing noindex state.
update_option( 'blog_public', '1' );

echo wp_json_encode(
	array(
		'home_url'             => home_url(),
		'site_language'        => $effective_locale,
		'site_visibility'      => (int) get_option( 'blog_public' ),
		'privacy_page_id'      => (int) $privacy_page->ID,
		'about_page_id'        => (int) $about_id,
		'placeholder_pages'    => count( $placeholder_pages ),
		'placeholder_updates'  => $placeholder_updates,
		'turnstile_configured' => (bool) get_option( '_fluentform_turnstile_keys_status', false ),
	),
	JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
) . PHP_EOL;
