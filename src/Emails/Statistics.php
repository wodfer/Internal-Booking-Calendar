<?php
namespace IBC\Emails;

class Statistics {

	public static function send( string $from, string $to ): void {
		global $wpdb;
		$table = $wpdb->prefix . 'ibc_bookings';

		$rows = $wpdb->get_results( $wpdb->prepare(
			"SELECT category_id, COUNT(*) AS qty
			 FROM $table
			 WHERE type='booking' AND start BETWEEN %s AND %s
			 GROUP BY category_id",
			$from, $to
		) );

		$body = "Statistics $from – $to\n\n";
		foreach ( $rows as $row ) {
			$cat  = get_term( $row->category_id, 'ibc_category' );
			$body .= "{$cat->name}: {$row->qty}\n";
		}

		wp_mail( get_bloginfo( 'admin_email' ), 'Monthly Booking Statistics', $body );
	}
}