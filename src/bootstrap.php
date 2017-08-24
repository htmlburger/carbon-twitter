<?php

require_once __DIR__ . '/functions.php';

/**
 * WordPress Related functionality.
 */
if ( function_exists( 'wp' ) ) {
	require_once __DIR__ . '/wp-functions.php';

	add_action( 'carbon_register_fields', 'carbon_twitter_register_options', 100 );
	add_action( 'widgets_init', 'carbon_twitter_register_widget', 100 );
}
