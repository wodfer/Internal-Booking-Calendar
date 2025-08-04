<?php
namespace IBC\Blocks;

class CalendarBlock {

	public function __construct() {
		add_action( 'init', [ $this, 'register' ] );
	}

	public function register(): void {
		wp_register_script(
			'ibc-block',
			IBC_URL . 'build/index.js',
			[ 'wp-blocks', 'wp-element', 'wp-editor' ],
			IBC_VERSION,
			true
		);
		register_block_type( 'ibc/calendar', [
			'editor_script' => 'ibc-block',
			'render_callback' => fn() => do_shortcode( '[ibc_calendar]' ),
		] );
	}
}