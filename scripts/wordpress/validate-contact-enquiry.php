<?php
/** Read-only validation for the Contact enquiry form and Privacy Policy. */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 1 );
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';

$contact = get_page_by_path( 'contact', OBJECT, 'page' );
$privacy = get_page_by_path( 'privacy-policy', OBJECT, 'page' );
$form    = function_exists( 'wpFluent' )
	? wpFluent()->table( 'fluentform_forms' )->where( 'title', 'like', 'ChinaWorthSeeing Trip Enquiry%' )->first()
	: null;

$form_fields = $form ? json_decode( (string) $form->form_fields, true ) : array();
$field_names = array();
$elements    = array();
$fields_by_name = array();

$collect_fields = static function ( $fields ) use ( &$collect_fields, &$field_names, &$elements, &$fields_by_name ) {
	foreach ( (array) $fields as $field ) {
		if ( isset( $field['element'] ) ) {
			$elements[] = (string) $field['element'];
		}

		if ( ! empty( $field['attributes']['name'] ) ) {
			$field_name = (string) $field['attributes']['name'];
			$field_names[] = $field_name;
			$fields_by_name[ $field_name ] = $field;
		}

		if ( ! empty( $field['columns'] ) ) {
			foreach ( $field['columns'] as $column ) {
				$collect_fields( isset( $column['fields'] ) ? $column['fields'] : array() );
			}
		}
	}
};

$collect_fields( isset( $form_fields['fields'] ) ? $form_fields['fields'] : array() );

$meta_rows = array();
if ( $form ) {
	$rows = wpFluent()->table( 'fluentform_form_meta' )->where( 'form_id', (int) $form->id )->get();
	foreach ( $rows as $row ) {
		$meta_rows[ $row->meta_key ] = $row->value;
	}
}

$notification = isset( $meta_rows['notifications'] ) ? json_decode( $meta_rows['notifications'], true ) : array();
$settings     = isset( $meta_rows['formSettings'] ) ? json_decode( $meta_rows['formSettings'], true ) : array();
$contact_html = $contact ? (string) $contact->post_content : '';
$privacy_html = $privacy ? (string) $privacy->post_content : '';
$mu_plugin_path = WPMU_PLUGIN_DIR . '/cws-contact-enquiry.php';
$css_path       = WPMU_PLUGIN_DIR . '/assets/cws-contact-enquiry.css';
$js_path        = WPMU_PLUGIN_DIR . '/assets/cws-contact-enquiry.js';
$calling_codes_path = WPMU_PLUGIN_DIR . '/assets/cws-country-calling-codes.json';
$mu_plugin      = file_exists( $mu_plugin_path ) ? (string) file_get_contents( $mu_plugin_path ) : '';
$presentation_css = file_exists( $css_path ) ? (string) file_get_contents( $css_path ) : '';
$presentation_js = file_exists( $js_path ) ? (string) file_get_contents( $js_path ) : '';
$calling_codes_data = file_exists( $calling_codes_path )
	? json_decode( (string) file_get_contents( $calling_codes_path ), true )
	: array();
$rendered_form  = $form ? do_shortcode( '[fluentform id="' . (int) $form->id . '"]' ) : '';
$country_code_field = isset( $fields_by_name['whatsapp_country_code'] ) ? $fields_by_name['whatsapp_country_code'] : array();

$required_fields = array(
	'destinations',
	'travel_month',
	'travel_year',
	'trip_duration',
	'traveller_count',
	'additional_comments',
	'first_name',
	'last_name',
	'email',
	'whatsapp_country_code',
	'whatsapp_number',
);

$missing_fields = array_values( array_diff( $required_fields, array_unique( $field_names ) ) );
$turnstile_keys = (bool) get_option( '_fluentform_turnstile_keys_status', false );
$turnstile_form = in_array( 'turnstile', $elements, true );

