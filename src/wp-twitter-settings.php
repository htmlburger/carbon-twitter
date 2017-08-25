<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'theme_options', apply_filters( 'carbon_twitter_settings_title', __( 'Twitter Settings', 'carbon-twitter' ) ) )
	->set_page_parent( apply_filters( 'carbon_twitter_settings_page_parent', 'crbn-theme-options.php' ) )
	->add_fields( apply_filters( 'carbon_twitter_settings_fields', array(
		Field::make( 'html', 'carbon_twitter_settings_html' )
			->set_html( carbon_twitter_get_options_help_text() ),
		Field::make( 'text', 'carbon_twitter_consumer_key', __( 'Consumer Key', 'carbon-twitter' ) ),
		Field::make( 'text', 'carbon_twitter_consumer_secret', __( 'Consumer Secret', 'carbon-twitter' ) ),
		Field::make( 'text', 'carbon_twitter_access_token', __( 'Access Token', 'carbon-twitter' ) ),
		Field::make( 'text', 'carbon_twitter_access_token_secret', __( 'Access Token Secret', 'carbon-twitter' ) ),
	) ) );
