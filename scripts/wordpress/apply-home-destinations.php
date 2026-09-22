<?php
/**
 * Add the confirmed homepage destination carousel and its Additional CSS.
 *
 * The content remains native Gutenberg blocks. Carousel controls are provided
 * by the small cws-home-destinations MU plugin without modifying the theme.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$front_page_id = (int) get_option( 'page_on_front' );

if ( ! $front_page_id ) {
	throw new RuntimeException( 'No static front page is configured.' );
}

$cities = array(
	array(
		'name'     => 'BEIJING',
		'label'    => 'Beijing',
		'slug'     => 'beijing',
		'filename' => 'cws-city-beijing.webp',
		'tagline'  => 'Where imperial China still feels alive.',
		'alt'      => 'Temple of Heaven beneath a wide blue sky in Beijing',
		'url'      => '/destinations/beijing/',
	),
	array(
		'name'     => 'SHANGHAI',
		'label'    => 'Shanghai',
		'slug'     => 'shanghai',
		'filename' => 'cws-city-shanghai.webp',
		'tagline'  => 'China at its most modern, stylish and energetic.',
		'alt'      => 'Shanghai skyline and the Oriental Pearl Tower at night',
		'url'      => '/destinations/shanghai/',
	),
	array(
		'name'     => "XI'AN",
		'label'    => "Xi'an",
		'slug'     => 'xian',
		'filename' => 'cws-city-xian.webp',
		'tagline'  => 'The gateway to China’s ancient past.',
		'alt'      => "Terracotta Warriors at the archaeological site near Xi'an",
		'url'      => '/destinations/xian/',
	),
	array(
		'name'     => 'GUILIN',
		'label'    => 'Guilin',
		'slug'     => 'guilin',
		'filename' => 'cws-city-guilin.webp',
		'tagline'  => 'China’s most iconic landscapes.',
		'alt'      => 'Li River winding through misty karst mountains near Guilin',
		'url'      => '/destinations/guilin/',
	),
	array(
		'name'     => 'HANGZHOU',
		'label'    => 'Hangzhou',
		'slug'     => 'hangzhou',
		'filename' => 'cws-city-hangzhou.webp',
		'tagline'  => 'Lakeside beauty, tea and a slower rhythm.',
		'alt'      => 'Stone lanterns glowing over West Lake at sunset in Hangzhou',
		'url'      => '/destinations/hangzhou/',
	),
	array(
		'name'     => 'SUZHOU',
		'label'    => 'Suzhou',
		'slug'     => 'suzhou',
		'filename' => 'cws-city-suzhou.webp',
		'tagline'  => 'Classical gardens, canals and Jiangnan elegance.',
		'alt'      => 'Moon gate and pond in a classical Suzhou garden at dusk',
		'url'      => '/destinations/suzhou/',
	),
	array(
		'name'     => 'GUANGZHOU',
		'label'    => 'Guangzhou',
		'slug'     => 'guangzhou',
		'filename' => 'cws-city-guangzhou.webp',
		'tagline'  => 'The heart of Cantonese food and southern China.',
		'alt'      => 'Canton Tower rising above modern architecture in Guangzhou',
		'url'      => '/destinations/guangzhou/',
	),
);

$find_attachment = static function ( $filename ) {
	$attachments = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'     => '_wp_attached_file',
					'value'   => $filename,
					'compare' => 'LIKE',
				),
			),
		)
	);

	return $attachments ? (int) $attachments[0] : 0;
};

$card_blocks = '';

foreach ( $cities as $city ) {
	$attachment_id = $find_attachment( $city['filename'] );

	if ( ! $attachment_id ) {
		throw new RuntimeException( sprintf( 'Missing media attachment: %s', $city['filename'] ) );
	}

	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $city['alt'] );

	$image = wp_get_attachment_image(
		$attachment_id,
		'large',
		false,
		array(
			'class'    => 'cws-destination-card__image wp-image-' . $attachment_id,
			'loading'  => 'lazy',
			'decoding' => 'async',
			'sizes'    => '(max-width: 782px) 83vw, (max-width: 1024px) 43vw, 28vw',
		)
	);

	$image_link = sprintf(
		'<a href="%1$s" aria-label="Discover %2$s">%3$s</a>',
		esc_url( $city['url'] ),
		esc_attr( $city['label'] ),
		$image
	);

	$card_blocks .= sprintf(
		'<!-- wp:group {"className":"cws-destination-card cws-destination-card--%1$s","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-destination-card cws-destination-card--%1$s">
<!-- wp:image {"id":%2$d,"sizeSlug":"large","linkDestination":"custom","href":%7$s,"className":"cws-destination-card__media"} -->
<figure class="wp-block-image size-large cws-destination-card__media">%3$s</figure>
<!-- /wp:image -->

<!-- wp:group {"className":"cws-destination-card__body","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-destination-card__body">
<!-- wp:heading {"level":3,"className":"cws-destination-card__title"} -->
<h3 class="wp-block-heading cws-destination-card__title"><a href="%8$s">%4$s</a></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"cws-destination-card__tagline"} -->
<p class="cws-destination-card__tagline">%5$s</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"cws-destination-card__link"} -->
<p class="cws-destination-card__link"><a href="%8$s">Discover %6$s <span aria-hidden="true">→</span></a></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

',
		esc_attr( $city['slug'] ),
		$attachment_id,
		$image_link,
		esc_html( $city['name'] ),
		esc_html( $city['tagline'] ),
		esc_html( $city['label'] ),
		wp_json_encode( $city['url'] ),
		esc_url( $city['url'] )
	);
}

$section = sprintf(
	'<!-- wp:group {"tagName":"section","className":"cws-destinations","layout":{"type":"constrained"}} -->
<section class="wp-block-group cws-destinations">
<!-- wp:group {"className":"cws-destinations__inner","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-destinations__inner">
<!-- wp:group {"className":"cws-destinations__header","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-destinations__header">
<!-- wp:group {"className":"cws-destinations__heading-group","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-destinations__heading-group">
<!-- wp:paragraph {"className":"cws-destinations__eyebrow"} -->
<p class="cws-destinations__eyebrow">PLACES WORTH DISCOVERING</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"className":"cws-destinations__title","anchor":"explore-china-city-by-city"} -->
<h2 class="wp-block-heading cws-destinations__title" id="explore-china-city-by-city">Explore China, City by City</h2>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"cws-destinations__track","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-destinations__track">
%s</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->',
	$card_blocks
);

$section_css = <<<'CSS'
/* ===== Destination Carousel — 2026-08-11 ===== */
.home .cws-destinations {
	--cws-destination-gap: 24px;
	box-sizing: border-box;
	width: 100%;
	max-width: none !important;
	margin: 0 !important;
	padding: clamp(80px, 8vw, 120px) 0 clamp(88px, 9vw, 128px);
	overflow: hidden;
	background: #fffdf8;
	color: var(--cws-ink, #1e211e);
}

.home .cws-destinations__inner {
	box-sizing: border-box;
	width: min(calc(100% - 48px), 1180px);
	max-width: 1180px !important;
	margin: 0 auto !important;
	padding: 0 !important;
}

.home .cws-destinations__header,
.home .cws-destinations__heading-group,
.home .cws-destinations__track,
.home .cws-destination-card,
.home .cws-destination-card__body {
	max-width: none !important;
	margin: 0 !important;
	padding: 0 !important;
}

.home .cws-destinations__header > .wp-block-group__inner-container {
	display: block;
}

.home .cws-destinations__heading-group {
	width: min(100%, 820px);
}

.home .cws-destinations__eyebrow {
	margin: 0 0 18px;
	color: var(--cws-green, #315f52);
	font-size: 13px;
	font-weight: 700;
	letter-spacing: .18em;
	line-height: 1.45;
}

.home .cws-destinations__title {
	margin: 0;
	color: var(--cws-ink, #1e211e);
	font-family: Georgia, "Times New Roman", serif;
	font-size: clamp(38px, 4vw, 54px);
	font-weight: 400;
	letter-spacing: -.035em;
	line-height: 1.08;
}

.home .cws-destinations__track {
	margin-top: clamp(40px, 4.5vw, 56px) !important;
}

.home .cws-destinations__track > .wp-block-group__inner-container {
	display: flex;
	max-width: none !important;
	margin: 0 !important;
	padding: 0 !important;
	overflow-x: auto;
	overflow-y: hidden;
	gap: var(--cws-destination-gap);
	cursor: grab;
	overscroll-behavior-inline: contain;
	scroll-behavior: smooth;
	scroll-snap-type: x mandatory;
	scrollbar-width: none;
	-webkit-overflow-scrolling: touch;
}

.home .cws-destinations__track > .wp-block-group__inner-container::-webkit-scrollbar {
	display: none;
}

.home .cws-destinations__track > .wp-block-group__inner-container.is-dragging {
	cursor: grabbing;
	scroll-behavior: auto;
	scroll-snap-type: none;
	user-select: none;
}

.home .cws-destinations__track > .wp-block-group__inner-container.is-dragging * {
	cursor: grabbing !important;
}

.home .cws-destination-card {
	display: flex;
	box-sizing: border-box;
	min-width: 0;
	flex: 0 0 calc((100% - (var(--cws-destination-gap) * 2.35)) / 3.35);
	scroll-snap-align: start;
}

.home .cws-destination-card > .wp-block-group__inner-container {
	display: flex;
	width: 100%;
	min-width: 0;
	flex-direction: column;
}

.home .cws-destination-card,
.home .cws-destination-card a {
	cursor: grab;
}

.home .cws-destination-card__media {
	position: relative;
	width: 100%;
	max-width: none !important;
	margin: 0 !important;
	overflow: hidden;
	aspect-ratio: 3 / 4;
	background: #e6e0d5;
}

.home .cws-destination-card__media a,
.home .cws-destination-card__media img {
	display: block;
	width: 100%;
	height: 100%;
}

.home .cws-destination-card__media img {
	object-fit: cover;
	object-position: var(--cws-city-focal, 50% 50%);
	-webkit-user-drag: none;
	transition: transform 420ms cubic-bezier(.2, .7, .2, 1);
	user-select: none;
}

.home .cws-destination-card__body {
	display: flex;
	box-sizing: border-box;
	padding-top: 22px !important;
	flex: 1;
	flex-direction: column;
}

.home .cws-destination-card__title {
	margin: 0 0 10px;
	color: var(--cws-ink, #1e211e);
	font-family: Georgia, "Times New Roman", serif;
	font-size: clamp(24px, 2.15vw, 30px);
	font-weight: 400;
	letter-spacing: .01em;
	line-height: 1.15;
}

.home .cws-destination-card__title a,
.home .cws-destination-card__link a {
	color: inherit;
	text-decoration: none;
}

.home .cws-destination-card__tagline {
	min-height: 3.2em;
	margin: 0 0 18px;
	color: #49514d;
	font-size: 16px;
	line-height: 1.6;
}

.home .cws-destination-card__link {
	margin: auto 0 0;
	color: var(--cws-green, #315f52);
	font-size: 15px;
	font-weight: 700;
	line-height: 1.45;
}

.home .cws-destination-card__link a {
	display: inline-block;
	border-bottom: 1px solid rgba(49, 95, 82, .36);
	transition: border-color 180ms ease;
}

.home .cws-destination-card__link a:hover,
.home .cws-destination-card__link a:focus-visible,
.home .cws-destination-card__title a:hover,
.home .cws-destination-card__title a:focus-visible {
	color: var(--cws-green, #315f52);
}

.home .cws-destination-card__link a:hover,
.home .cws-destination-card__link a:focus-visible {
	border-color: currentColor;
}

@media (hover: hover) and (pointer: fine) {
	.home .cws-destination-card__media a:hover img,
	.home .cws-destination-card__media a:focus-visible img {
		transform: scale(1.03);
	}
}

@media (max-width: 1024px) {
	.home .cws-destinations {
		--cws-destination-gap: 22px;
	}

	.home .cws-destination-card {
		flex-basis: calc((100% - (var(--cws-destination-gap) * 1.25)) / 2.25);
	}
}

@media (max-width: 782px) {
	.home .cws-destinations {
		--cws-destination-gap: 16px;
		padding: 72px 0 84px;
	}

	.home .cws-destinations__inner {
		width: min(calc(100% - 40px), 1180px);
	}

	.home .cws-destinations__eyebrow {
		margin-bottom: 16px;
		font-size: 12px;
		letter-spacing: .14em;
	}

	.home .cws-destinations__title {
		font-size: clamp(34px, 10vw, 44px);
	}

	.home .cws-destinations__track {
		margin-top: 34px !important;
	}

	.home .cws-destination-card {
		flex-basis: calc((100% - (var(--cws-destination-gap) * .15)) / 1.15);
	}

	.home .cws-destination-card__body {
		padding-top: 18px !important;
	}

	.home .cws-destination-card__title {
		font-size: 25px;
	}

	.home .cws-destination-card__tagline {
		min-height: 0;
		margin-bottom: 16px;
	}
}

@media (prefers-reduced-motion: reduce) {
	.home .cws-destinations__track > .wp-block-group__inner-container {
		scroll-behavior: auto;
	}

	.home .cws-destination-card__media img {
		transition: none;
	}
}
CSS;

$content         = (string) get_post_field( 'post_content', $front_page_id );
$current_css     = (string) wp_get_custom_css();
$content_changed = false;
$css_changed     = false;

$normalized_content = preg_replace(
	'/\s*<!-- wp:html -->\s*<div class="cws-destinations__nav".*?<\/div>\s*<!-- \/wp:html -->\s*/s',
	"\n\n",
	$content,
	1
);

if ( null === $normalized_content ) {
	throw new RuntimeException( 'Unable to remove the legacy Destination Carousel buttons.' );
}

if ( false === strpos( $normalized_content, 'cws-why-travel' ) ) {
	throw new RuntimeException( 'The confirmed Why Travel section is missing.' );
}


if ( false === strpos( $normalized_content, 'cws-destinations' ) ) {
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

if ( false === strpos( $current_css, 'Destination Carousel — 2026-08-11' ) ) {
	$why_marker = '/* ===== Why Travel — 2026-08-11 ===== */';

	if ( false !== strpos( $current_css, $why_marker ) ) {
		$updated_css = str_replace(
			$why_marker,
			trim( $section_css ) . "\n\n" . $why_marker,
			$current_css
		);
	} else {
		$updated_css = rtrim( $current_css ) . "\n\n" . trim( $section_css ) . "\n";
	}
} else {
	$updated_css = preg_replace(
		'/\/\* ===== Destination Carousel — 2026-08-11 ===== \*\/.*?(?=\/\* ===== [^\r\n]+ ===== \*\/|\z)/s',
		trim( $section_css ) . "\n\n",
		$current_css,
		1
	);
}

if ( null === $updated_css ) {
	throw new RuntimeException( 'Unable to prepare the Destination Carousel CSS update.' );
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
	"Destination Carousel applied. content_changed=%s css_changed=%s\n",
	$content_changed ? 'yes' : 'no',
	$css_changed ? 'yes' : 'no'
);
