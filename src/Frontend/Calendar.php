<?php
namespace IBC\Frontend;

class Calendar {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'assets' ] );
		add_shortcode( 'ibc_calendar', [ $this, 'render_shortcode' ] );
	}

	public function assets(): void {
		if ( is_user_logged_in() ) {
			wp_enqueue_style( 'ibc-frontend', IBC_URL . 'assets/css/frontend.css', [], IBC_VERSION );
			wp_enqueue_script( 'ibc-frontend', IBC_URL . 'assets/js/frontend.js', [ 'jquery' ], IBC_VERSION, true );
			wp_localize_script( 'ibc-frontend', 'ibcObj', [
				'ajax'  => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'ibc_nonce' ),
			] );
		}
	}

	public function render_shortcode(): string {
		if ( ! is_user_logged_in() ) {
			return '<p>Please log in to view the calendar.</p>';
		}
		return '<div id="ibc-frontend-calendar"></div>';
	}
}