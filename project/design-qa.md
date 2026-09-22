# Homepage Design QA

## Homepage What We Do

Date: 2026-08-12

### Design and content boundary

- Adopted: a minimal two-level editorial statement that transitions the photographic Hero into a warm, light service explanation.
- Removed: the previous eyebrow, split layout, `01 / 02 / 03` values and separate `We Plan. You Stay in Control.` statement.
- Preserved: Hero, Brand Statement, Why Travel and Destination Carousel content and internal styling; no image, JavaScript, external library, framework, icon package or parent-theme change was introduced.
- Content boundary: the section states that ChinaWorthSeeing creates detailed personalized travel plans rather than packaged tours; itinerary links may support bookings, while customers remain free to book independently.
- Refined: What We Do now uses the exact navigation warm-ivory background (`#f4efe5`) and the same sans-serif font family as the Brand Statement title; Brand Statement uses pure white and its CTA label is `GET IN TOUCH`.

### Responsive and visual review

- Passed: the section renders once, immediately after Hero and immediately before Brand Statement; both measured section boundary gaps are `0px`.
- Passed: desktop and mobile reuse the existing Georgia display typography, PT Sans body typography and ink/warm-ivory color family without adding a second design system.
- Passed: the main title wraps to two lines at 1440px, 1280px and 1024px; three lines at 768px; and four balanced lines at 390px and 375px. The subtitle wraps to two lines on desktop/tablet and three lines on mobile.
- Passed: the rendered section contains exactly one H2 and one paragraph, with no eyebrow, cards, numbered values, links, buttons, images or hidden legacy service copy.
- Passed: no page-level horizontal overflow occurs at 1440px, 1280px, 1024px, 768px, 390px or 375px.
- Passed: the mobile subtitle remains readable and transitions directly into Brand Statement without overlap or an artificial gap.
- Passed: no browser console errors or uncaught page errors were observed; all page images loaded and Smart Slider retained eight progress dots.
- Passed: the application script is idempotent (`content_changed=no`, `css_changed=no` on the final rerun); Smart Slider remains active and Hever remains the active theme.

final result: passed

## Homepage Hero

Date: 2026-08-10

## Comparison input

- Reference: `docs/design/homepage-hero-concept-v1.png` (1586 × 992)
- Implementation: `docs/design/homepage-hero-implementation-desktop.png` (1586 × 992)
- Side-by-side comparison: `docs/design/homepage-hero-qa-comparison.png`
- Mobile implementation: `docs/design/homepage-hero-implementation-mobile.png` (390 × 844 viewport)

## Visual review

- Passed: warm ivory independent header, wordmark, primary navigation, separate green action button, full-width hero, left gradient, serif headline, supporting copy, dual actions, circular arrows and eight progress dots.
- Passed: hero fills the first viewport without horizontal overflow; desktop measured `scrollWidth === clientWidth`.
- Passed: 390px mobile layout has no horizontal overflow; wordmark and menu remain visible, text stays readable and actions become full-width.
- Passed: all eight supplied images render through the WordPress slider; automatic progression changed from one active slide to the next after the configured interval.
- Accepted difference: the reference illustration includes a globe control. It remains omitted because the launch language entry is still a pending product decision.
- Accepted difference: the WordPress admin toolbar appears in the authenticated implementation screenshot but is not visible to public visitors.
- Accepted difference: the supplied Great Wall photograph replaces the generated concept image while preserving the reference crop hierarchy and text-safe left area.

final result: passed

## Explore China navigation label refinement

Date: 2026-08-11

- Confirmed: the top-level Explore China item renders as a plain `SPAN` label and no longer renders an anchor to `/explore-china/`.
- Passed: the adjacent disclosure button remains the only interactive control for the top-level item and exposes correct `aria-controls` and `aria-expanded` states.
- Passed: desktop hover/click opening remains available; Escape closes the Mega Menu and returns focus to the disclosure button.
- Passed: mobile navigation retains the independent accordion interaction, a 44 × 48px Explore disclosure target, and no page-level horizontal overflow at 390px.
- Passed: the Explore China label is not included in the tab order; Places to Go, Experiences, Plan Your Trip and their child links remain normal links.
- Passed: no browser console warnings or errors were observed after the change.
- Passed: Home, About and Contact now use 55px-high flex link boxes whose measured centers equal the 27.5px header center.
- Passed: Places to Go, Experiences and Plan Your Trip render as plain `SPAN` labels with no anchors and no tab stops; their child destination, experience and guide links remain interactive.
- Passed: mobile retains separate 44 × 44px accordion buttons for all three text labels, with correct `aria-expanded` state and no horizontal overflow.

final result: passed

## Homepage Why Travel cards

Date: 2026-08-11

### Reference and implementation boundary

