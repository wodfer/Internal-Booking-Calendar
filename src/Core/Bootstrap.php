<?php
/**
 * Internal Booking Calendar – Core Bootstrap
 */

namespace IBC\Core;

class Bootstrap {

	/**
	 * Kick everything off.
	 */
	public static function init(): void {

		// 1. Autoload classes under src/
		spl_autoload_register( [ __CLASS__, 'autoload' ] );

		// 2. Register our taxonomy BEFORE anything else uses it
		add_action( 'init', [ __CLASS__, 'register_booking_taxonomy' ] );

		// 3. Load the rest of the plugin
		new Admin\Menu();
		new Frontend\Calendar();
		new Blocks\CalendarBlock();
		new Emails\StatisticsCron();
	}

	/**
	 * Very small autoloader: src/Namespace/Class.php
	 */
	private static function autoload( string $class ): void {
		$prefix   = 'IBC\\';
		$base_dir = trailingslashit( IBC_PATH ) . 'src/';
		$len      = strlen( $prefix );

		if ( strncmp( $prefix, $class, $len ) !== 0 ) {
			return;
		}

		$relative = substr( $class, $len );
		$file     = $base_dir . str_replace( '\\', '/', $relative ) . '.php';

		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}

	/**
	 * Register the custom taxonomy “Booking Category”.
	 * We attach it to *no* post type (null), because we store bookings
	 * in our own table.  The UI is still shown in the admin menu.
	 */
	public static function register_booking_taxonomy(): void {

		$labels = [
			'name'                       => _x( 'Booking Categories', 'taxonomy general name', 'internal-booking-calendar' ),
			'singular_name'              => _x( 'Booking Category',  'taxonomy singular name', 'internal-booking-calendar' ),
			'search_items'               => __( 'Search categories', 'internal-booking-calendar' ),
			'all_items'                  => __( 'All categories',   'internal-booking-calendar' ),
			'edit_item'                  => __( 'Edit category',    'internal-booking-calendar' ),
			'update_item'                => __( 'Update category',  'internal-booking-calendar' ),
			'add_new_item'               => __( 'Add new category', 'internal-booking-calendar' ),
			'new_item_name'              => __( 'New category name','internal-booking-calendar' ),
			'menu_name'                  => __( 'Categories',       'internal-booking-calendar' ),
		];

		$args = [
			'labels'            => $labels,
			'hierarchical'      => false, // flat, like tags
			'public'            => false,  // no public archive pages
			'show_ui'           => true,   // show the admin UI
			'show_in_menu'      => 'ibc-calendar', // under our plugin menu
			'show_admin_column' => true,
			'show_in_rest'      => true,   // allow Gutenberg / REST API
			'rewrite'           => false,  // no pretty URLs needed
			'description'       => __( 'Put the e-mail address that should receive ICS notifications in the term description.', 'internal-booking-calendar' ),
		];

		register_taxonomy( 'ibc_category', null, $args );
	}
}