<?php
namespace IBC\Models;

use DateTime;

class Booking {

	public static function all( string $start, string $end ): array {
		global $wpdb;
		$table = $wpdb->prefix . 'ibc_bookings';

		return $wpdb->get_results( $wpdb->prepare(
			"SELECT * FROM $table WHERE start BETWEEN %s AND %s OR end BETWEEN %s AND %s",
			$start, $end, $start, $end
		), ARRAY_A );
	}

	public static function save_event( array $data ): int {
		global $wpdb;
		$table = $wpdb->prefix . 'ibc_bookings';

		$wpdb->insert( $table, [
			'user_id'         => get_current_user_id(),
			'category_id'     => absint( $data['category_id'] ),
			'title'           => sanitize_text_field( $data['title'] ),
			'start'           => $data['start'],
			'end'             => $data['end'],
			'type'            => 'event',
			'recurrence_end'  => $data['recurrence_end'] ?? null,
		] );

		return $wpdb->insert_id;
	}

	public static function book( int $user_id, array $data ): int {
		global $wpdb;
		$table = $wpdb->prefix . 'ibc_bookings';

		$wpdb->insert( $table, [
			'user_id'     => $user_id,
			'category_id' => absint( $data['category_id'] ),
			'title'       => sanitize_text_field( $data['title'] ),
			'start'       => $data['start'],
			'end'         => $data['end'],
			'type'        => 'booking',
		] );
		$id = $wpdb->insert_id;

		// send ICS
		\IBC\Emails\Ics::send( $id );

		return $id;
	}

	public static function delete_for_user( int $id, int $user_id ): void {
		global $wpdb;
		$table = $wpdb->prefix . 'ibc_bookings';
		$where = [ 'id' => $id ];
		if ( ! current_user_can( 'manage_options' ) ) {
			$where['user_id'] = $user_id;
		}
		$wpdb->delete( $table, $where );
	}
}