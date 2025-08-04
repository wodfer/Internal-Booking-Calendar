<?php
namespace IBC\Emails;

class Ics {

	public static function send( int $booking_id ): void {
		$booking = \IBC\Models\Booking::all( '', '' ); // quick fetch
		$booking = array_filter( $booking, fn( $b ) => $b['id'] == $booking_id );
		$booking = array_shift( $booking );

		$category = get_term( $booking['category_id'], 'ibc_category' );
		if ( empty( $category ) || empty( $category->description ) ) {
			return;
		}

		$ics = self::generate_ics( $booking );
		$to  = $category->description;

		$subject = 'New booking: ' . $booking['title'];
		$headers = [ 'Content-Type: text/calendar; charset=utf-8; method=REQUEST' ];
		wp_mail( $to, $subject, $ics, $headers );
	}

	private static function generate_ics( array $b ): string {
		$dtstart = date( 'Ymd\THis', strtotime( $b['start'] ) );
		$dtend   = date( 'Ymd\THis', strtotime( $b['end'] ) );
		$uid     = $b['id'] . '@' . $_SERVER['SERVER_NAME'];

		return <<<ICS
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Internal Booking Calendar//EN
BEGIN:VEVENT
UID:$uid
DTSTART:$dtstart
DTEND:$dtend
SUMMARY:{$b['title']}
END:VEVENT
END:VCALENDAR
ICS;
	}
}