<?php
/**
 * Plugin Name: ChinaWorthSeeing Home Destinations
 * Description: Adds progressive carousel controls to the native homepage destination track.
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'wp_enqueue_scripts',
	static function () {
		if ( ! is_front_page() ) {
			return;
		}

		$relative_path = 'assets/cws-home-destinations.js';
		$file_path     = WPMU_PLUGIN_DIR . '/' . $relative_path;

		if ( ! file_exists( $file_path ) ) {
			return;
		}

		wp_enqueue_script(
			'cws-home-destinations',
			WPMU_PLUGIN_URL . '/' . $relative_path,
			array(),
			(string) filemtime( $file_path ),
			true
		);

		wp_script_add_data( 'cws-home-destinations', 'strategy', 'defer' );
	}
);
