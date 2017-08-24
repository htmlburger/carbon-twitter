<?php

function carbon_twitter_register_widget() {
	require_once __DIR__ . '/wp-widget.php';

	register_widget( 'Carbon_Twitter_Widget' );
}

function carbon_twitter_register_options() {
	require_once __DIR__ . '/wp-theme-options.php';
}