$result = array(
	'contact' => array(
		'id'                    => $contact ? (int) $contact->ID : 0,
		'status'                => $contact ? $contact->post_status : 'missing',
		'url'                   => $contact ? get_permalink( $contact ) : '',
		'managed_marker'        => false !== strpos( $contact_html, 'cws-managed-contact-enquiry-v1' ),
		'fluentform_shortcode'  => $form ? false !== strpos( $contact_html, '[fluentform id="' . (int) $form->id . '"]' ) : false,
		'privacy_link_present'  => $privacy ? false !== strpos( $form ? (string) $form->form_fields : '', get_permalink( $privacy ) ) : false,
		'privacy_link_bold'     => false !== strpos( $form ? (string) $form->form_fields : '', '<strong><a href=' ),
		'privacy_link_underlined_by_css' => file_exists( WPMU_PLUGIN_DIR . '/assets/cws-contact-enquiry.css' )
			&& false !== strpos( (string) file_get_contents( WPMU_PLUGIN_DIR . '/assets/cws-contact-enquiry.css' ), '.cws-privacy-note a' ),
		'is_not_posts_page'     => $contact ? (int) get_option( 'page_for_posts' ) !== (int) $contact->ID : false,
	),
	'privacy' => array(
		'id'                 => $privacy ? (int) $privacy->ID : 0,
		'status'             => $privacy ? $privacy->post_status : 'missing',
		'url'                => $privacy ? get_permalink( $privacy ) : '',
		'is_wordpress_policy' => $privacy ? (int) get_option( 'wp_page_for_privacy_policy' ) === (int) $privacy->ID : false,
		'operator_present'   => false !== strpos( $privacy_html, 'operated by Yixiang Sheng' ),
		'email_present'      => false !== strpos( $privacy_html, 'felixsheng27@gmail.com' ),
		'updated_date'       => false !== strpos( $privacy_html, '12 August 2026' ),
	),
	'form' => array(
		'id'                     => $form ? (int) $form->id : 0,
		'status'                 => $form ? $form->status : 'missing',
		'field_names'            => array_values( array_unique( $field_names ) ),
		'missing_fields'         => $missing_fields,
		'destination_count'      => substr_count( $form ? (string) $form->form_fields : '', 'calc_value' ) >= 10 ? 10 : 0,
		'chengdu_present'        => false !== strpos( $form ? (string) $form->form_fields : '', 'Chengdu' ),
		'chongqing_present'      => false !== strpos( $form ? (string) $form->form_fields : '', 'Chongqing' ),
		'entries_saved'          => isset( $settings['delete_entry_on_submission'] ) && 'no' === $settings['delete_entry_on_submission'],
		'notification_enabled'   => ! empty( $notification['enabled'] ),
		'notification_recipient' => isset( $notification['sendTo']['email'] ) ? $notification['sendTo']['email'] : '',
		'primary_email_field'    => isset( $meta_rows['_primary_email_field'] ) ? $meta_rows['_primary_email_field'] : '',
		'country_code_is_select' => isset( $country_code_field['element'] ) && 'select' === $country_code_field['element'],
		'country_code_searchable' => isset( $country_code_field['settings']['enable_select_2'] ) && 'yes' === $country_code_field['settings']['enable_select_2'],
		'country_code_option_count' => isset( $country_code_field['settings']['advanced_options'] ) ? count( $country_code_field['settings']['advanced_options'] ) : 0,
	),
	'security' => array(
		'turnstile_keys_configured' => $turnstile_keys,
		'turnstile_field_present'   => $turnstile_form,
		'turnstile_state_consistent' => $turnstile_keys === $turnstile_form,
	),
	'presentation' => array(
		'mu_plugin_loaded'          => isset( get_mu_plugins()['cws-contact-enquiry.php'] ),
		'css_file_exists'           => file_exists( $css_path ),
		'compact_choice_remove_css' => false !== strpos( $presentation_css, '.choices__button' )
			&& false !== strpos( $presentation_css, 'width: 18px' )
			&& false !== strpos( $presentation_css, 'height: 18px' ),
		'prompt_only_select_filter' => false !== strpos( $mu_plugin, 'fluentform/rendering_field_html_select' ),
		'prompt_only_select_options' => 4 <= substr_count( $rendered_form, 'value="" selected disabled hidden' ),
		'contact_footer_hidden'     => false !== strpos( $presentation_css, '.cws-contact-template .site-footer' )
			&& false !== strpos( $presentation_css, '.cws-contact-template #colophon' ),
		'enlarged_form_labels'      => 2 <= substr_count( $presentation_css, 'font-size: 15px' ),
		'country_code_script_exists' => file_exists( $js_path )
			&& false !== strpos( $presentation_js, 'cws-country-flag' ),
		'country_calling_code_count' => isset( $calling_codes_data['countries'] ) ? count( $calling_codes_data['countries'] ) : 0,
		'theme'                     => wp_get_theme()->get_stylesheet(),
	),
);

$result['all_core_checks_pass'] = $contact
	&& 'publish' === $contact->post_status
	&& $privacy
	&& 'publish' === $privacy->post_status
	&& $form
	&& 'published' === $form->status
	&& empty( $missing_fields )
	&& ! empty( $notification['enabled'] )
	&& 'felixsheng27@gmail.com' === $result['form']['notification_recipient']
	&& $result['contact']['privacy_link_present']
	&& $result['contact']['privacy_link_bold']
	&& $result['contact']['privacy_link_underlined_by_css']
	&& $result['contact']['is_not_posts_page']
	&& $result['security']['turnstile_state_consistent']
	&& $result['presentation']['mu_plugin_loaded']
	&& $result['presentation']['compact_choice_remove_css']
	&& $result['presentation']['prompt_only_select_filter']
	&& $result['presentation']['prompt_only_select_options']
	&& $result['form']['country_code_is_select']
	&& $result['form']['country_code_searchable']
	&& 240 <= $result['form']['country_code_option_count']
	&& $result['presentation']['contact_footer_hidden']
	&& $result['presentation']['enlarged_form_labels']
	&& $result['presentation']['country_code_script_exists']
	&& 240 <= $result['presentation']['country_calling_code_count'];

echo wp_json_encode( $result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . PHP_EOL;
