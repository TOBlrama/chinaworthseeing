# ChinaWorthSeeing MVP Production Release Check

- Version: `mvp-2026-08-16`
- Checked at: 2026-08-16 19:44 CST (UTC+08:00)
- Target: `https://www.chinaworthseeing.com/`
- Scope: local release artifacts and public production surface, read-only
- Verdict: **BLOCKED**

## Executive verdict

The domain, HTTPS redirects, TLS certificate, homepage, navigation and Contact form shell are reachable, and the mobile form layout has no page-level horizontal overflow at a 390 × 844 viewport. The site is nevertheless not ready to be declared formally launched. Search indexing, privacy disclosure and the enquiry delivery path do not yet satisfy the confirmed MVP release gates.

No production content, DNS, credentials, form entries or public configuration were changed during this check.

## Release blockers

### B1 — Search launch is still disabled

Evidence:

- Home, Contact, About, Explore China and every sampled child page return `200` but include `<meta name="robots" content="noindex, nofollow">`.
- `https://www.chinaworthseeing.com/wp-sitemap.xml` returns `404`.
- `robots.txt` is reachable and does not globally disallow the site, so the blocking signal is the WordPress page-level `noindex` state.

Impact: the formal site cannot be indexed and does not meet the MVP SEO requirement for indexable content and a sitemap.

Exit condition: after all other blockers pass, enable indexing for completed launch pages, keep placeholder/structure-only pages explicitly `noindex`, verify the sitemap returns `200`, and recheck canonical/robots output from a logged-out request.

### B2 — The Contact privacy disclosure is broken

Evidence:

- Contact renders the required privacy notice and links to `/privacy-policy/`.
- `/privacy-policy/` returns a Chinese WordPress `404` page.
- The public REST page list contains no published `privacy-policy` page.

Impact: visitors are asked to submit name, email, WhatsApp number and travel details without access to the promised privacy notice. This is a conversion, trust and privacy blocker.

Exit condition: publish the approved English Privacy Policy at the exact URL, select it as the WordPress privacy page, verify the Contact link and status `200`, and review the policy against the services actually enabled in production.

### B3 — The production enquiry path has not passed an end-to-end test

Evidence:

- The public Contact page exposes labelled required fields, an enabled Send enquiry button and a Cloudflare Turnstile widget with a site key.
- DNS currently publishes Cloudflare Email Routing MX records, SPF covering Cloudflare and Elastic Email, Elastic Email DKIM (`api._domainkey`) and DMARC `p=none`.
- No production form submission was made during this read-only release check, so success state, database persistence, Gmail receipt, sender authentication and Reply-To behavior remain unverified.
- The project requirement explicitly requires a real submission-to-human-follow-up rehearsal before release.

Impact: the site's primary business function could silently lose enquiries even though the form UI loads.

Exit condition: use approved synthetic data to test required-field failure, Turnstile success/failure, submission success, Fluent Forms entry storage, Gmail notification, Reply-To and a practical WhatsApp follow-up. Remove the synthetic record afterward under the agreed retention/testing process.

### B4 — English launch localization is incorrect

Evidence:

- English pages declare `<html lang="zh-Hans">`.
- The skip link and 404 experience are Chinese.

Impact: assistive technology and search engines receive the wrong language signal; the error experience conflicts with an English-first launch.

Exit condition: set the production site language to English and verify `lang="en-US"` or the agreed English locale on all launch templates, including 404 and form validation messages.

### B5 — Public trust/content gates are incomplete

Evidence:

- `/about/` contains only the page title.
- Seven Destination, three Experience and five Guide pages are published and linked from the primary navigation/homepage while displaying internal review wording such as “The page is published now so the site structure and navigation can be reviewed.”

Impact: core trust navigation leads to unfinished/internal-facing content. Enabling global indexing as-is would expose 15 thin placeholder pages to search.

Exit condition: publish an approved minimum About page; replace internal review wording with visitor-facing copy; and either keep all unfinished pages explicitly `noindex` or remove them from public navigation until substantive content is ready.

### B6 — Backup, SSL mode and recovery evidence — closed before deployment

Evidence:

