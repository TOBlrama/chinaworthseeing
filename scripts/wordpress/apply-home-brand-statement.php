<?php
/**
 * Add the confirmed homepage Brand Statement and its Additional CSS.
 *
 * WordPress Studio executes this from a temporary copy inside the site root,
 * because the site's PHP sandbox cannot read this project directory directly.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$front_page_id = (int) get_option( 'page_on_front' );

if ( ! $front_page_id ) {
	throw new RuntimeException( 'No static front page is configured.' );
}

$section = <<<'HTML'
<!-- wp:group {"tagName":"section","className":"cws-brand-statement","layout":{"type":"constrained"}} -->
<section class="wp-block-group cws-brand-statement">
<!-- wp:group {"className":"cws-brand-statement__inner","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-brand-statement__inner">
<!-- wp:heading {"textAlign":"center","level":2,"className":"cws-brand-statement__title","anchor":"cws-brand-statement-title"} -->
<h2 class="wp-block-heading has-text-align-center cws-brand-statement__title" id="cws-brand-statement-title">China should feel easier to explore</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","className":"cws-brand-statement__copy"} -->
<p class="has-text-align-center cws-brand-statement__copy">China can be exciting, beautiful and deeply rewarding — but for many international travelers, planning a trip here can still feel more complicated than it should.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center","className":"cws-brand-statement__copy"} -->
<p class="has-text-align-center cws-brand-statement__copy">We created ChinaWorthSeeing to make that journey simpler.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center","className":"cws-brand-statement__copy"} -->
<p class="has-text-align-center cws-brand-statement__copy">To help you understand where to go, how to travel, and what is genuinely worth your time — while taking you beyond the usual checklists to experience a more authentic side of China.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center","className":"cws-brand-statement__copy"} -->
<p class="has-text-align-center cws-brand-statement__copy">Not just the places everyone tells you to see, but the landscapes, cultures, food and moments that make China truly worth seeing.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"className":"cws-brand-statement__actions"} -->
<div class="wp-block-buttons cws-brand-statement__actions"><!-- wp:button {"className":"cws-brand-statement__button"} -->
<div class="wp-block-button cws-brand-statement__button"><a class="wp-block-button__link wp-element-button" href="/contact/">GET IN TOUCH</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
HTML;

$brand_css = <<<'CSS'
/* ===== Brand Statement — 2026-08-11 ===== */
.home .cws-brand-statement {
	box-sizing: border-box;
	width: 100%;
	max-width: none !important;
	margin: 0 !important;
	padding: clamp(40px, 4vw, 56px) 0 clamp(72px, 7vw, 112px);
	background: #fff;
	color: var(--cws-ink, #1e211e);
}

.home .cws-brand-statement__inner {
	box-sizing: border-box;
	width: min(calc(100% - 48px), 900px);
	max-width: 900px !important;
	margin: 0 auto !important;
	padding: 0 !important;
	text-align: center;
}

.home .cws-brand-statement__title {
	margin: 0 0 30px;
	color: var(--cws-ink, #1e211e);
	font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
	font-size: clamp(16px, 1.4vw, 18px);
	font-weight: 700;
	letter-spacing: .12em;
	line-height: 1.35;
	text-transform: uppercase;
}

.home .cws-brand-statement__copy {
	margin: 0 0 18px;
	color: #49514d;
	font-size: clamp(16px, 1.25vw, 18px);
	letter-spacing: .01em;
	line-height: 1.62;
}

.home .cws-brand-statement__copy:last-child {
	margin-bottom: 0;
}

.home .cws-brand-statement__actions {
	display: flex;
	justify-content: center;
	max-width: none !important;
	margin: 28px 0 0 !important;
	padding: 0 !important;
}

.home .cws-brand-statement__button {
	margin: 0 !important;
}

.home .cws-brand-statement__button .wp-block-button__link {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-height: 48px;
	padding: 12px 30px;
	border: 1px solid var(--cws-green, #315f52);
	border-radius: 999px;
	background: var(--cws-green, #315f52);
	box-shadow: none;
	color: #fff;
	font-size: 15px;
	font-weight: 700;
	line-height: 1.4;
	text-decoration: none;
	transition: background-color 160ms ease, border-color 160ms ease;
}

.home .cws-brand-statement__button .wp-block-button__link:hover {
	border-color: #274c42;
	background: #274c42;
	color: #fff;
}

.home .cws-brand-statement__button .wp-block-button__link:focus-visible {
	outline: 2px solid var(--cws-green, #315f52);
	outline-offset: 4px;
}

@media (max-width: 782px) {
	.home .cws-brand-statement {
		padding: 32px 0 68px;
	}

	.home .cws-brand-statement__inner {
		width: min(calc(100% - 40px), 900px);
	}

	.home .cws-brand-statement__title {
		margin-bottom: 26px;
		font-size: 15px;
		letter-spacing: .1em;
	}

	.home .cws-brand-statement__copy {
		margin-bottom: 17px;
		font-size: 16px;
		line-height: 1.62;
	}

	.home .cws-brand-statement__actions {
		margin-top: 24px !important;
	}

	.home .cws-brand-statement__button,
	.home .cws-brand-statement__button .wp-block-button__link {
		width: 100%;
	}
}
CSS;

$content         = (string) get_post_field( 'post_content', $front_page_id );
$current_css     = (string) wp_get_custom_css();
$content_changed = false;
$css_changed     = false;

$normalized_content = str_replace(
	array(
		'<section class="wp-block-group cws-brand-statement" aria-labelledby="cws-brand-statement-title">',
		'<!-- wp:heading {"textAlign":"center","level":2,"className":"cws-brand-statement__title"} -->',
	),
	array(
		'<section class="wp-block-group cws-brand-statement">',
		'<!-- wp:heading {"textAlign":"center","level":2,"className":"cws-brand-statement__title","anchor":"cws-brand-statement-title"} -->',
	),
	$content
);

$normalized_content = str_replace(
	'>get in touch</a>',
	'>GET IN TOUCH</a>',
	$normalized_content
);

$button_markup = <<<'HTML'
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"className":"cws-brand-statement__actions"} -->
<div class="wp-block-buttons cws-brand-statement__actions"><!-- wp:button {"className":"cws-brand-statement__button"} -->
<div class="wp-block-button cws-brand-statement__button"><a class="wp-block-button__link wp-element-button" href="/contact/">GET IN TOUCH</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
HTML;

if (
	false !== strpos( $normalized_content, 'cws-brand-statement' )
	&& false === strpos( $normalized_content, 'cws-brand-statement__actions' )
) {
	$last_copy = <<<'HTML'
<!-- wp:paragraph {"align":"center","className":"cws-brand-statement__copy"} -->
<p class="has-text-align-center cws-brand-statement__copy">Not just the places everyone tells you to see, but the landscapes, cultures, food and moments that make China truly worth seeing.</p>
<!-- /wp:paragraph -->
HTML;

	$normalized_content = str_replace(
		$last_copy,
		$last_copy . "\n\n" . $button_markup,
		$normalized_content,
		$count
	);

	if ( 1 !== $count ) {
		throw new RuntimeException( 'Unable to place the Brand Statement button after the confirmed final paragraph.' );
	}
}

if ( false === strpos( $normalized_content, 'cws-brand-statement' ) ) {
	$normalized_content = rtrim( $normalized_content ) . "\n\n" . trim( $section );
}

if ( $normalized_content !== $content ) {
	$result = wp_update_post(
		array(
			'ID'           => $front_page_id,
			'post_content' => $normalized_content,
		),
		true
	);

	if ( is_wp_error( $result ) ) {
		throw new RuntimeException( $result->get_error_message() );
	}

	$content_changed = true;
}

if ( false === strpos( $current_css, 'Brand Statement — 2026-08-11' ) ) {
	$updated_css = rtrim( $current_css ) . "\n\n" . trim( $brand_css ) . "\n";
} else {
	$updated_css = preg_replace(
		'/\/\* ===== Brand Statement — 2026-08-11 ===== \*\/.*?(?=\/\* ===== [^\r\n]+ ===== \*\/|\z)/s',
		trim( $brand_css ) . "\n\n",
		$current_css,
		1
	);
}

if ( null === $updated_css ) {
	throw new RuntimeException( 'Unable to prepare the Brand Statement CSS update.' );
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
	"Brand Statement applied. content_changed=%s css_changed=%s\n",
	$content_changed ? 'yes' : 'no',
	$css_changed ? 'yes' : 'no'
);
