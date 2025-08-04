<?php
/**
 * Plugin Name: Internal Booking Calendar
 * Description: Lightweight booking calendar for WP 6.8+, PHP 8.1+
 * Version:     1.0.3
 * Text Domain: ibc
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'IBC_PATH', plugin_dir_path( __FILE__ ) );
define( 'IBC_URL',  plugin_dir_url( __FILE__ ) );

/* ----------  1. Load the bootstrap (correct path & case)  ---------- */
require_once IBC_PATH . 'src/Core/Bootstrap.php';
require_once IBC_PATH . 'src/Core/Activator.php';
require_once IBC_PATH . 'src/Core/Deactivator.php';

/* ----------  2. Activation / Deactivation hooks  ---------- */
register_activation_hook( __FILE__,   [ \IBC\Core\Activator::class,   'run' ] );
register_deactivation_hook( __FILE__, [ \IBC\Core\Deactivator::class, 'run' ] );

/* ----------  3. Kick everything off  ---------- */
add_action( 'plugins_loaded', [ \IBC\Core\Bootstrap::class, 'init' ] );