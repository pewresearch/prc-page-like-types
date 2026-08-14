<?php
/**
 * Registers DataViews admin lists for page-like types.
 *
 * @package PRC\Platform\Page_Like_Types
 */

namespace PRC\Platform\Page_Like_Types;

/**
 * Soft-depends on prc-wp-admin-dataview via the register_lists action.
 */
class Admin_Dataview_Lists {
	/**
	 * Constructor.
	 *
	 * @param Loader $loader Plugin loader.
	 */
	public function __construct( $loader ) {
		$loader->add_action( 'prc_wp_admin_dataview_register_lists', $this, 'register_lists', 10, 1 );
	}

	/**
	 * Register list configs for mini-course, events, and fact-sheet.
	 *
	 * @param object $lists List registry from prc-wp-admin-dataview.
	 */
	public function register_lists( $lists ): void {
		if ( ! is_object( $lists ) || ! method_exists( $lists, 'register' ) ) {
			return;
		}

		foreach ( self::list_configs() as $config ) {
			$lists->register( $config );
		}
	}

	/**
	 * List configs owned by this plugin.
	 *
	 * @return array<int, array<string, string>>
	 */
	public static function list_configs(): array {
		return array(
			array(
				'postType'  => 'mini-course',
				'pageSlug'  => 'prc-wp-admin-dataview-mini-course',
				'menuTitle' => __( 'All Courses', 'prc-page-like-types' ),
				'pageTitle' => __( 'All Courses', 'prc-page-like-types' ),
			),
			array(
				'postType'  => 'events',
				'pageSlug'  => 'prc-wp-admin-dataview-events',
				'menuTitle' => __( 'All Events', 'prc-page-like-types' ),
				'pageTitle' => __( 'All Events', 'prc-page-like-types' ),
			),
			array(
				'postType'  => 'fact-sheet',
				'pageSlug'  => 'prc-wp-admin-dataview-fact-sheet',
				'menuTitle' => __( 'All Fact Sheets', 'prc-page-like-types' ),
				'pageTitle' => __( 'All Fact Sheets', 'prc-page-like-types' ),
			),
		);
	}
}
