<?php
namespace IBC\Emails;

class StatisticsCron {

	public function __construct() {
		add_action( 'ibc_monthly_stats', [ Statistics::class, 'send_last_month' ] );
		if ( ! wp_next_scheduled( 'ibc_monthly_stats' ) ) {
			wp_schedule_event( strtotime( 'first day of next month 00:00' ), 'monthly', 'ibc_monthly_stats' );
		}
	}
}