- Reference: Safari Frank `Why travel with SAFARI FRANK` section and the local handoff/source supplied by the project owner.
- Adopted: centered eyebrow and title, three equal cards on desktop, one card per row on mobile, image-first card hierarchy and cover cropping.
- Not copied: Safari Frank code, photography, fonts, copy or brand assets.
- Implementation: native WordPress Group, Heading, Image and Paragraph blocks plus a dedicated `Why Travel — 2026-08-11` block in Additional CSS.

### Content, image and responsive review

- Passed: the section renders once as the immediate next structural block after Brand Statement; the eyebrow, heading, three titles and three supplied descriptions match the confirmed copy and order.
- Passed: desktop viewport 1280 × 720 renders three equal 376px-wide columns at x=42, 444 and 846; all cards are 393px high and there is no horizontal overflow.
- Passed: mobile viewport 390 × 844 renders one roughly 335px-wide column; all images retain a 3:2 display ratio and there is no horizontal overflow.
- Passed: all three responsive images completed loading on desktop and mobile. The 1800 × 1200 source PNG files remain unchanged; WordPress uses WebP derivatives of approximately 303KB, 283KB and 153KB.
- Passed: image order is Natural Wonders, History & Heritage, Flavours of China, with descriptive alt text stored in the WordPress media library.
- Passed: Smart Slider remains active with eight slide backgrounds and eight progress dots; clicking the second progress dot activates the second slide.
- Passed: no browser console warnings or errors were observed.
- Passed: the application script is idempotent (`content_changed=no`, `css_changed=no` on the final rerun); Hever remains the active parent theme and Smart Slider remains active.

final result: passed

## Homepage Brand Statement

Date: 2026-08-11

### Reference and implementation boundary

- Reference: Black Tomato homepage Brand Statement and the locally archived source under `C:\Users\盛义翔\Desktop\blacktomato`.
- Adopted: full-width light section, centered narrow text column, compact letter-spaced heading, centered paragraphs and restrained vertical spacing.
- Not copied: Black Tomato fonts, background image, button, code and brand assets.
- Implementation: native WordPress Group, Heading, Paragraph, Buttons and Button blocks plus a dedicated `Brand Statement — 2026-08-11` block in Additional CSS.

### Desktop and mobile review

- Passed: the Brand Statement is the immediate next sibling after the Hero with a measured zero gap.
- Passed: the confirmed heading and all four paragraphs render once and in the supplied order.
- Passed: desktop viewport 1280 × 720 uses a 900px centered text column and has no horizontal overflow.
- Passed: mobile viewport 390 × 844 uses a roughly 335px centered text column, 15px heading, 16px body copy and 25.92px body line height.
- Passed: mobile and desktop preserve centered alignment and readable paragraph separation.
- Passed: one `get in touch` button renders after the fourth paragraph, remains inside the Brand Statement inner group and links to `/contact/`.
- Passed: desktop button is approximately 138 × 48px; mobile button expands to the 335px text-column width while retaining a 48px minimum touch height.
- Passed: the Brand Statement and Why Travel boundaries still touch with a measured effective gap of 0px after the button was added.
- Passed: Smart Slider remains present with eight slide backgrounds and eight progress dots; autoplay advanced from slide 4 to slide 5 during the timed check.
- Passed: no browser console warnings or errors were observed.
- Passed: Hever parent-theme files and Smart Slider plugin files were not modified.

final result: passed

## Additional CSS duplicate cleanup

Date: 2026-08-10

- Before: 257,575 characters, 13,233 lines, 4 copies of the fixed-navigation block.
- After: 44,212 rendered characters, 2,254 rendered lines, 1 fixed-navigation block.
- Passed: the published front-end CSS contains only the selected effective base block and the latest navigation override.
- Passed: desktop homepage geometry and computed navigation styles are exactly equal before and after cleanup.
- Passed: the header remains fixed while scrolling; Home and Inspirations current-page underline states remain correct.
- Passed: the four ordinary navigation gaps remain equal at the 1280px desktop viewport.
- Passed: Smart Slider still renders eight dots and autoplay advances to the next slide.
- Passed: mobile preview has no horizontal overflow and retains the wordmark and compact menu control.
- Passed: no browser console warnings or errors were observed after publication.
- Screenshots: `docs/design/css-cleanup-before.png` and `docs/design/css-cleanup-after.png`.

final result: passed

## Fixed navigation refinement

Date: 2026-08-10

### Comparison input

- Before: `docs/design/header-before-fixed-right.png`
- Desktop implementation: `docs/design/header-fixed-right-desktop.png` (1280 × 720 viewport)
- Mobile implementation: `docs/design/header-fixed-right-mobile.png` (390 × 844 viewport)
- Side-by-side comparison: `docs/design/header-fixed-right-comparison.png`

### Visual and functional review

