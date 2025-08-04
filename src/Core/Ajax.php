<?php
namespace IBC\Core;

use IBC\Models\Booking;

class Ajax {

	public function __construct() {
		add_action( 'wp_ajax_ibc_admin_get_events', [ $this, 'admin_events' ] );
		add_action( 'wp_ajax_ibc_admin_save_event', [ $this, 'save_event' ] );
		add_action( 'wp_ajax_ibc_frontend_get_events', [ $this, 'frontend_events' ] );
		add_action( 'wp_ajax_ibc_frontend_book', [ $this, 'frontend_book' ] );
		add_action( 'wp_ajax_ibc_frontend_delete', [ $this, 'frontend_delete' ] );
		add_action( 'wp_ajax_ibc_send_stats', [ $this, 'send_stats' ] );
	}

	public function admin_events(): void {
		check_ajax_referer( 'ibc_nonce' );
		wp_send_json_success( Booking::all( $_GET['start'], $_GET['end'] ) );
	}

	public function save_event(): void {
		check_ajax_referer( 'ibc_nonce' );
		$data = wp_unslash( $_POST );
		$id   = Booking::save_event( $data );
		wp_send_json_success( $id );
	}

	public function frontend_events(): void {
		check_ajax_referer( 'ibc_nonce' );
		wp_send_json_success( Booking::all( $_GET['start'], $_GET['end'] ) );
	}

	public function frontend_book(): void {
		check_ajax_referer( 'ibc_nonce' );
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( 'Login required' );
		}
		$id = Booking::book( get_current_user_id(), wp_unslash( $_POST ) );
		wp_send_json_success( $id );
	}

	public function frontend_delete(): void {
		check_ajax_referer( 'ibc_nonce' );
		$id = absint( $_POST['id'] );
		Booking::delete_for_user( $id, get_current_user_id() );
		wp_send_json_success();
	}

	public function send_stats(): void {
		check_ajax_referer( 'ibc_nonce' );
		\IBC\Emails\Statistics::send( $_POST['from'], $_POST['to'] );
		wp_send_json_success();
	}
}
new Ajax();