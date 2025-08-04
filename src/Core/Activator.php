<?php
namespace IBC\Core;

class Activator {

	public static function run(): void {
		global $wpdb;

		$charset = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$wpdb->prefix}ibc_bookings (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			user_id bigint(20) unsigned NOT NULL,
			category_id int(11) NOT NULL,
			title varchar(255) NOT NULL,
			start datetime NOT NULL,
			end datetime NOT NULL,
			type enum('booking','event') NOT NULL DEFAULT 'booking',
			recurrence_end date DEFAULT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id)
		) $charset;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		add_option( 'ibc_db_version', IBC_VERSION );
	}
}