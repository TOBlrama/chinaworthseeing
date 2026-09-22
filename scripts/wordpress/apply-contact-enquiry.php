<?php
/**
 * Create or update the confirmed Contact enquiry form and Privacy Policy.
 *
 * The script is safe to rerun. It updates the managed Fluent Forms record,
 * the Contact page and the Privacy Policy in place.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wpFluent' ) ) {
	throw new RuntimeException( 'Fluent Forms must be active before applying the Contact enquiry form.' );
}

$managed_marker = 'cws-managed-contact-enquiry-v1';
$notification_email = 'felixsheng27@gmail.com';

$find_page = static function ( $slug ) {
	$page = get_page_by_path( $slug, OBJECT, 'page' );
	return $page ? (int) $page->ID : 0;
};

$upsert_page = static function ( $args ) use ( $find_page ) {
	$page_id = ! empty( $args['existing_id'] ) ? (int) $args['existing_id'] : $find_page( $args['slug'] );

	$post_data = array(
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_title'   => $args['title'],
		'post_name'    => $args['slug'],
		'post_content' => $args['content'],
		'post_excerpt' => isset( $args['excerpt'] ) ? $args['excerpt'] : '',
	);

	if ( $page_id ) {
		$post_data['ID'] = $page_id;
		$result          = wp_update_post( wp_slash( $post_data ), true );
	} else {
		$result = wp_insert_post( wp_slash( $post_data ), true );
	}

	if ( is_wp_error( $result ) ) {
		throw new RuntimeException( $result->get_error_message() );
	}

	return (int) $result;
};

$privacy_content = <<<'HTML'
<!-- cws-managed-contact-enquiry-v1 -->
<!-- wp:group {"tagName":"article","className":"cws-privacy-policy","layout":{"type":"constrained"}} -->
<article class="wp-block-group cws-privacy-policy">
<!-- wp:paragraph {"className":"cws-privacy-policy__eyebrow"} -->
<p class="cws-privacy-policy__eyebrow">Privacy &amp; data</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Privacy Policy</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"cws-privacy-policy__updated"} -->
<p class="cws-privacy-policy__updated">Last updated: 16 August 2026</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>This Privacy Policy explains how ChinaWorthSeeing collects, uses, stores and shares personal information when you browse our website, submit an enquiry or communicate with us.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">1. Who we are</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>ChinaWorthSeeing is operated by Yixiang Sheng, an individual based in China. For privacy questions or requests, contact <a href="mailto:felixsheng27@gmail.com">felixsheng27@gmail.com</a>.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">2. Information we collect</h2>
<!-- /wp:heading -->
<!-- wp:list -->
<ul><li>Contact details, including your first and last name, email address, WhatsApp country code and number.</li><li>Trip details, including destinations of interest, expected travel month and year, trip duration and number of travellers.</li><li>Information you choose to include in additional comments or later correspondence.</li><li>Limited technical and security data, such as IP address, browser or device information, timestamps and website logs.</li></ul>
<!-- /wp:list -->
<!-- wp:paragraph -->
<p>Please do not send passport numbers, payment-card details or unnecessary sensitive information through the initial enquiry form. If health, dietary, accessibility, child or companion information becomes necessary to provide a requested service, please provide only what is relevant and make sure you are authorised to share information about other people.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">3. How we collect information</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>We collect information directly from you when you submit a website form, email us, contact us through WhatsApp or continue a planning conversation. We may also receive basic technical information automatically from our website hosting, security and anti-spam services.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">4. How and why we use information</h2>
<!-- /wp:heading -->
<!-- wp:list -->
<ul><li>To review and respond to your enquiry.</li><li>To understand your travel preferences and discuss a personalised travel-planning service.</li><li>To communicate with you by email or WhatsApp about the enquiry you submitted.</li><li>To prepare and deliver an agreed service.</li><li>To protect the website, prevent spam or fraud, troubleshoot problems and keep necessary records.</li><li>To comply with legal obligations and handle complaints or disputes.</li></ul>
<!-- /wp:list -->
<!-- wp:paragraph -->
<p>Depending on the situation, we rely on steps requested by you before entering into an agreement, performance of an agreement, our legitimate interests in responding to enquiries and protecting the website, your consent where sensitive information or optional sharing is involved, and legal obligations. We do not use enquiry data for newsletters, advertising retargeting, automated pricing or automated decision-making, and we do not use it to train artificial-intelligence systems.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">5. When information is required</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Fields marked with an asterisk are required so that we can understand and respond to your enquiry. If you do not provide them, the form cannot be submitted. Additional comments are optional.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">6. Who we share information with</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>We do not sell, rent or exchange personal information. We may use service providers that help us host, secure, store or communicate about enquiries, including WordPress and Fluent Forms, Cloudways and DigitalOcean, Cloudflare (including Turnstile and Email Routing), Gmail, Elastic Email, WhatsApp and Meta, Notion, Google Drive or Google Sheets, and devices used to manage the service.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>We only provide a travel supplier with the minimum information necessary when you explicitly ask us to arrange the relevant cooperation or when a service has been confirmed. We may also disclose information if required by law or when reasonably necessary to protect rights, safety or the website.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">7. International processing</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>ChinaWorthSeeing is operated from China, while some service providers may process information in other countries. Those countries may have privacy laws different from the laws where you live. We select established providers and use available contractual, organisational and technical safeguards appropriate to the service.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">8. Cookies, security and anti-spam</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>The website may use cookies or similar storage that is technically necessary for WordPress, security, form operation and your session. We do not currently use Google Analytics, Meta Pixel, advertising cookies or behavioural retargeting.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>We use Cloudflare Turnstile to reduce automated spam. Turnstile may process technical information in accordance with <a href="https://www.cloudflare.com/privacypolicy/" rel="noopener noreferrer" target="_blank">Cloudflare’s Privacy Policy</a>.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">9. How long we keep information</h2>
<!-- /wp:heading -->
<!-- wp:list -->
<ul><li>Inactive or unsuccessful enquiries: generally three months after the last meaningful contact.</li><li>Completed services: generally one month after completion.</li><li>Security and technical logs: generally 30 days.</li><li>Backups: generally 30 days, after which they rotate or are deleted.</li></ul>
<!-- /wp:list -->
<!-- wp:paragraph -->
<p>We may keep limited information longer where reasonably necessary for a complaint, dispute, fraud investigation, legal claim or legal obligation. Because WordPress does not yet record whether each enquiry became a completed service, deletion against these two enquiry periods is currently an operational review rather than a fully automatic process.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">10. Your choices and rights</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Depending on where you live, you may ask whether we hold your information and request access, correction, deletion, restriction, objection, portability or withdrawal of consent. You may also complain to the privacy or data-protection authority in your country. We may need to verify your identity before completing a request.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>Send requests to <a href="mailto:felixsheng27@gmail.com">felixsheng27@gmail.com</a>. We aim to respond within 30 days. If a request is complex or local law permits more time, we will tell you.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">11. Children and information about other people</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>The website is intended for adults, and an initial enquiry should be submitted by an adult. We do not intentionally collect information about a child unless it is clearly necessary for a requested service and is provided by a parent or guardian. If you give us information about a travel companion, you confirm that you have authority to do so.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">12. How we protect information</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>We use reasonable access controls, account security, service-provider safeguards, backups and data-minimisation practices. No internet transmission or storage system is completely secure, so please avoid sending unnecessary sensitive information.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">13. Changes to this policy</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>We may update this Privacy Policy when our website, providers or legal responsibilities change. The date at the top shows the latest version. Material changes will be highlighted on this page where appropriate.</p>
<!-- /wp:paragraph -->
</article>
<!-- /wp:group -->
HTML;

$existing_privacy_id = (int) get_option( 'wp_page_for_privacy_policy' );
if ( ! $existing_privacy_id ) {
	$existing_privacy_id = $find_page( 'privacy-policy' );
}

$privacy_id = $upsert_page(
	array(
		'existing_id' => $existing_privacy_id,
		'title'       => 'Privacy Policy',
		'slug'        => 'privacy-policy',
		'content'     => $privacy_content,
		'excerpt'     => 'How ChinaWorthSeeing handles enquiry and website information.',
	)
);

update_option( 'wp_page_for_privacy_policy', $privacy_id );
$privacy_url = get_permalink( $privacy_id );

$required_rule = static function () {
	return array(
		'value'          => true,
		'message'        => 'This field is required.',
		'global_message' => 'This field is required.',
		'global'         => false,
	);
};

$text_field = static function ( $name, $label, $placeholder = '', $type = 'text', $required = true, $class = '' ) use ( $required_rule ) {
	return array(
		'index'      => 2,
		'element'    => 'input_text',
		'attributes' => array(
			'type'        => $type,
			'name'        => $name,
			'value'       => '',
			'class'       => '',
			'placeholder' => $placeholder,
			'maxlength'   => '100',
		),
		'settings'   => array(
			'container_class'   => $class,
			'label'             => $label,
			'label_placement'   => '',
			'admin_field_label' => $label,
			'help_message'      => '',
			'prefix_label'      => '',
			'suffix_label'      => '',
			'validation_rules'  => array( 'required' => array_merge( $required_rule(), array( 'value' => $required ) ) ),
			'conditional_logics' => array(),
			'is_unique'         => 'no',
		),
		'editor_options' => array(
			'title'      => $label,
			'icon_class' => 'ff-edit-text',
			'template'   => 'inputText',
		),
		'uniqElKey' => 'cws_' . $name,
	);
};

$email_field = static function () use ( $required_rule ) {
	return array(
		'index'      => 1,
		'element'    => 'input_email',
		'attributes' => array(
			'type'        => 'email',
			'name'        => 'email',
			'value'       => '',
			'id'          => '',
			'class'       => '',
			'placeholder' => 'you@example.com',
		),
		'settings'   => array(
			'container_class'   => '',
			'label'             => 'Email address',
			'label_placement'   => '',
			'help_message'      => '',
			'admin_field_label' => 'Email address',
			'prefix_label'      => '',
			'suffix_label'      => '',
			'validation_rules'  => array(
				'required' => $required_rule(),
				'email'    => array(
					'value'          => true,
					'message'        => 'Please enter a valid email address.',
					'global_message' => 'Please enter a valid email address.',
					'global'         => false,
				),
			),
			'conditional_logics' => array(),
			'is_unique'         => 'no',
		),
		'editor_options' => array(
			'title'      => 'Email address',
			'icon_class' => 'ff-edit-email',
			'template'   => 'inputText',
		),
		'uniqElKey' => 'cws_email',
	);
};

$select_field = static function ( $name, $label, $placeholder, $options, $help = '' ) use ( $required_rule ) {
	$advanced_options = array();
	foreach ( $options as $value => $option_label ) {
		$advanced_options[] = array(
			'label'      => $option_label,
			'value'      => is_int( $value ) ? $option_label : $value,
			'calc_value' => '',
		);
	}

	return array(
		'index'      => 7,
		'element'    => 'select',
		'attributes' => array(
			'name'  => $name,
			'value' => '',
			'id'    => '',
			'class' => '',
		),
		'settings'   => array(
			'dynamic_default_value' => '',
			'label'                 => $label,
			'admin_field_label'     => $label,
			'help_message'          => $help,
			'container_class'       => '',
			'label_placement'       => '',
			'placeholder'           => $placeholder,
			'advanced_options'      => $advanced_options,
			'calc_value_status'     => false,
			'enable_image_input'    => false,
			'values_visible'        => false,
			'enable_option_groups'  => 'no',
			'enable_select_2'       => 'no',
			'validation_rules'      => array( 'required' => $required_rule() ),
			'conditional_logics'    => array(),
			'randomize_options'     => 'no',
		),
		'editor_options' => array(
			'title'      => $label,
			'icon_class' => 'ff-edit-dropdown',
			'element'    => 'select',
			'template'   => 'select',
		),
		'uniqElKey' => 'cws_' . $name,
	);
};

$container = static function ( $fields_by_column, $class = '' ) {
	$columns = array();
	$width   = 100 / count( $fields_by_column );
	foreach ( $fields_by_column as $column_fields ) {
		$columns[] = array(
			'width'  => $width,
			'fields' => $column_fields,
		);
	}

	return array(
		'index'      => 1,
		'element'    => 'container',
		'attributes' => array(),
		'settings'   => array(
			'container_class'    => $class,
			'conditional_logics' => array(),
			'container_width'    => '',
			'is_width_auto_calc' => true,
		),
		'columns' => $columns,
		'editor_options' => array(
			'title'      => count( $columns ) . ' Column Container',
			'icon_class' => 'ff-edit-column-' . count( $columns ),
		),
		'uniqElKey' => 'cws_container_' . wp_generate_uuid4(),
	);
};

$html_field = static function ( $html, $class, $key ) {
	return array(
		'index'      => 17,
		'element'    => 'custom_html',
		'attributes' => array(),
		'settings'   => array(
			'html_codes'         => $html,
			'conditional_logics' => array(),
			'container_class'    => $class,
		),
		'editor_options' => array(
			'title'      => 'Custom HTML',
			'icon_class' => 'ff-edit-html',
			'template'   => 'customHTML',
		),
		'uniqElKey' => $key,
	);
};

$destinations = array(
	'Beijing',
	'Shanghai',
	"Xi'an",
	'Guilin',
	'Hangzhou',
	'Suzhou',
	'Guangzhou',
	'Chengdu',
	'Chongqing',
	"Not sure yet / I'd like your advice",
);

$destination_options = array();
foreach ( $destinations as $destination ) {
	$destination_options[] = array(
		'label'      => $destination,
		'value'      => $destination,
		'calc_value' => '',
	);
}

$destination_field = array(
	'index'      => 10,
	'element'    => 'select',
	'attributes' => array(
		'name'        => 'destinations',
		'value'       => array(),
		'id'          => '',
		'class'       => '',
		'placeholder' => '',
		'multiple'    => true,
	),
	'settings'   => array(
		'dynamic_default_value' => '',
		'help_message'          => 'Select as many places as you like.',
		'container_class'       => '',
		'label'                 => 'Where would you like to go?',
		'admin_field_label'     => 'Destinations',
		'label_placement'       => '',
		'placeholder'           => 'Choose one or more destinations',
		'max_selection'         => '',
		'advanced_options'      => $destination_options,
		'calc_value_status'     => false,
		'enable_image_input'    => false,
		'enable_option_groups'  => 'no',
		'randomize_options'     => 'no',
		'validation_rules'      => array( 'required' => $required_rule() ),
		'conditional_logics'    => array(),
	),
	'editor_options' => array(
		'title'      => 'Destinations',
		'icon_class' => 'ff-edit-multiple-choice',
		'element'    => 'select',
		'template'   => 'select',
	),
	'uniqElKey' => 'cws_destinations',
);

$month_options = array(
	'January', 'February', 'March', 'April', 'May', 'June',
	'July', 'August', 'September', 'October', 'November', 'December',
	'Not sure yet',
);

$year_options = array(
	'2026', '2027', '2028', '2029', '2030', '2031 or later', 'Not sure yet',
);

$duration_options = array(
	'1–3 days', '4–7 days', '8–14 days', '15–21 days', '22 days or more', 'Not sure yet',
);

$traveller_options = array(
	'1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11 or more',
);

$calling_codes_path = WPMU_PLUGIN_DIR . '/assets/cws-country-calling-codes.json';
if ( ! file_exists( $calling_codes_path ) ) {
	throw new RuntimeException( 'Country calling-code data is missing.' );
}

$calling_codes_data = json_decode( (string) file_get_contents( $calling_codes_path ), true );
if ( empty( $calling_codes_data['countries'] ) || ! is_array( $calling_codes_data['countries'] ) ) {
	throw new RuntimeException( 'Country calling-code data is invalid.' );
}

$country_calling_code_options = array();
foreach ( $calling_codes_data['countries'] as $country ) {
	$iso       = isset( $country['iso'] ) ? strtoupper( (string) $country['iso'] ) : '';
	$name      = isset( $country['name'] ) ? (string) $country['name'] : '';
	$dial_code = isset( $country['dial_code'] ) ? (string) $country['dial_code'] : '';

	if ( '' === $iso || '' === $name || '' === $dial_code ) {
		continue;
	}

	$country_calling_code_options[ $iso . ' ' . $dial_code ] = $name . ' (' . $dial_code . ')';
}

$country_code_field = $select_field(
	'whatsapp_country_code',
	'WhatsApp country code',
	'Select a country code',
	$country_calling_code_options
);
$country_code_field['settings']['enable_select_2'] = 'yes';
$country_code_field['settings']['container_class'] = 'cws-country-code-field';

$comments_field = array(
	'index'      => 3,
	'element'    => 'textarea',
	'attributes' => array(
		'name'        => 'additional_comments',
		'value'       => '',
		'id'          => '',
		'class'       => '',
		'placeholder' => 'Tell us what you would most like to experience, or anything else that would help us understand your trip.',
		'rows'        => '6',
		'cols'        => '2',
		'maxlength'   => '2000',
	),
	'settings'   => array(
		'container_class'   => '',
		'label'             => 'Anything else you would like us to know?',
		'label_placement'   => '',
		'admin_field_label' => 'Additional comments',
		'help_message'      => 'Optional',
		'validation_rules'  => array( 'required' => array_merge( $required_rule(), array( 'value' => false ) ) ),
		'conditional_logics' => array(),
	),
	'editor_options' => array(
		'title'      => 'Additional comments',
		'icon_class' => 'ff-edit-textarea',
		'template'   => 'inputTextarea',
	),
	'uniqElKey' => 'cws_additional_comments',
);

$privacy_note = sprintf(
	'<p class="cws-privacy-note">By submitting this form, you confirm that you have read our <strong><a href="%1$s">Privacy Policy</a></strong> and understand that we will use your information to respond to your enquiry.</p><p class="cws-sensitive-note">Please do not include passport numbers, payment-card details or unnecessary sensitive information in this initial enquiry.</p>',
	esc_url( $privacy_url )
);

$trip_section = $container(
	array(
		array(
			$html_field( '<h2 class="cws-form-section-heading">Your trip</h2>', '', 'cws_trip_heading' ),
			$destination_field,
			$container(
				array(
					array( $select_field( 'travel_month', 'When would you like to go?', 'Select a month', $month_options ) ),
					array( $select_field( 'travel_year', 'Expected year', 'Select a year', $year_options ) ),
				)
			),
			$container(
				array(
					array( $select_field( 'trip_duration', 'How long for?', 'Select a duration', $duration_options ) ),
					array( $select_field( 'traveller_count', 'How many people are travelling?', 'Select travellers', $traveller_options ) ),
				)
			),
			$comments_field,
		),
	),
	'cws-form-section cws-form-section--trip'
);

$details_section = $container(
	array(
		array(
			$html_field( '<h2 class="cws-form-section-heading">Your details</h2>', '', 'cws_details_heading' ),
			$container(
				array(
					array( $text_field( 'first_name', 'First name', 'Your first name' ) ),
					array( $text_field( 'last_name', 'Last name', 'Your last name' ) ),
				)
			),
			$email_field(),
			$container(
				array(
					array( $country_code_field ),
					array( $text_field( 'whatsapp_number', 'WhatsApp number', 'Your number', 'tel' ) ),
				)
			),
			$html_field( $privacy_note, '', 'cws_privacy_note' ),
		),
	),
	'cws-form-section cws-form-section--details'
);

$form_fields = array(
	'fields' => array( $trip_section, $details_section ),
	'submitButton' => array(
		'uniqElKey' => 'cws_submit',
		'element'   => 'button',
		'attributes' => array(
			'type'  => 'submit',
			'class' => '',
		),
		'settings' => array(
			'align'            => 'left',
			'button_style'     => 'default',
			'container_class'  => '',
			'help_message'     => '',
			'background_color' => '#315f52',
			'button_size'      => 'md',
			'color'            => '#ffffff',
			'button_ui'        => array(
				'type'    => 'default',
				'text'    => 'Send enquiry',
				'img_url' => '',
			),
		),
		'editor_options' => array( 'title' => 'Submit Button' ),
	),
);

$turnstile_active = (bool) get_option( '_fluentform_turnstile_keys_status', false );
if ( $turnstile_active ) {
	$form_fields['fields'][] = array(
		'element'    => 'turnstile',
		'attributes' => array( 'name' => 'turnstile' ),
		'uniqElKey'  => 'cws_turnstile',
	);
}

$form_table = wpFluent()->table( 'fluentform_forms' );
$form       = $form_table->where( 'title', 'like', 'ChinaWorthSeeing Trip Enquiry%' )->first();
$now        = current_time( 'mysql' );
$form_data  = array(
	'title'       => 'ChinaWorthSeeing Trip Enquiry',
	'status'      => 'published',
	'form_fields' => wp_json_encode( $form_fields, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ),
	'has_payment' => 0,
	'type'        => 'form',
	'updated_at'  => $now,
);

if ( $form ) {
	$form_id = (int) $form->id;
	$form_table->where( 'id', $form_id )->update( $form_data );
} else {
	$form_data['created_by'] = get_current_user_id();
	$form_data['created_at'] = $now;
	$form_id = (int) $form_table->insertGetId( $form_data );
}

if ( ! $form_id ) {
	throw new RuntimeException( 'Unable to create the Fluent Forms enquiry form.' );
}

$upsert_form_meta = static function ( $form_id, $meta_key, $value ) {
	$query = wpFluent()->table( 'fluentform_form_meta' )
		->where( 'form_id', $form_id )
		->where( 'meta_key', $meta_key );
	$existing = $query->first();
	$stored   = is_string( $value ) ? $value : wp_json_encode( $value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

	if ( $existing ) {
		return wpFluent()->table( 'fluentform_form_meta' )->where( 'id', $existing->id )->update( array( 'value' => $stored ) );
	}

	return wpFluent()->table( 'fluentform_form_meta' )->insertGetId(
		array(
			'form_id'  => $form_id,
			'meta_key' => $meta_key,
			'value'    => $stored,
		)
	);
};

$form_settings = array(
	'confirmation' => array(
		'redirectTo'           => 'samePage',
		'messageToShow'        => '<strong>Thank you — your enquiry has been received.</strong><br>We will review your trip details and contact you by email or WhatsApp.',
		'customPage'           => null,
		'samePageFormBehavior' => 'hide_form',
		'customUrl'            => null,
	),
	'restrictions' => array(
		'limitNumberOfEntries' => array(
			'enabled'         => false,
			'numberOfEntries' => null,
			'period'          => 'total',
			'limitReachedMsg' => 'Maximum number of entries exceeded.',
		),
		'scheduleForm' => array(
			'enabled'      => false,
			'start'        => null,
			'end'          => null,
			'selectedDays' => null,
			'pendingMsg'   => 'This form is not open yet.',
			'expiredMsg'   => 'This form is currently closed.',
		),
		'requireLogin' => array(
			'enabled'         => false,
			'requireLoginMsg' => 'You must be logged in to submit the form.',
		),
		'denyEmptySubmission' => array(
			'enabled' => true,
			'message' => 'Please complete the required fields before submitting.',
		),
	),
	'layout' => array(
		'labelPlacement'        => 'top',
		'helpMessagePlacement'  => 'with_label',
		'errorMessagePlacement' => 'inline',
		'cssClassName'          => 'cws-enquiry-form',
		'asteriskPlacement'     => 'asterisk-right',
	),
	'delete_entry_on_submission' => 'no',
);

$notification = array(
	'name'   => 'ChinaWorthSeeing enquiry notification',
	'sendTo' => array(
		'type'    => 'email',
		'email'   => $notification_email,
		'field'   => 'email',
		'routing' => array(),
	),
	'fromName'  => 'ChinaWorthSeeing Website',
	'fromEmail' => '{wp.admin_email}',
	'replyTo'   => '{inputs.email}',
	'bcc'       => '',
	'subject'   => 'New ChinaWorthSeeing enquiry from {inputs.first_name} {inputs.last_name}',
	'message'   => '<p>A new trip enquiry has been submitted.</p><p>{all_data}</p><p>Submitted from: {embed_post.permalink}</p>',
	'conditionals' => array(
		'status'     => false,
		'type'       => 'all',
		'conditions' => array(),
	),
	'enabled'        => true,
	'email_template' => '',
);

$upsert_form_meta( $form_id, 'formSettings', $form_settings );
$upsert_form_meta( $form_id, 'notifications', $notification );
$upsert_form_meta( $form_id, '_primary_email_field', 'email' );
$upsert_form_meta( $form_id, 'step_data_persistency_status', 'no' );
$upsert_form_meta( $form_id, 'cws_managed_marker', $managed_marker );

$contact_content = sprintf(
	'<!-- %1$s -->
<!-- wp:group {"tagName":"div","className":"cws-contact-page","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-contact-page">
<!-- wp:group {"tagName":"section","className":"cws-contact-hero","layout":{"type":"constrained"}} -->
<section class="wp-block-group cws-contact-hero">
<!-- wp:paragraph {"className":"cws-contact-hero__eyebrow"} -->
<p class="cws-contact-hero__eyebrow">Make an enquiry</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"className":"cws-contact-hero__title"} -->
<h1 class="wp-block-heading cws-contact-hero__title">Let’s shape your journey through China</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"cws-contact-hero__copy"} -->
<p class="cws-contact-hero__copy">Tell us where you would like to go and how you prefer to travel. We will use your answers to understand the trip you have in mind.</p>
<!-- /wp:paragraph -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"cws-enquiry-layout","layout":{"type":"constrained"}} -->
<section class="wp-block-group cws-enquiry-layout">
<!-- wp:group {"className":"cws-enquiry-layout__form","layout":{"type":"constrained"}} -->
<div class="wp-block-group cws-enquiry-layout__form">
<!-- wp:shortcode -->
[fluentform id="%2$d"]
<!-- /wp:shortcode -->
</div>
<!-- /wp:group -->

<!-- wp:group {"tagName":"aside","className":"cws-enquiry-layout__aside cws-contact-panel","layout":{"type":"constrained"}} -->
<aside class="wp-block-group cws-enquiry-layout__aside cws-contact-panel">
<!-- wp:paragraph {"className":"cws-contact-panel__eyebrow"} -->
<p class="cws-contact-panel__eyebrow">A thoughtful first step</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"className":"cws-contact-panel__title"} -->
<h2 class="wp-block-heading cws-contact-panel__title">Share the trip you have in mind</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"cws-contact-panel__copy"} -->
<p class="cws-contact-panel__copy">Your enquiry is reviewed personally. If it looks like we can help, the conversation can continue by email or WhatsApp.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"cws-contact-panel__link"} -->
<p class="cws-contact-panel__link"><a href="mailto:%3$s">%3$s</a></p>
<!-- /wp:paragraph -->
</aside>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
</div>
<!-- /wp:group -->',
	esc_html( $managed_marker ),
	$form_id,
	esc_html( $notification_email )
);

$contact_id = $upsert_page(
	array(
		'existing_id' => $find_page( 'contact' ),
		'title'       => 'Contact',
		'slug'        => 'contact',
		'content'     => $contact_content,
		'excerpt'     => 'Tell ChinaWorthSeeing about the China trip you have in mind.',
	)
);

// A page selected as WordPress's posts index ignores its page content. Contact
// must remain a normal page so the enquiry form can render at /contact/.
if ( (int) get_option( 'page_for_posts' ) === $contact_id ) {
	update_option( 'page_for_posts', 0 );
}

update_post_meta( $contact_id, '_cws_content_status', 'confirmed-contact-enquiry' );
update_post_meta( $privacy_id, '_cws_content_status', 'confirmed-privacy-policy' );

$result = array(
	'contact_page_id'          => $contact_id,
	'contact_url'              => get_permalink( $contact_id ),
	'privacy_page_id'          => $privacy_id,
	'privacy_url'              => $privacy_url,
	'fluent_form_id'           => $form_id,
	'notification_email'       => $notification_email,
	'turnstile_keys_configured' => $turnstile_active,
	'turnstile_field_included' => $turnstile_active,
);

echo wp_json_encode( $result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . PHP_EOL;
