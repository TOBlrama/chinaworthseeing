<?php
/**
 * Plugin Name: ChinaWorthSeeing Contact Enquiry
 * Description: Loads the maintainable Contact form and Privacy Policy presentation layer.
 * Version: 1.0.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'wp_enqueue_scripts',
	static function () {
		if ( ! is_page( array( 'contact', 'privacy-policy' ) ) ) {
			return;
		}

		$relative_path = 'assets/cws-contact-enquiry.css';
		$file_path     = WPMU_PLUGIN_DIR . '/' . $relative_path;

		if ( ! file_exists( $file_path ) ) {
			return;
		}

		wp_enqueue_style(
			'cws-contact-enquiry',
			WPMU_PLUGIN_URL . '/' . $relative_path,
			array(),
			(string) filemtime( $file_path )
		);

		if ( is_page( 'contact' ) ) {
			$script_relative_path = 'assets/cws-contact-enquiry.js';
			$script_file_path     = WPMU_PLUGIN_DIR . '/' . $script_relative_path;

			if ( file_exists( $script_file_path ) ) {
				wp_enqueue_script(
					'cws-contact-enquiry',
					WPMU_PLUGIN_URL . '/' . $script_relative_path,
					array(),
					(string) filemtime( $script_file_path ),
					true
				);
			}
		}
	}
);

add_filter(
	'body_class',
	static function ( $classes ) {
		if ( is_page( 'contact' ) ) {
			$classes[] = 'cws-contact-template';
		}

		if ( is_page( 'privacy-policy' ) ) {
			$classes[] = 'cws-privacy-template';
		}

		return $classes;
	}
);

/**
 * Keep the prompt visible before selection without presenting it as a choice.
 * Fluent Forms otherwise renders its placeholder as a normal empty option.
 */
add_filter(
	'fluentform/rendering_field_html_select',
	static function ( $html, $data, $form ) {
		$form_title = isset( $form->title ) ? (string) $form->title : '';
		$field_name = isset( $data['attributes']['name'] ) ? (string) $data['attributes']['name'] : '';
		$prompt_only_fields = array(
			'travel_month',
			'travel_year',
			'trip_duration',
			'traveller_count',
			'whatsapp_country_code',
		);

		if (
			false === strpos( $form_title, 'ChinaWorthSeeing Trip Enquiry' )
			|| ! in_array( $field_name, $prompt_only_fields, true )
		) {
			return $html;
		}

		return preg_replace(
			'/<option value="">/',
			'<option value="" selected disabled hidden>',
			$html,
			1
		);
	},
	10,
	3
);