- Passed: the header is fixed to the top of the viewport and remains at the same position after scrolling.
- Passed: the header spans the full viewport; all five navigation entries are grouped on the right and the call-to-action ends 64px from the desktop edge.
- Passed: Home, Inspirations, About, and Contact use equal measured gaps (42.875px at 1280px).
- Passed: ordinary navigation links keep the same ink color for default, hover, focus, visited, active, and current-page states.
- Passed: the current page is indicated only by a 2px green underline; this was verified on both Home and Inspirations.
- Passed: Start Planning remains a separate green pill with white text.
- Passed: desktop and 390px mobile views have no horizontal overflow; mobile retains the wordmark and compact menu control.
- Accepted difference: authenticated screenshots include the WordPress admin/customizer toolbar; public visitors do not see it.

final result: passed

## Homepage vertical spacing refinement

Date: 2026-08-11

### Root-cause inspection

- Confirmed: the Hero and Brand Statement bounding boxes already touched with a measured section gap of `0px` on desktop and mobile.
- Confirmed: Hero had `margin-bottom: 0`, `padding-bottom: 0`; Brand Statement had `margin-top: 0`; no WordPress Spacer block existed.
- Root cause: the perceived blank area was Brand Statement's own top padding—89.6px at the 1280px desktop viewport and 64px at the 390px mobile viewport.
- Resolution: retained the Hero's black bottom strip and replaced only the excessive Brand Statement top-padding rule. No negative margin was introduced.

### Desktop and mobile verification

- Passed: desktop 1280 × 720 has a `0px` section gap; Brand Statement top padding is 51.2px from the responsive 40–56px range, while the existing 89.6px bottom padding remains unchanged.
- Passed: mobile 390 × 844 has a `0px` section gap and 32px Brand Statement top padding; the existing 68px bottom padding remains unchanged.
- Passed: Brand Statement internal spacing remains unchanged—mobile title margin-bottom is 26px, paragraph margins are 17px, and body line height is 25.92px.
- Passed: Why Travel eyebrow-to-title and title-to-card gaps are equal at 18px on desktop and 16px on mobile.
- Passed: desktop retains three card columns; mobile retains one card column; neither viewport has horizontal overflow.
- Passed: Smart Slider remains active with eight slide backgrounds and eight progress dots; no browser console warnings or errors were observed.
- Passed: both application scripts are idempotent on the final rerun; no temporary PHP files remain in the WordPress site root.

final result: passed

## Homepage destination carousel

Date: 2026-08-11

### Existing-system preservation

- Passed: before and after SHA-256 hashes are identical for the Hero, Brand Statement and Why Travel top-level Gutenberg blocks.
- Passed: the new `cws-destinations` section is the immediate next structural block after Why Travel and renders once with seven unique city cards.
- Passed: Hever remains the active theme; Smart Slider remains active with eight slide backgrounds and eight progress dots.

### Design-system and responsive review

- Passed: reused the existing 1180px content width, PT Sans body typography, Georgia display typography and `#1e211e`, `#315f52`, `#f4efe5` design tokens.
- Passed: desktop 1280 × 720 renders 3.35 cards with 335.4px card width and 24px gaps; the fourth card intentionally remains partially visible.
- Passed: tablet 900 × 800 renders 2.25 cards with 359.63px card width and 22px gaps.
- Passed: mobile 390 × 844 renders 1.15 cards with 288.93px card width and 16px gaps; the next card remains visible as a swipe cue.
- Passed: all images render at an exact 3:4 ratio with `object-fit: cover`; supplied source files already matched 1200 × 1600, so no focal-subject crop was required.
- Passed: no card border, box shadow, large radius, rating, price or OTA-style metadata was introduced.
- Passed: no desktop, tablet or mobile viewport has page-level horizontal overflow.

### Interaction, accessibility and performance review

- Passed: visible previous/next buttons and their navigation markup have been removed completely.
- Passed: pressing and dragging directly on a desktop image/card area advanced and snapped the track by one 359.33px card step; native image dragging is disabled so the gesture controls the carousel itself.
- Passed: mobile horizontal drag advanced and snapped the track by one 304.67px card step without page-level horizontal movement.
- Passed: the track remains keyboard-focusable, exposes a labelled region and supports ArrowLeft/ArrowRight as a non-pointer alternative.
- Passed: all 21 image/title/discover links are clearly marked placeholders and do not navigate until destination pages exist.
- Passed: the seven 1200 × 1600 WebP files range from approximately 74KB to 493KB; WordPress supplies responsive `srcset`/`sizes`, while offscreen images remain lazy-loaded.
- Passed: reduced-motion removes smooth scrolling and image transition; the carousel remains natively horizontally scrollable without JavaScript.
- Passed: no browser console warnings or errors were observed.
- Passed: final rerun is idempotent (`content_changed=no`, `css_changed=no`); the MU-plugin deployment matches the project source and no temporary PHP files remain.

final result: passed
