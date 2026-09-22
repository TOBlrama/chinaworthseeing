<?php
/**
 * Remove the default Hever/Varia footer widgets from the site.
 *
 * Safe to rerun. The site name and Privacy Policy footer information are not
 * stored in sidebar-1 and are therefore preserved.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$widget_ids = array( 'block-2', 'block-3', 'block-4', 'block-5', 'block-6', 'block-8' );
$sidebars   = get_option( 'sidebars_widgets', array() );
$removed_from_sidebars = array();

foreach ( $sidebars as $sidebar_id => $assigned_widgets ) {
	if ( ! is_array( $assigned_widgets ) ) {
		continue;
	}

	$removed = array_values( array_intersect( $assigned_widgets, $widget_ids ) );
	if ( $removed ) {
		$removed_from_sidebars[ $sidebar_id ] = $removed;
		$sidebars[ $sidebar_id ] = array_values( array_diff( $assigned_widgets, $widget_ids ) );
	}
}

update_option( 'sidebars_widgets', $sidebars );

$widget_blocks = get_option( 'widget_block', array() );
$removed_block_instances = array();

foreach ( $widget_ids as $widget_id ) {
	$instance_id = (int) str_replace( 'block-', '', $widget_id );
	if ( isset( $widget_blocks[ $instance_id ] ) ) {
		$removed_block_instances[] = $widget_id;
		unset( $widget_blocks[ $instance_id ] );
	}
}

update_option( 'widget_block', $widget_blocks );

echo wp_json_encode(
	array(
		'removed_from_sidebars'  => $removed_from_sidebars,
		'removed_widget_blocks'  => $removed_block_instances,
		'footer_sidebar_widgets' => isset( $sidebars['sidebar-1'] ) ? $sidebars['sidebar-1'] : array(),
		'site_name_preserved'    => get_bloginfo( 'name' ),
		'privacy_policy_url'     => get_privacy_policy_url(),
	),
	JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
) . PHP_EOL;
