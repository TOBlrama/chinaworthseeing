<?php
/**
 * Plugin Name: ChinaWorthSeeing Explore Navigation
 * Description: Adds accessible mega-menu and mobile accordion behavior to the native Primary Navigation menu.
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'wp_enqueue_scripts',
	static function () {
		$relative_path = 'assets/cws-explore-navigation.js';
		$file_path     = WPMU_PLUGIN_DIR . '/' . $relative_path;

		if ( ! file_exists( $file_path ) ) {
			return;
		}

		wp_enqueue_script(
			'cws-explore-navigation',
			WPMU_PLUGIN_URL . '/' . $relative_path,
			array(),
			(string) filemtime( $file_path ),
			true
		);

		wp_script_add_data( 'cws-explore-navigation', 'strategy', 'defer' );
	}
);

add_filter(
	'the_title',
	static function ( $title, $post_id ) {
		if (
			! is_admin()
			&& is_page( 'explore-china' )
			&& in_the_loop()
			&& is_main_query()
			&& (int) get_queried_object_id() === (int) $post_id
		) {
			return '';
		}

		return $title;
	},
	20,
	2
);

add_filter(
	'walker_nav_menu_start_el',
	static function ( $item_output, $item, $depth, $args ) {
		$classes = isset( $item->classes ) && is_array( $item->classes ) ? $item->classes : array();

		if (
			in_array( 'cws-mega-menu', $classes, true )
			|| in_array( 'cws-mega-group', $classes, true )
		) {
			return sprintf(
				'%1$s<span class="cws-nav-label">%2$s</span>%3$s',
				isset( $args->before ) ? $args->before : '',
				esc_html( $item->title ),
				isset( $args->after ) ? $args->after : ''
			);
		}

		if ( ! in_array( 'cws-mega-featured', $classes, true ) ) {
			return $item_output;
		}

		$page_id = 'page' === $item->object ? (int) $item->object_id : 0;
		$page    = $page_id ? get_post( $page_id ) : null;
		$image   = $page_id
			? get_the_post_thumbnail(
				$page_id,
				'medium_large',
				array(
					'class'    => 'cws-mega-featured__image',
					'loading'  => 'lazy',
					'decoding' => 'async',
				)
			)
			: '';
		$excerpt = $page ? trim( (string) $page->post_excerpt ) : '';

		if ( ! $image || ! $page ) {
			return $item_output;
		}

		return sprintf(
			'%1$s<a href="%2$s" class="cws-mega-featured__link"><span class="cws-mega-featured__eyebrow">Featured</span><span class="cws-mega-featured__media">%3$s</span><span class="cws-mega-featured__title">%4$s</span><span class="cws-mega-featured__copy">%5$s</span><span class="cws-mega-featured__action">Start here <span aria-hidden="true">→</span></span></a>%6$s',
			isset( $args->before ) ? $args->before : '',
			esc_url( $item->url ),
			$image,
			esc_html( $item->title ),
			esc_html( $excerpt ),
			isset( $args->after ) ? $args->after : ''
		);
	},
	10,
	4
);
