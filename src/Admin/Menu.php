<?php
namespace IBC\Admin;

class Menu {

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'register' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'assets' ] );
	}

	public function register(): void {
		add_menu_page(
			'Internal Bookings',
			'Bookings',
			'manage_options',
			'ibc-calendar',
			[ $this, 'render' ],
			'dashicons-calendar-alt',
			26
		);
	}

	public function assets( $hook ): void {
		if ( $hook !== 'toplevel_page_ibc-calendar' ) {
			return;
		}

		wp_enqueue_style( 'ibc-admin', IBC_URL . 'assets/css/admin.css', [], IBC_VERSION );
		wp_enqueue_script( 'ibc-admin', IBC_URL . 'assets/js/admin.js', [ 'jquery' ], IBC_VERSION, true );
		wp_localize_script( 'ibc-admin', 'ibcObj', [
			'ajax' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'ibc_nonce' ),
		] );
	}

	public function render(): void {
		echo '<div id="ibc-react-admin"></div>';
	}
}