- Public HTTPS is valid: HTTP and apex requests redirect to HTTPS `www`; TLS 1.3 succeeds with a valid Google Trust Services certificate (valid through 2026-11-07).
- Cloudflare was subsequently verified in the signed-in dashboard as `Full (strict)`.
- A new Cloudways on-demand application backup covering files and database completed at 2026-08-16 11:58:08 UTC; the restore interface lists it as the latest recovery point.
- Cloudways Let's Encrypt covers the apex and `www` hostnames, expires 2026-11-11 and has automatic renewal enabled.

Status: this blocker is closed for the imminent mutation. Recheck the same settings after deployment.

Exit condition: create and record a fresh Cloudways application/database backup before deployment, verify `Full (strict)`, and document the rollback/restore path.

### B7 — Credential residue found during the check — locally remediated

Evidence:

- At audit time, `.codex-tmp/cloudways-launch-20260813/` contained `id_ed25519`, `id_ed25519.pub` and `configure-turnstile.php`.
- The private key contents were not read. The current process could not read its ACL.
- The decision log says the temporary public key was revoked and the local migration package was removed, but the current filesystem contradicts the local-cleanup portion of that statement.

Remediation after the read-only audit: all three temporary files were deleted from the workspace without reading or quoting their contents. The directory is now empty. The private/public key files and configuration script are not recoverable from that temporary location.

Subsequent dashboard verification found two active master-credential public keys named `Codex temporary deploy 2026-08-13` and `Codex launch config 2026-08-13`. Their local private keys have been deleted, but the remote public keys are still authorised. Delete both before deployment, add only a new narrowly scoped temporary deployment key if approved, and delete that new key after deployment.

## Quality and security observations

### Passed or positively observed

- DNS resolves through Cloudflare; HTTP and apex canonicalization reach HTTPS `www`.
- TLS certificate validation passes; negotiated protocol was TLS 1.3 with an AEAD cipher.
- Home, Contact, About, Explore China and all sampled navigation child URLs respond.
- Public homepage and Contact DOM expose useful headings, labelled form controls and a skip link.
- At 390 × 844, the public page width equals the document width; visible form controls fit the viewport. Apparent admin-toolbar overflow was excluded as authenticated-browser UI, not public-page content.
- Homepage images loaded without broken resources in the browser check.
- Node syntax checks passed for all local `.js`/`.mjs` files; the country-code JSON parsed successfully.
- Static scans found no `eval`, `base64_decode`, shell execution, anonymous AJAX or custom REST endpoint use in project PHP/JS.
- Direct probes of `/.git/HEAD`, `/wp-config.php` and `/wp-content/debug.log` were denied (`403`).

### Non-blocking hardening/debt

- HTML responses lack HSTS, Content-Security-Policy, X-Frame-Options/frame-ancestors, Referrer-Policy and Permissions-Policy headers. Add deliberately and test compatibility; do not copy a generic CSP into WordPress without staging validation.
- `/readme.html` and `/license.txt` are public; remove or deny the readme to reduce version fingerprinting.
- `/wp-json/wp/v2/users` publicly exposes the author name/slug. Treat as user-enumeration hardening rather than a substitute for strong credentials and MFA.
- `/xmlrpc.php` returned Cloudflare `520`; prefer a deliberate allow/deny decision and a stable response.
- The Contact page logged warnings for a Turnstile script query parameter and unsupported Choices.js options. They did not block rendering but should be cleared during post-deployment QA.
- The repository has no Git metadata, CHANGELOG or technical-debt register; README and roadmap are stale relative to the deployed state. This weakens release traceability.
- The planned `cws-production-indexing.php` was corrected after this audit: `placeholder` pages and structure-only parent indexes remain `noindex`, while the content-bearing Explore China hub is explicitly allowed to be indexed. A live per-page robots assertion is still required after deployment.
- The planned validator does not yet prove sitemap status, per-page robots output, live Contact-to-Privacy linkage, notification delivery or rollback readiness. These require live checks after deployment.

## Test limitations

