<?php

require_once __DIR__ . '/functions.php';

/**
 * WordPress Related functionality.
 */
if ( function_exists( 'wp' ) ) {
	require_once __DIR__ . '/wp-functions.php';

	add_action( 'carbon_fields_register_fields', 'carbon_twitter_register_options', 20 );
	add_action( 'widgets_init', 'carbon_twitter_register_widget', 20 );

	add_action( 'admin_notices', 'carbon_twitter_maybe_show_missing_configuration_notice' );

	add_action( 'init', 'carbon_twitter_setup_configuration' );
}
