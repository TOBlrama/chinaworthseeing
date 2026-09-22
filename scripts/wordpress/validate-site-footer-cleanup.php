<?php
/** Read-only validation for the site-wide default footer-widget cleanup. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$removed_widget_ids = array( 'block-2', 'block-3', 'block-4', 'block-5', 'block-6', 'block-8' );
$sidebars           = get_option( 'sidebars_widgets', array() );
$widget_blocks      = get_option( 'widget_block', array() );
$assigned_removed_widgets = array();
$stored_removed_widgets   = array();

foreach ( $sidebars as $sidebar_id => $assigned_widgets ) {
	if ( ! is_array( $assigned_widgets ) ) {
		continue;
	}

	foreach ( array_intersect( $assigned_widgets, $removed_widget_ids ) as $widget_id ) {
		$assigned_removed_widgets[] = $sidebar_id . ':' . $widget_id;
	}
}

foreach ( $removed_widget_ids as $widget_id ) {
	$instance_id = (int) str_replace( 'block-', '', $widget_id );
	if ( isset( $widget_blocks[ $instance_id ] ) ) {
		$stored_removed_widgets[] = $widget_id;
	}
}

$footer_widgets = isset( $sidebars['sidebar-1'] ) && is_array( $sidebars['sidebar-1'] )
	? array_values( $sidebars['sidebar-1'] )
	: array();

$result = array(
	'footer_sidebar_widgets'    => $footer_widgets,
	'assigned_removed_widgets'  => $assigned_removed_widgets,
	'stored_removed_widgets'    => $stored_removed_widgets,
	'footer_sidebar_is_empty'   => empty( $footer_widgets ),
	'site_name'                 => get_bloginfo( 'name' ),
	'privacy_policy_url'        => get_privacy_policy_url(),
	'brand_footer_data_present' => '' !== get_bloginfo( 'name' ) && '' !== get_privacy_policy_url(),
);

$result['all_checks_pass'] = $result['footer_sidebar_is_empty']
	&& empty( $assigned_removed_widgets )
	&& empty( $stored_removed_widgets )
	&& $result['brand_footer_data_present'];

echo wp_json_encode( $result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . PHP_EOL;