- PHP is not available on the normal local PATH. After the audit, WordPress Studio WP-CLI successfully parsed the new indexing and launch scripts; the production-launch script stopped at its expected domain guard before mutation, and the read-only validator ran against the local WordPress instance and reported the expected local/production differences. Final PHP execution and validation must still run in Cloudways.
- No production form was submitted, no email was sent, no dashboard setting was changed, and no backup/restore operation was performed because this assignment was read-only.
- Cloudflare dashboard settings, origin certificate mode, Cloudways backup state and remote SSH-key state cannot be inferred from public HTTP alone.

## Required re-check sequence

1. Confirm a fresh backup and rollback point.
2. Deploy the reviewed launch changes without altering unrelated DNS.
3. Run PHP lint and all WordPress validators in the Cloudways runtime.
4. Verify Privacy `200`, English `lang`, completed-page indexing, placeholder-page `noindex`, canonical URLs, robots and sitemap.
5. Run desktop and 390px mobile checks while logged out.
6. With explicit approval for synthetic data, complete the form/Turnstile/database/Gmail/Reply-To/WhatsApp rehearsal.
7. Verify `Full (strict)`, mail routing and post-change DNS records.
8. Remove credential residue and update the decision log with actual results only.
9. Re-run this release gate. Only then may the verdict change to **READY FOR RELEASE**.

## Final verdict

**BLOCKED — do not announce the production launch as complete and do not remove global `noindex` until B2, B3 and B4 are closed, the two active remote keys in B7 are removed, and B1/B5 are implemented using page-level indexing guardrails. B6 is closed for the pre-deployment state.**

## Post-deployment re-check — 2026-08-16 20:32 CST

This addendum records production changes explicitly authorised after the read-only audit. It does not rewrite the evidence or verdict that applied at 19:44 CST.

- B1 closed: WordPress search visibility is enabled; Home and the other completed launch pages no longer output `noindex`; a representative Beijing placeholder outputs `max-image-preview:large, noindex`; `/wp-sitemap.xml` returns `200 application/xml` with a sitemap index.
- B2 closed: `/privacy-policy/` is published, selected as the WordPress privacy page, returns successfully, and remains linked from Contact. The production policy names the enabled hosting, Cloudflare, email and WhatsApp/Meta processing stack and is dated 16 August 2026.
- B4 closed: a fresh production request reports WordPress locale `en_US`, and sampled public pages output `lang="en-US"`.
- B5 closed for the agreed minimum launch boundary: About contains the managed minimum trust copy; the 15 internal-review notices were replaced with visitor-facing preparation notices; all 15 unfinished child pages remain `noindex`.
- B6 rechecked through the deployment record: the 2026-08-16 11:58:08 UTC Cloudways files-and-database backup remains the documented rollback point; no DNS change was made.
- B7 closed: the two 2026-08-13 public keys and the one 2026-08-16 deployment key were deleted from Cloudways; the key list is empty. Server staging scripts and both local deployment-key directories were deleted.
- Runtime validation passed all 13 checks, including production URL, locale, search visibility, Turnstile configuration, indexing guardrail, required pages, privacy-page selection and placeholder handling.
- B3 remains open: no production synthetic enquiry was submitted, so Fluent Forms persistence, visible success state, Gmail delivery and Reply-To are not yet proven end to end.

Mail incident update at 21:00 CST: the project owner completed a human production submission, which was persisted as Fluent Forms submission `#4`, but its notification failed with the explicit WP Mail SMTP error `APIKey Expired`. With separate approval, WP Mail SMTP was switched from the expired application-level Elastic Email API connection to the already-enabled Cloudways server-level Elastic Email path via Default/PHP mailer. A plugin test email then completed without a new failure banner or error indicator. Automated form submission was correctly rejected by Turnstile and created no entry; Fluent Forms Lite cannot resend an existing notification. B3 therefore remains open only for Gmail receipt confirmation and one post-fix human form submission/Reply-To check.

Current gate: **CONDITIONALLY LIVE, RELEASE CHECK STILL BLOCKED ON B3**. The public informational surface is live and indexable according to the confirmed page-level boundary, but the enquiry workflow must not be described as fully verified until the approved synthetic submission and mail test pass.
