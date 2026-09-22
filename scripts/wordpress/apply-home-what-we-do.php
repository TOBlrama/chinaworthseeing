<?php
/**
 * Replace the homepage What We Do section with the confirmed minimal version.
 *
 * The content remains editable native Gutenberg blocks. The script replaces
 * only the existing What We Do block and its dedicated Additional CSS block.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$front_page_id = (int) get_option( 'page_on_front' );

if ( ! $front_page_id ) {
	throw new RuntimeException( 'No static front page is configured.' );
}

$section = <<<'HTML'
<!-- wp:group {"tagName":"section","className":"cws-what-we-do","layout":{"type":"constrained"}} -->
<section class="wp-block-group cws-what-we-do">
<!-- wp:group {"className":"cws-what-we-do__inner","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-what-we-do__inner">
<!-- wp:heading {"textAlign":"center","level":2,"className":"cws-what-we-do__title","anchor":"what-we-do"} -->
<h2 class="wp-block-heading has-text-align-center cws-what-we-do__title" id="what-we-do">We create detailed, personalized travel plans — without selling you a packaged tour.</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","className":"cws-what-we-do__subtitle"} -->
<p class="has-text-align-center cws-what-we-do__subtitle">Most bookings can be made through links included in your itinerary, or you're always free to book everything yourself.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
HTML;

$section_css = <<<'CSS'
/* ===== What We Do — 2026-08-12 ===== */
.home .cws-what-we-do {
	box-sizing: border-box;
	width: 100%;
	max-width: none !important;
	margin: 0 !important;
	padding: clamp(96px, 9vw, 136px) 0 clamp(92px, 8.5vw, 128px);
	background: var(--cws-ivory, #f4efe5);
	color: var(--cws-ink, #1e211e);
}

.home .cws-what-we-do__inner {
	box-sizing: border-box;
	width: min(calc(100% - 48px), 1180px);
	max-width: 1180px !important;
	margin: 0 auto !important;
	padding: 0 !important;
	text-align: center;
}

.home .cws-what-we-do__title {
	max-width: none !important;
	margin: 0;
	color: var(--cws-ink, #1e211e);
	font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
	font-size: clamp(44px, 4.4vw, 60px);
	font-weight: 700;
	letter-spacing: -.035em;
	line-height: 1.1;
	text-wrap: balance;
}

.home .cws-what-we-do__subtitle {
	max-width: 780px;
	margin: clamp(28px, 3vw, 40px) auto 0;
	color: #49514d;
	font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
	font-size: clamp(17px, 1.4vw, 19px);
	letter-spacing: .005em;
	line-height: 1.65;
	text-wrap: balance;
}

@media (max-width: 782px) {
	.home .cws-what-we-do {
		padding: 76px 0 80px;
	}

	.home .cws-what-we-do__inner {
		width: min(calc(100% - 40px), 1180px);
	}

	.home .cws-what-we-do__title {
		font-size: clamp(32px, 8.5vw, 36px);
		letter-spacing: -.03em;
		line-height: 1.1;
	}

	.home .cws-what-we-do__subtitle {
		margin-top: 26px;
		font-size: 16px;
		line-height: 1.62;
	}
}
CSS;

$content         = (string) get_post_field( 'post_content', $front_page_id );
$current_css     = (string) wp_get_custom_css();
$content_changed = false;
$css_changed     = false;
$section_marker  = '<!-- wp:group {"tagName":"section","className":"cws-what-we-do"';
$brand_marker    = '<!-- wp:group {"tagName":"section","className":"cws-brand-statement"';
$section_start   = strpos( $content, $section_marker );
$brand_start     = strpos( $content, $brand_marker );

if ( false === strpos( $content, 'className":"cws-hero"' ) ) {
	throw new RuntimeException( 'The confirmed homepage Hero is missing.' );
}

if ( false === $brand_start ) {
	throw new RuntimeException( 'The confirmed Brand Statement section is missing.' );
}

if ( false === $section_start ) {
	$updated_content = substr( $content, 0, $brand_start ) . trim( $section ) . "\n\n" . substr( $content, $brand_start );
} else {
	if ( $section_start > $brand_start ) {
		throw new RuntimeException( 'What We Do is not before Brand Statement.' );
	}

	$updated_content = substr( $content, 0, $section_start ) . trim( $section ) . "\n\n" . substr( $content, $brand_start );
}

if ( $updated_content !== $content ) {
	$result = wp_update_post(
		array(
			'ID'           => $front_page_id,
			'post_content' => $updated_content,
		),
		true
	);

	if ( is_wp_error( $result ) ) {
		throw new RuntimeException( $result->get_error_message() );
	}

	$content_changed = true;
}

$what_we_do_pattern = '/\/\* ===== What We Do — 2026-08-(?:11|12) ===== \*\/.*?(?=\/\* ===== [^\r\n]+ ===== \*\/|\z)/s';

if ( preg_match( $what_we_do_pattern, $current_css ) ) {
	$updated_css = preg_replace(
		$what_we_do_pattern,
		trim( $section_css ) . "\n\n",
		$current_css,
		1
	);
} else {
	$brand_css_marker = '/* ===== Brand Statement — 2026-08-11 ===== */';

	if ( false !== strpos( $current_css, $brand_css_marker ) ) {
		$updated_css = str_replace(
			$brand_css_marker,
			trim( $section_css ) . "\n\n" . $brand_css_marker,
			$current_css
		);
	} else {
		$updated_css = rtrim( $current_css ) . "\n\n" . trim( $section_css ) . "\n";
	}
}

if ( null === $updated_css ) {
	throw new RuntimeException( 'Unable to prepare the What We Do CSS update.' );
}

if ( $updated_css !== $current_css ) {
	$result = wp_update_custom_css_post(
		$updated_css,
		array( 'stylesheet' => get_stylesheet() )
	);

	if ( is_wp_error( $result ) ) {
		throw new RuntimeException( $result->get_error_message() );
	}

	$css_changed = true;
}

printf(
	"What We Do simplified. content_changed=%s css_changed=%s\n",
	$content_changed ? 'yes' : 'no',
	$css_changed ? 'yes' : 'no'
);
