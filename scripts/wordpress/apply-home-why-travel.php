<?php
/**
 * Add the confirmed homepage Why Travel section and its Additional CSS.
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
<!-- wp:group {"tagName":"section","className":"cws-why-travel","layout":{"type":"constrained"}} -->
<section class="wp-block-group cws-why-travel">
<!-- wp:group {"className":"cws-why-travel__inner","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-why-travel__inner">
<!-- wp:paragraph {"align":"center","className":"cws-why-travel__eyebrow"} -->
<p class="has-text-align-center cws-why-travel__eyebrow">A LAND OF STORIES, SCENERY &amp; FLAVOUR</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":2,"className":"cws-why-travel__title","anchor":"why-travel-with-chinaworthseeing"} -->
<h2 class="wp-block-heading has-text-align-center cws-why-travel__title" id="why-travel-with-chinaworthseeing">Why travel with ChinaWorthSeeing</h2>
<!-- /wp:heading -->

<!-- wp:group {"className":"cws-why-travel__cards","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-why-travel__cards">
<!-- wp:group {"className":"cws-why-card","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-why-card">
<!-- wp:image {"id":81,"sizeSlug":"full","linkDestination":"none","className":"cws-why-card__media"} -->
<figure class="wp-block-image size-full cws-why-card__media"><img src="/wp-content/uploads/2026/08/cws-natural-wonders.webp" alt="Zhangjiajie sandstone pillars glowing in warm evening light" class="wp-image-81" width="1800" height="1200" loading="lazy" decoding="async"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"cws-why-card__body","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-why-card__body">
<!-- wp:heading {"textAlign":"center","level":3,"className":"cws-why-card__title"} -->
<h3 class="wp-block-heading has-text-align-center cws-why-card__title"><a href="/experiences/natural-wonders/">Natural Wonders</a></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","className":"cws-why-card__copy"} -->
<p class="has-text-align-center cws-why-card__copy">Discover the landscapes that make China unlike anywhere else.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"cws-why-card","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-why-card">
<!-- wp:image {"id":82,"sizeSlug":"full","linkDestination":"none","className":"cws-why-card__media"} -->
<figure class="wp-block-image size-full cws-why-card__media"><img src="/wp-content/uploads/2026/08/cws-history-heritage.webp" alt="Historic palace architecture inside the Forbidden City in Beijing" class="wp-image-82" width="1800" height="1200" loading="lazy" decoding="async"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"cws-why-card__body","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-why-card__body">
<!-- wp:heading {"textAlign":"center","level":3,"className":"cws-why-card__title"} -->
<h3 class="wp-block-heading has-text-align-center cws-why-card__title"><a href="/experiences/history-heritage/">History &amp; Heritage</a></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","className":"cws-why-card__copy"} -->
<p class="has-text-align-center cws-why-card__copy">Step into thousands of years of history, from imperial landmarks to ancient streets and living traditions.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"cws-why-card","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-why-card">
<!-- wp:image {"id":83,"sizeSlug":"full","linkDestination":"none","className":"cws-why-card__media"} -->
<figure class="wp-block-image size-full cws-why-card__media"><img src="/wp-content/uploads/2026/08/cws-flavours-of-china.webp" alt="Chinese roast duck served with pancakes, cucumber and sauces" class="wp-image-83" width="1800" height="1200" loading="lazy" decoding="async"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"cws-why-card__body","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-why-card__body">
<!-- wp:heading {"textAlign":"center","level":3,"className":"cws-why-card__title"} -->
<h3 class="wp-block-heading has-text-align-center cws-why-card__title"><a href="/experiences/flavours-of-china/">Flavours of China</a></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","className":"cws-why-card__copy"} -->
<p class="has-text-align-center cws-why-card__copy">Taste China beyond the tourist checklist, from regional classics to the places locals actually love.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
HTML;

$section_css = <<<'CSS'
/* ===== Why Travel — 2026-08-11 ===== */
.home .cws-why-travel {
	--cws-why-heading-gap: 18px;
	box-sizing: border-box;
	width: 100%;
	max-width: none !important;
	margin: 0 !important;
	padding: clamp(80px, 8vw, 124px) 0 clamp(88px, 9vw, 136px);
	background: var(--cws-ivory, #f4efe5);
	color: var(--cws-ink, #1e211e);
}

.home .cws-why-travel__inner {
	box-sizing: border-box;
	width: min(calc(100% - 48px), 1180px);
	max-width: 1180px !important;
	margin: 0 auto !important;
	padding: 0 !important;
}

.home .cws-why-travel__eyebrow {
	margin: 0 0 var(--cws-why-heading-gap);
	color: var(--cws-green, #315f52);
	font-size: 13px;
	font-weight: 700;
	letter-spacing: .18em;
	line-height: 1.45;
}

.home .cws-why-travel__title {
	margin: 0;
	color: var(--cws-ink, #1e211e);
	font-family: Georgia, "Times New Roman", serif;
	font-size: clamp(38px, 4vw, 54px);
	font-weight: 400;
	letter-spacing: -.035em;
	line-height: 1.08;
}

.home .cws-why-travel__cards {
	max-width: none !important;
	margin: var(--cws-why-heading-gap) 0 0 !important;
	padding: 0 !important;
}

.home .cws-why-travel__cards > .wp-block-group__inner-container {
	display: grid;
	grid-template-columns: repeat(3, minmax(0, 1fr));
	align-items: stretch;
	gap: clamp(18px, 2vw, 28px);
	max-width: none !important;
}

.home .cws-why-card {
	position: relative;
	display: flex;
	grid-column: auto !important;
	grid-row: auto !important;
	box-sizing: border-box;
	width: 100%;
	height: 100%;
	min-width: 0;
	max-width: none !important;
	margin: 0 !important;
	padding: 0 !important;
	background: #fffdf8;
	flex-direction: column;
}

.home .cws-why-card > .wp-block-group__inner-container {
	display: flex;
	box-sizing: border-box;
	width: 100%;
	height: 100%;
	flex-direction: column;
}

.home .cws-why-card__media {
	position: relative;
	width: 100%;
	max-width: none !important;
	height: clamp(210px, 25vh, 280px);
	margin: 0 !important;
	overflow: hidden;
}

.home .cws-why-card__media img {
	display: block;
	width: 100%;
	height: 100%;
	object-fit: cover;
	object-position: center;
	transition: transform 360ms cubic-bezier(.2, .7, .2, 1);
}

.home .cws-why-card__body {
	display: flex;
	box-sizing: border-box;
	width: 100%;
	max-width: none !important;
	margin: 0 !important;
	padding: clamp(24px, 2.3vw, 32px);
	flex: 1;
	flex-direction: column;
}

.home .cws-why-card__title {
	margin: 0 0 15px;
	color: var(--cws-ink, #1e211e);
	font-family: Georgia, "Times New Roman", serif;
	font-size: clamp(25px, 2.1vw, 31px);
	font-weight: 400;
	line-height: 1.18;
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

.home .cws-why-card__copy {
	margin: 0;
	color: #49514d;
	font-size: 16px;
	line-height: 1.62;
}

@media (hover: hover) and (pointer: fine) {
	.home .cws-why-card:hover .cws-why-card__media img,
	.home .cws-why-card:focus-within .cws-why-card__media img {
		transform: scale(1.02);
	}
}

@media (max-width: 782px) {
	.home .cws-why-travel {
		--cws-why-heading-gap: 16px;
		padding: 72px 0 76px;
	}

	.home .cws-why-travel__inner {
		width: min(calc(100% - 40px), 1180px);
	}

	.home .cws-why-travel__eyebrow {
		font-size: 12px;
		letter-spacing: .14em;
	}

	.home .cws-why-travel__title {
		font-size: clamp(34px, 10vw, 44px);
	}

	.home .cws-why-travel__cards > .wp-block-group__inner-container {
		grid-template-columns: 1fr;
		gap: 18px;
	}

	.home .cws-why-card__media {
		height: auto;
		aspect-ratio: 3 / 2;
	}

	.home .cws-why-card__body {
		padding: 26px 24px 28px;
	}

	.home .cws-why-card__title {
		font-size: 27px;
	}
}

@media (prefers-reduced-motion: reduce) {
	.home .cws-why-card__media img {
		transition: none;
	}
}
CSS;

$content         = (string) get_post_field( 'post_content', $front_page_id );
$current_css     = (string) wp_get_custom_css();
$content_changed = false;
$css_changed     = false;

if ( false === strpos( $content, 'cws-brand-statement' ) ) {
	throw new RuntimeException( 'The confirmed Brand Statement section is missing.' );
}

if ( false === strpos( $content, 'cws-why-travel' ) ) {
	$result = wp_update_post(
		array(
			'ID'           => $front_page_id,
			'post_content' => rtrim( $content ) . "\n\n" . trim( $section ),
		),
		true
	);

	if ( is_wp_error( $result ) ) {
		throw new RuntimeException( $result->get_error_message() );
	}

	$content_changed = true;
}

if ( false === strpos( $current_css, 'Why Travel — 2026-08-11' ) ) {
	$updated_css = rtrim( $current_css ) . "\n\n" . trim( $section_css ) . "\n";
} else {
	$updated_css = preg_replace(
		'/\/\* ===== Why Travel — 2026-08-11 ===== \*\/.*$/s',
		trim( $section_css ) . "\n",
		$current_css,
		1
	);
}

if ( null === $updated_css ) {
	throw new RuntimeException( 'Unable to prepare the Why Travel CSS update.' );
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
	"Why Travel applied. content_changed=%s css_changed=%s\n",
	$content_changed ? 'yes' : 'no',
	$css_changed ? 'yes' : 'no'
);
